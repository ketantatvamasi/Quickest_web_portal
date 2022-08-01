<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Estimate;
use App\Models\Event;
use App\Models\SalesPersonPerformances;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Crypt, DB, Validator};

class DashboardController extends BaseController
{
    protected $logged_user = null;
    protected $company_id = 0;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->logged_user = Auth::user();
            $this->company_id = ($this->logged_user->company_id) ? $this->logged_user->company_id : $this->logged_user->id;
            return $next($request);
        });
    }

    public function __invoke($assign_user)
    {
        $widgets = Estimate::query()->groupBy('status')
            ->where(function ($query) use ($assign_user) {
                if ($this->logged_user->company_id == "") {
                    $query->where('company_id', '=', $this->company_id);
                } else {
                    $query->whereRaw('user_id IN  (' . $assign_user . ')');
                    $query->orWhere('user_id', $this->logged_user->id);
                }
            })
            ->select('status', DB::raw('COUNT(status) as widget_total'))->get()->toArray();
        $a = [['status' => 'Sent', 'widget_total' => 0], ['status' => 'Inprogress', 'widget_total' => 0], ['status' => 'Accept', 'widget_total' => 0], ['status' => 'Decline', 'widget_total' => 0], ['status' => 'Draft', 'widget_total' => 0]];
        $x = array_column($widgets, 'status');
        $total = 0 + array_sum(array_column($widgets, 'widget_total'));
        foreach ($a as $key => $value) {
            if (!in_array($value['status'], $x)) {
                $widgets[] = $value;
            }
        }
        usort($widgets, function ($a, $b) {
            return $a['status'] <=> $b['status'];
        });
        $widgets[] = array("status" => "Total", "widget_total" => $total);
        return $this->sendResponse($widgets, 'Widget retrieved successfully');
    }

    public function barChart($date, $assign_user)
    {
        $monthArr = [];
//        $input = $request->all();
        $dateArr = explode("_", $date);

        $year = date('Y', strtotime($dateArr[0]));
        $month = date('m', strtotime($dateArr[0]));
        for ($i = 0; $i < 12; $i++) {
            array_push($monthArr, date("M` Y", strtotime('+' . $i . ' month', date(strtotime('01-' . $month . '-' . $year)))));
        }

        $records = Estimate::query()
            ->where(function ($query) use ($dateArr) {
                $query->whereBetween(DB::raw("DATE_FORMAT(estimate_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
            })
            ->where(function ($query) use ($assign_user) {
                if ($this->logged_user->company_id == "") {
                    $query->where('company_id', '=', $this->company_id);
                } else {
                    $query->whereRaw('user_id IN  (' . $assign_user . ')');
                    $query->orWhere('user_id', $this->logged_user->id);
                }
            })
            ->groupBy('status', DB::raw("DATE_FORMAT(estimate_date, '%Y-%m-%d')"))
            ->orderBy(DB::raw("DATE_FORMAT(estimate_date, '%Y-%m-%d')"))
            ->select('status', DB::raw('SUM(net_amount) as total_count'), DB::raw("DATE_FORMAT(estimate_date, '%b` %Y') as estimate_date"))->get();

        $labels = array();
        $sent = array();
        $close = array();
        $dataArr = array();
        if (isset($records)) {
            foreach ($monthArr as $k => $v) {

                foreach ($records as $record) {
                    if ($record->estimate_date == $v) {
                        $dataArr[$v]['sent'][] = 0;
                        $dataArr[$v]['sent'][] = $record->total_count;
                        $dataArr[$v]['close'][] = 0;

                        if ($record->status == 'Accept')
                            $dataArr[$v]['close'][] = $record->total_count;

                    } else {
                        $dataArr[$v]['sent'][] = 0;
                        $dataArr[$v]['close'][] = 0;
                    }
                }
                $dataArr[$v]['sent'][] = 0;
                $dataArr[$v]['close'][] = 0;
            }

            foreach ($dataArr as $key => $value) {
                $sum_sent = array_sum($value['sent']);
                $sum_close = array_sum($value['close']);
                array_push($labels, $key);
                array_push($sent, $sum_sent);
                array_push($close, $sum_close);
            }
        }
        $data['labels'] = $labels;
        $data['sent'] = $sent;
        $data['close'] = $close;
        return $this->sendResponse($data, 'Bar chart retrieved successfully');
    }

    public function salesPerformanceChart($date, $assign_user)
    {
        $sales_performance = DB::table('sales_person_performances')
            ->where(function ($query) use ($date) {
                $dateArr = explode("_", $date);
                $query->whereBetween(DB::raw("DATE_FORMAT(performance_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
            })
            ->where(function ($query) use ($assign_user) {
                if ($this->logged_user->company_id == "") {
                    $query->where('user_id', '=', $this->logged_user->id);
//                    $query->where('company_id', '=', $this->company_id);
                } else {
//                    $query->whereRaw('user_id IN  (' . $assign_user . ')');
                    $query->orWhere('user_id', $this->logged_user->id);
                }
            })
            ->select(DB::raw("SUM(total_task) as total_task"), DB::raw("SUM(completed_task) as completed_task"), DB::raw("COUNT(id) as total_record"), DB::raw("SUM(daily_performance) as daily_performance"))
            ->get()->toArray();

        $count_all = DB::table('sales_person_performances')
            ->where('daily_performance', '>', 0)
            ->where(function ($query) use ($date) {
                $dateArr = explode("_", $date);
                $query->whereBetween(DB::raw("DATE_FORMAT(performance_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
            })
            ->where(function ($query) use ($assign_user) {
                if ($this->logged_user->company_id == "") {
                    $query->where('user_id', '=', $this->logged_user->id);
//                    $query->where('company_id', '=', $this->company_id);
                } else {
//                    $query->whereRaw('user_id IN  (' . $assign_user . ')');
                    $query->orWhere('user_id', $this->logged_user->id);
                }
            })
            ->select('id')->count();

        $total_task = array_column($sales_performance, 'total_task');
        $total_record = array_column($sales_performance, 'total_record');
        $completed_task = array_column($sales_performance, 'completed_task');
        $total_daily_performance = array_column($sales_performance, 'daily_performance');

        $data['performance']['labels'] = 'Performance';
        $data['performance']['series'] = ($count_all > 0) ? (float)number_format($total_daily_performance[0] / $count_all, 2) : 0;
        $data['completion_ratio']['labels'] = 'Completion Ratio';
        $data['completion_ratio']['series'] = ($total_task[0] > 0) ? (float)number_format(($completed_task[0] * 100) / $total_task[0], 2) : 0;

        $data['total_task'] = (int)$total_task[0];
        $data['completed_task'] = (int)$completed_task[0];
        $data['total_record'] = (int)$total_record[0];

        return $this->sendResponse($data, 'Sales performance chart retrieved successfully');
    }

    public function calendarData($start, $end, $assign_user)
    {
        $start = (!empty($start)) ? ($start) : ('');
        $end = (!empty($end)) ? ($end) : ('');

        $data = DB::table('events')
            ->leftJoin('estimates', 'events.estimate_id', 'estimates.id')
            ->leftJoin('users', 'events.user_id', 'users.id')
            ->where('events.company_id', $this->company_id)
            ->where(function ($query) use ($assign_user) {
                if ($this->logged_user->company_id == "") {
                    $query->where('events.company_id', '=', $this->company_id);
                } else {
                    $query->whereRaw('events.user_id IN  (' . $assign_user . ')');
                    $query->orWhere('events.user_id', $this->logged_user->id);
                }
            })
            ->whereDate('events.start_date', '>=', $start)->whereDate('end_date', '<=', $end)
            ->select('events.event_type', 'events.id', 'events.start_date as start', 'events.end_date as end', 'events.class_name as className', DB::raw("concat_ws(' - ',events.notes,estimates.estimate_no,users.name) AS title"))
            ->get();
        return $this->sendResponse($data, 'Calendar data retrieved successfully');
    }

    public function getDateWiseFollowUpList($date, $assign_user)
    {

//        $input = $request->all();
        $event = Estimate::select('e.*', 'estimates.estimate_no as estimate_no', 'estimates.customer_name', DB::raw("DATE_FORMAT(e.start_date, '%d-%m-%Y') as display_date"), DB::raw("DATE_FORMAT(e.start_date, '%d-%m-%Y %H:%i:%s') as start_date"), 'estimates.customer_name', DB::raw("RIGHT(estimates.customer_address, 10) as mobile_no"), "estimates.status", "u.name as user_name")
            ->join('events as e', function ($query) {
                $query->on('e.estimate_id', '=', 'estimates.id');
            })
            ->join('users as u', 'e.user_id', 'u.id')
            ->where(function ($query) use ($date, $assign_user) {
                $query->whereRaw("e.id=(SELECT MAX(t2.id) FROM `events` t2 WHERE t2.estimate_id = e.estimate_id )");
                if ($date) {
                    $dateArr = explode("_", $date);
                    if (count($dateArr) == 2) {
                        $query->whereBetween(DB::raw("DATE_FORMAT(e.start_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
                    } else
                        $query->where(DB::raw("DATE_FORMAT(e.start_date, '%Y-%m-%d')"), $date);
                }
                if ($this->logged_user->company_id == "") {
                    $query->where('e.company_id', '=', $this->company_id);
                } else {
                    $query->where(function ($query) use ($assign_user) {
                        $query->whereRaw('e.user_id IN(' . $assign_user . ')');
                        $query->orWhere('e.user_id', $this->logged_user->id);
                    });
                }
            })
            ->get();

        if (is_null($event)) {
            return response()->json(['success' => 'Follow up not found!'], 422);
        }
        $data = [];
        $dataArr = [];

        foreach ($event as $val) {
            $val['color'] = 'danger';
            if (strtotime($val->display_date) > strtotime(date('d-m-Y'))) {
                $val['color'] = 'success';
            }
            if (strtotime($val->display_date) == strtotime(date('d-m-Y'))) {
                $val['color'] = 'warning';
            }

            $data[$val->display_date][] = $val;
        }
        if (empty($data)) {
            return $this->sendResponse($data, 'Follow up not found!');
        }

        foreach ($data as $key => $val) {
            $dataArr[] = $data[$key];
        }

        return $this->sendResponse($dataArr, 'Calendar data retrieved successfully');
        /* return response()->json([
             "success" => true,
             "message" => "Follow up retrieved successfully.",
             "data" => $data
         ], 201);*/
    }


    public function createNextFolloup(Request $request)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'followup_date' => 'required',
//                'followup_time' => 'required',
            'notes' => 'required',
            'estimate_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);;
        }
//        if ($input['id'] == 0) {
//            $input['estimate_id'] = Crypt::decrypt($input['estimate_id']);
//        }
        $fetchData = SalesPersonPerformances::query()->select(DB::raw("DATE_FORMAT(performance_date, '%d-%m-%Y') as display_date"), "total_task", "completed_task", "adv_completed_task")->where([["performance_date", "=", Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d')], ["user_id", "=", $this->logged_user->id]])
            ->where(function ($query) use ($input) {
                if ($this->logged_user->company_id == "") {
                    $query->where('company_id', '=', $this->company_id);
                } else {
                    $query->whereRaw('user_id IN  (' . $input['assign_user'] . ')');
                    $query->orWhere('user_id', $this->logged_user->id);
                }
            })
            ->get();

        if ($fetchData->count() > 0) {
            if (!isset($input['next_follow_up']) || $input['next_follow_up'] != 'No') {
                $tts = $fetchData[0]->total_task;
                $adv_cts = $fetchData[0]->adv_completed_task;
                $cts = $fetchData[0]->completed_task;
                $next_cts = $tts + 1;
                $dps = (($cts - $adv_cts) * 100) / $next_cts;

                SalesPersonPerformances::where([["performance_date", "=", Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d')], ["user_id", "=", $this->logged_user->id]])->update(array("total_task" => $next_cts, "daily_performance" => $dps));
//                    SalesPersonPerformances::where([["performance_date", "=", Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d')], ["user_id", "=", $user->id]])->increment('total_task', 1);
            }
        } else {
            if (!isset($input['next_follow_up']) || $input['next_follow_up'] != 'No') {

                $insert = SalesPersonPerformances::create(["performance_date" => Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d'), "user_id" => $this->logged_user->id, "total_task" => 1, "completed_task" => 0, "daily_performance" => 0, "company_id" => $this->company_id]);
            }
        }

        if ($input['sp_flag'] == 'u') {
//                $tt - total task, $ct - completed task, $next_ct - total next completed task, $dp - daily performance
            $eventData = Event::query()->select(DB::raw("DATE_FORMAT(start_date, '%d-%m-%Y') as start_date"))->where("id", $input['event_id'])->latest("id")->first();
            $tmpStartDate = Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d');
            if ($eventData)
                $tmpStartDate = Carbon::createFromFormat('d-m-Y', $eventData->start_date)->format('Y-m-d');
            $fetchDatas = SalesPersonPerformances::query()->select("daily_performance", "completed_task", "total_task", DB::raw("DATE_FORMAT(performance_date, '%d-%m-%Y') as display_date"), "adv_completed_task")->where([["performance_date", "=", $tmpStartDate], ['user_id', '=', $this->logged_user->id]])
                /*  ->where(function ($query) use ($user) {
                      if ($user->company_id == "") {
                          $query->where('company_id', '=', $user->id);
                      } else {
                          $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                          $query->orWhere('user_id', $user->id);
                      }
                  })*/
                ->get();

            $tt = $fetchDatas[0]->total_task;
            $adv_ctss = $fetchDatas[0]->adv_completed_task;
            $ct = $fetchDatas[0]->completed_task;
            $next_ct = $ct + 1;
            if ($eventData && strtotime($tmpStartDate) == strtotime(date('Y-m-d'))) {
                $dp = (($next_ct - $adv_ctss) * 100) / $tt;
                SalesPersonPerformances::where([["performance_date", "=", $tmpStartDate], ["user_id", "=", $this->logged_user->id]])->update(array("completed_task" => $next_ct, "daily_performance" => $dp));
            }

            if ($eventData && strtotime($tmpStartDate) != strtotime(date('Y-m-d'))) {
                $adv_completed_task = $adv_ctss;
                if ($input['sp_flag'] === 'u') {
                    $adv_completed_task = $adv_ctss + 1;
                }
                SalesPersonPerformances::where([["performance_date", "=", $tmpStartDate], ["user_id", "=", $this->logged_user->id]])->update(array("completed_task" => $next_ct, "adv_completed_task" => $adv_completed_task));
            }
        }

        $data = array();
        $next_follow_up = 0;
        if (isset($input['next_follow_up']) && $input['next_follow_up'] == 'No') {
            $fetch = Event::query()->where("estimate_id", $input['estimate_id'])->latest("id")->first();
            $data['start_date'] = $fetch->start_date;
            $data['end_date'] = $fetch->end_date;
            $next_follow_up = 1;
        }
        if ($input['sp_flag'] == 'c') {
            $data['start_date'] = Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d') . ' ' . Carbon::createFromFormat('H:i', $input['followup_time'])->format('H:i:s');
            $data['end_date'] = Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d') . ' ' . Carbon::createFromFormat('H:i', $input['followup_time'])->format('H:i:s');
        }

        if (isset($input['next_follow_up']) && $input['next_follow_up'] == 'Yes' && $input['sp_flag'] != 'c') {
            $data['start_date'] = Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d') . ' ' . Carbon::createFromFormat('H:i', $input['followup_time'])->format('H:i:s');
            $data['end_date'] = Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d') . ' ' . Carbon::createFromFormat('H:i', $input['followup_time'])->format('H:i:s');
        }

        $data['next_follow_up'] = $next_follow_up;
        $data['notes'] = $input['notes'];
        $data['estimate_id'] = $input['estimate_id'];
        $data['event_type'] = 'estimate';
        $data['class_name'] = 'bg-info';

        $data['company_id'] = $this->company_id;
        $data['user_id'] = $this->logged_user->id;
        $data['log_id'] = $input['estimate_id'];
        $data['log_type'] = 'event-follow-up';

        $activityLogMsg = 'follow up created by ' . $this->logged_user->name;
        $follow_up = Event::create($data);
//        LogActivity::addToLog($activityLogMsg, $data, 1);
        if (!(isset($eventData) || $input['sp_flag'] == 'u'))
            $input['estimate_status'] = 'Sent';

        if (isset($input['estimate_status'])) {
            Estimate::where(array("id" => $input['estimate_id']))->update(array("status" => $input['estimate_status']));
        }
        return $this->sendResponse([], 'Follow up Saved!');
    }

    public function createTodo(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $date = date('Y-m-d', strtotime($request->start)) . ' ' . $request->event_time;
        $date = date('Y-m-d H:i:s', strtotime($date));
        $insertArr = ['notes' => $request->title,
            'start_date' => $date,
            'end_date' => $date,
            'company_id' => $company_id,
            'class_name' => $request->className,
            'event_type' => 'event',
            'user_id' => $user->id
        ];
        $event = Event::insert($insertArr);
        return $this->sendResponse([], 'Todo Saved!');
    }

    public function updateTodo(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $date = date('Y-m-d', strtotime($request->start)) . ' ' . $request->event_time;
        $date = date('Y-m-d H:i:s', strtotime($date));
        $where = array('id' => $request->id);
        $updateArr = ['notes' => $request->title, 'start_date' => $date, 'end_date' => $date, 'company_id' => $company_id, 'class_name' => $request->className,
            'user_id' => $user->id,];

        $event = Event::where($where)->update($updateArr);
        return $this->sendResponse([], 'Todo Updated!');
    }

    public function destroyTodo(Request $request)
    {
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $event = Event::where('id', $request->id)->delete();
        return $this->sendResponse([], 'Todo Deleted!');
    }
}
