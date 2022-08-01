<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\{Event, Estimate, SalesPersonPerformances};
use Illuminate\Support\Facades\{Crypt, DB, Validator};
use Auth, Response, Session, Cache;
use LogActivity;

class EventController extends Controller
{
    public function calendarEvent()
    {
        if (request()->ajax()) {

            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            $data = DB::table('events')
                ->leftJoin('estimates', 'events.estimate_id', 'estimates.id')
                ->leftJoin('users', 'events.user_id', 'users.id')
//                ->where('events.company_id', $company_id)
                ->where(function ($query) use ($user) {
                    if ($user->company_id == "") {
                        $query->where('events.company_id', '=', $user->id);
                    } else {
                        $query->whereRaw('events.user_id IN  (' . Session::get("get_data_by_id") . ')');
                        $query->orWhere('events.user_id', $user->id);
                    }
                })
                ->whereDate('events.start_date', '>=', $start)->whereDate('end_date', '<=', $end)
                ->select('events.event_type', 'events.id', 'events.start_date as start', 'events.end_date as end', 'events.class_name as className', DB::raw("concat_ws(' - ',events.notes,estimates.estimate_no,users.name) AS title"))
//                ->addSelect(DB::raw("'bg-info' as className"))
                ->get();
            return Response::json($data);
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $val = $input['val'];
            if ($input['flg'] == 'enc') {
                $val = Crypt::decrypt($input['val']);
            }
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            $event = DB::table('events')
                ->leftjoin('estimates', 'events.estimate_id', 'estimates.id')
                ->leftJoin('users', 'events.user_id', 'users.id')
//                ->where(function ($query) use ($user) {
//                    if ($user->company_id == "") {
//                        $query->where('events.company_id', '=', $user->id);
//                    } else {
//                        $query->whereRaw('events.user_id IN  (' . Session::get("get_data_by_id") . ')');
//                        $query->orWhere('events.user_id', $user->id);
//                    }
//                })
                ->where([
                    ['events.estimate_id', '=', $val],
                    ['events.event_type', '=', 'estimate'],
                ])
                ->select('events.*', 'estimates.estimate_no as estimate_no', DB::raw("DATE_FORMAT(events.start_date, '%d-%m-%Y') as display_date"), DB::raw("DATE_FORMAT(events.start_date, '%d-%m-%Y %H:%i:%s') as start_date"), 'estimates.customer_name', DB::raw("RIGHT(estimates.customer_address, 10) as mobile_no"), 'estimates.status','users.name as user_name', DB::raw("DATE_FORMAT(events.created_at, '%d-%m-%Y  %H:%i:%s') as created_at")) // 'users.name',
                ->orderBy('events.id', 'DESC')
                ->orderBy('events.start_date', 'DESC')
                ->get();
            if (is_null($event)) {
                return response()->json(['success' => 'Follow up not found!'], 422);
            }

            return response()->json([
                "success" => true,
                "message" => "Follow up retrieved successfully.",
                "data" => $event
            ], 201);
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
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
            if ($input['id'] == 0) {
                $input['estimate_id'] = Crypt::decrypt($input['estimate_id']);
            }
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;

            $fetchData = SalesPersonPerformances::query()->select(DB::raw("DATE_FORMAT(performance_date, '%d-%m-%Y') as display_date"),"total_task","completed_task","adv_completed_task")->where([["performance_date", "=", Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d')], ["user_id", "=", $user->id]])
                ->where(function ($query) use ($user) {
                    if ($user->company_id == "") {
                        $query->where('company_id', '=', $user->id);
                    } else {
                        $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                        $query->orWhere('user_id', $user->id);
                    }
                })
                ->get();

            if ($fetchData->count() > 0) {
                if (!isset($input['next_follow_up']) || $input['next_follow_up'] != 'No') {
                    $tts = $fetchData[0]->total_task;
                    $adv_cts = $fetchData[0]->adv_completed_task;
                    $cts = $fetchData[0]->completed_task;
                    $next_cts = $tts + 1;
                    $dps = (($cts-$adv_cts) * 100) / $next_cts;

                    SalesPersonPerformances::where([["performance_date", "=", Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d')], ["user_id", "=", $user->id]])->update(array("total_task" => $next_cts, "daily_performance" => $dps));
//                    SalesPersonPerformances::where([["performance_date", "=", Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d')], ["user_id", "=", $user->id]])->increment('total_task', 1);
                }
            } else {
                if (!isset($input['next_follow_up']) || $input['next_follow_up'] != 'No') {

                    $insert = SalesPersonPerformances::create(["performance_date" => Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d'), "user_id" => $user->id, "total_task" => 1, "completed_task" => 0, "daily_performance" => 0, "company_id" => $company_id]);
                }
            }

            if ($input['sp_flag'] == 'u') {
//                $tt - total task, $ct - completed task, $next_ct - total next completed task, $dp - daily performance
                $eventData = Event::query()->select(DB::raw("DATE_FORMAT(start_date, '%d-%m-%Y') as start_date"))->where("id", $input['event_id'])->latest("id")->first();
                $tmpStartDate= Carbon::createFromFormat('d/m/Y', $input['followup_date'])->format('Y-m-d');
                if($eventData)
                    $tmpStartDate = Carbon::createFromFormat('d-m-Y', $eventData->start_date)->format('Y-m-d');
                $fetchDatas = SalesPersonPerformances::query()->select("daily_performance", "completed_task", "total_task", DB::raw("DATE_FORMAT(performance_date, '%d-%m-%Y') as display_date"),"adv_completed_task")->where([["performance_date", "=", $tmpStartDate],['user_id', '=', $user->id]])
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
                    $dp = (($next_ct-$adv_ctss) * 100) / $tt;
                    SalesPersonPerformances::where([["performance_date", "=", $tmpStartDate], ["user_id", "=", $user->id]])->update(array("completed_task" => $next_ct, "daily_performance" => $dp));
                }

                if ($eventData && strtotime($tmpStartDate) != strtotime(date('Y-m-d'))){
                    $adv_completed_task = $adv_ctss;
                    if ($input['sp_flag'] === 'u')
                    {
                        $adv_completed_task = $adv_ctss + 1;
                    }
                    SalesPersonPerformances::where([["performance_date", "=", $tmpStartDate], ["user_id", "=", $user->id]])->update(array("completed_task" => $next_ct,"adv_completed_task"=>$adv_completed_task));
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

            $data['company_id'] = $company_id;
            $data['user_id'] = $user->id;
            $data['log_id'] = $input['estimate_id'];
            $data['log_type'] = 'event-follow-up';

            $activityLogMsg = 'follow up created by ' . $user->name;
            $follow_up = Event::create($data);
            LogActivity::addToLog($activityLogMsg, $data, 1);
            if(!(isset($eventData) || $input['sp_flag'] == 'u'))
                $input['estimate_status']='Sent';

            if (isset($input['estimate_status'])) {
                Estimate::where(array("id" => $input['estimate_id']))->update(array("status" => $input['estimate_status']));
            }
            return response()->json(['success' => 'follow up Saved!', 'url' => url('quotes/edit/' . Crypt::encrypt($input['estimate_id'])) . '/1'], 201);
        }
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);;
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
        $activityLogMsg = 'event created by ' . $user->name;
        LogActivity::addToLog($activityLogMsg, $insertArr);
        return response()->json(['success' => 'Event Saved!'], 201);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);;
        }
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;

        $date = date('Y-m-d', strtotime($request->start)) . ' ' . $request->event_time;
        $date = date('Y-m-d H:i:s', strtotime($date));
        $where = array('id' => $request->id);
        $updateArr = ['notes' => $request->title, 'start_date' => $date, 'end_date' => $date, 'company_id' => $company_id, 'class_name' => $request->className,
            'user_id' => $user->id,];

        $event = Event::where($where)->update($updateArr);
        $activityLogMsg = 'event updated by ' . $user->name;
        LogActivity::addToLog($activityLogMsg, $input);
        return response()->json(['success' => 'Event Updated!'], 201);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $event = Event::where('id', $request->id)->delete();
        LogActivity::addToLog('Event deleted by ' . $user->name, $request->id);
        return response()->json(['success' => 'Event Deleted!'], 201);
    }

    public function followUpHistoryIndex()
    {
        return view('follow-up-history');
    }

    public function getDateWiseFollowUpList(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            \DB::enableQueryLog();
            $event = Estimate::select('e.*', 'estimates.estimate_no as estimate_no', 'estimates.customer_name', DB::raw("DATE_FORMAT(e.start_date, '%d-%m-%Y') as display_date"), DB::raw("DATE_FORMAT(e.start_date, '%d-%m-%Y %H:%i:%s') as start_date"), 'estimates.customer_name', DB::raw("RIGHT(estimates.customer_address, 10) as mobile_no"), "estimates.status","u.name as user_name", DB::raw("DATE_FORMAT(e.created_at, '%d-%m-%Y %H:%i:%s') as created_at"))
                ->join('events as e', function ($query) {
                    $query->on('e.estimate_id', '=', 'estimates.id');
                })
                ->join('users as u','e.user_id','u.id')
                ->where(function ($query) use ($user, $input) {
                    $query->whereRaw("e.id=(SELECT MAX(t2.id) FROM `events` t2 WHERE t2.estimate_id = e.estimate_id )");
                    if ($input['date']) {
                        $dateArr = explode("_", $input['date']);
                        if (count($dateArr) == 2) {
                            $query->whereBetween(DB::raw("DATE_FORMAT(e.start_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
                        } else
                            $query->where(DB::raw("DATE_FORMAT(e.start_date, '%Y-%m-%d')"), $input['date']);
                    }
                    if ($user->company_id == "") {
                        $query->where('e.company_id', '=', $user->id);
                    } else {
                        $query->where(function ($query) use ($user, $input) {
                            $query->whereRaw('e.user_id IN(' . Session::get("get_data_by_id") . ')');
                            $query->orWhere('e.user_id', $user->id);
                        });
                    }
                })
//                ->orderBy('e.id', 'DESC')
                ->get();
//            dd(\DB::getQueryLog());
            if (is_null($event)) {
                return response()->json(['success' => 'Follow up not found!'], 422);
            }
            $data = [];
            foreach ($event as $val) {

                $val['created_datetime'] = date("d-m-Y H:i:s",strtotime($val->created_at));
                $val['color'] = 'danger';
                if (strtotime($val->display_date) > strtotime(date('d-m-Y'))) {
                    $val['color'] = 'success';
                }
                if (strtotime($val->display_date) == strtotime(date('d-m-Y'))) {
                    $val['color'] = 'warning';
                }

//                $data[$val->display_date]['class_name'] = $val->class_name;
//                $data[$val->display_date]['company_id'] = $val->company_id;
//                $data[$val->display_date]['created_at'] = $val->created_at;
//                $data[$val->display_date]['customer_name'] = $val->customer_name;
//                $data[$val->display_date]['display_date'] = $val->display_date;
//                $data[$val->display_date]['end_date'] = $val->end_date;
//                $data[$val->display_date]['estimate_id'] = $val->estimate_id;
//                $data[$val->display_date]['estimate_no'] = $val->estimate_no;
//                $data[$val->display_date]['event_type'] = $val->event_type;
//                $data[$val->display_date]['id'] = $val->id;
//                $data[$val->display_date]['mobile_no'] = $val->mobile_no;
//                $data[$val->display_date]['next_follow_up'] = $val->next_follow_up;
//                $data[$val->display_date]['notes'] = $val->notes;
//                $data[$val->display_date]['start_date'] = $val->start_date;
//                $data[$val->display_date]['status'] = $val->status;
//                $data[$val->display_date]['updated_at'] = $val->updated_at;
//                $data[$val->display_date]['user_id'] = $val->user_id;
//                $data[$val->display_date]['user_name'] = $val->user_name;
                $data[$val->display_date][] = $val;
            }
            if (empty($data)) {
                return response()->json(['success' => 'Follow up not found!'], 422);
            }

            return response()->json([
                "success" => true,
                "message" => "Follow up retrieved successfully.",
                "data" => $data
            ], 201);

        }
    }

}
