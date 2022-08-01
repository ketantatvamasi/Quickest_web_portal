<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Estimate;
use Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
//        $widgets = Cache::rememberForever('dashboard_widgets', function () use ($user) {
        $widgets = Estimate::query()->groupBy('status') //->where('status','!=','Draft')
//            ->where('company_id',$company_id)
                ->where(function ($query) use ($user) {
                    if ($user->company_id == "") {
                        $query->where('company_id', '=', $user->id);
                    } else {
                        $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                        $query->orWhere('user_id', $user->id);
                    }
                })
                ->select('status', DB::raw('COUNT(status) as widget_total'))->get()->toArray();
//        });
//        dd($widgets);
//        Cache::flush();
        $a = [
            ['status'=>'Sent','widget_total' =>0],
            ['status'=>'Inprogress','widget_total' =>0],
            ['status'=>'Accept','widget_total' =>0],
            ['status'=>'Decline','widget_total' =>0],
            ['status'=>'Draft','widget_total' =>0]
        ];
        $x =  array_column($widgets, 'status');
        $total = 0+array_sum(array_column($widgets, 'widget_total'));
        foreach ($a as $value)
        {
            if (!in_array($value['status'], $x) )
            {
                $widgets[] = $value;
            }
        }
        usort($widgets, function ($a, $b) {
            return $a['status'] <=> $b['status'];
        });

        $widgets[] = array("status" => "Total","widget_total" => $total);
        $curr_date_month = date('m');
        $bar_chart_filter = $this->calculateFiscalYearForDate($curr_date_month);
        return view('dashboard', compact('widgets','bar_chart_filter'))->with(array("total"=>$total));
    }

    public function donutChart()
    {
        $user = auth()->user();
        $widgets = Estimate::query()->groupBy('status')
            ->where(function ($query) use ($user) {
                if ($user->company_id == "") {
                    $query->where('company_id', '=', $user->id);
                } else {
                    $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('user_id', $user->id);
                }
            })
            ->select('status', DB::raw('COUNT(status) as widget_total'), DB::raw('count(*)'))->get()->toArray();

        return response()->json([
            "success" => true,
            "message" => "Follow up retrieved successfully.",
            "labels" => array_column($widgets, 'status'),
            "data" => array_column($widgets, 'widget_total'),
        ], 201);

    }

    public function calculateFiscalYearForDate($month)
    {
        if($month > 4)
        {
            $y = date('Y');
            $pt = date('Y', strtotime('+1 year'));
        }
        else
        {
            $y = date('Y', strtotime('-1 year'));
            $pt = date('Y');

        }
        $fy = $y."-04-01"."_".$pt."-03-31";
        return ["current_fiscal_year" => $fy, "previous_fiscal_year"=>($y-1)."-04-01"."_".($pt-1)."-03-31","last_twelve_month"=>date('Y-m-d', strtotime(' - 12 months'))."_".date('Y-m-d'),"fd" =>$y."-04-01","ed"=>$pt."-03-31"];
    }

    public function barChart(Request $request){
        $monthArr = [];
        $input = $request->all();
        $user = auth()->user();
        $dateArr = explode("_", $input['date']);

        $year = date('Y',strtotime($dateArr[0]));
        $month = date('m',strtotime($dateArr[0]));
        for ($i = 0; $i < 12; $i++) {
            array_push($monthArr, date("M` Y", strtotime('+'.$i.' month',date(strtotime('01-' . $month . '-'. $year)))));
        }
//        DB::enableQueryLog();
//        $records = Estimate::select(
//            'status',
//            DB::raw("(SUM(net_amount)) as total_count"),
//            DB::raw("MONTHNAME(estimate_date) as month_name"),
//            DB::raw("DATE_FORMAT(estimate_date, '%b %Y') as estimate_date")
//        )
////            ->whereYear('estimate_date', date('Y'))
//            ->where(function ($query) use ($input) {
//                $dateArr = explode("_", $input['date']);
//                $query->whereBetween(DB::raw("DATE_FORMAT(estimate_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
//            })
//            ->where(function ($query) use ($user) {
//                if ($user->company_id == "") {
//                    $query->where('company_id', '=', $user->id);
//                } else {
//                    $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
//                    $query->orWhere('user_id', $user->id);
//                }
//            })
//            ->groupBy('month_name')
//            ->get();
//dd(DB::getQueryLog());
//dd($items);

        $records = Estimate::query()
            ->where(function ($query) use ($user, $input) {
                $dateArr = explode("_", $input['date']);
                $query->whereBetween(DB::raw("DATE_FORMAT(estimate_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
            })
//            ->whereIn('status', ['Sent', 'Accept'])
            ->where(function ($query) use ($user) {
                if ($user->company_id == "") {
                    $query->where('company_id', '=', $user->id);
                } else {
                    $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('user_id', $user->id);
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
//                if($records) {
                    foreach ($records as $record) {
                        if ($record->estimate_date == $v) {
                            $dataArr[$v]['sent'][] = 0;
//                        if ($record->status != 'Accept')
                            $dataArr[$v]['sent'][] = $record->total_count;

                            $dataArr[$v]['close'][] = 0;
                            if ($record->status == 'Accept')
                                $dataArr[$v]['close'][] = $record->total_count;

                        } else {
                            $dataArr[$v]['sent'][] = 0;
                            $dataArr[$v]['close'][] = 0;
                        }
                    }
//                }
//                if(!$records){
                    $dataArr[$v]['sent'][] = 0;
                    $dataArr[$v]['close'][] = 0;
//                }
            }
            foreach ($dataArr as $key => $value) {
                $sum_sent = array_sum($value['sent']);
                $sum_close = array_sum($value['close']);
                array_push($labels, $key);
                array_push($sent, $sum_sent);
                array_push($close, $sum_close);
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Follow up retrieved successfully.",
            "labels" => $labels,
            "sent" => $sent,
            "close" => $close,
        ], 201);
    }

    public function salesPerformanceChart(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();
//        DB::enableQueryLog();
        $sales_performance = DB::table('sales_person_performances')

            ->where(function ($query) use ($user, $input) {
                $dateArr = explode("_", $input['date']);
                $query->whereBetween(DB::raw("DATE_FORMAT(performance_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
            })
            ->where(function ($query) use ($user) {
                if ($user->company_id == "") {
//                    $query->where('company_id', '=', $user->id);
                    $query->where('user_id', '=', $user->id);
                } else {
//                    $query->whereRaw('user_id IN(' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('user_id', $user->id);
                }
            })
            ->select(DB::raw("SUM(total_task) as total_task"),DB::raw("SUM(completed_task) as completed_task"),DB::raw("COUNT(id) as total_record"),DB::raw("SUM(daily_performance) as daily_performance"))
            ->get()->toArray();

        $count_all = DB::table('sales_person_performances')
            ->where('daily_performance', '>', 0)
            ->where(function ($query) use ($input) {
                $dateArr = explode("_", $input['date']);
                $query->whereBetween(DB::raw("DATE_FORMAT(performance_date, '%Y-%m-%d')"), [$dateArr[0], $dateArr[1]]);
            })
            ->where(function ($query) use ($user) {
                if ($user->company_id == "") {
//                    $query->where('company_id', '=', $user->id);
                    $query->where('user_id', '=', $user->id);
                } else {
//                    $query->whereRaw('user_id IN(' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('user_id', $user->id);
                }
            })
            ->select('id')->count();
//        dd(DB::getQueryLog());
        $total_task = array_column($sales_performance, 'total_task');
        $total_record = array_column($sales_performance, 'total_record');
        $completed_task = array_column($sales_performance, 'completed_task');
        $total_daily_performance = array_column($sales_performance, 'daily_performance');

        $series[] = ($total_task[0] > 0)?(float)number_format(($completed_task[0] * 100)/$total_task[0],2):0;
        $series[] = ($count_all > 0)?(float)number_format($total_daily_performance[0]/$count_all,2):0;
        return response()->json([
            "success" => true,
            "message" => "Follow up retrieved successfully.",
            "data"=>$sales_performance,
            "series" => $series,
            "labels" => ["Completion Ratio","Performance"],
            "total_task" => (int)$total_task[0],
            "completed_task" => (int)$completed_task[0],
            "total_record" => (int)$total_record[0],
        ], 201);

    }
}
