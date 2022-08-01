<?php


namespace App\Helpers;

use Request;
use App\Models\LogActivity as LogActivityModel;
use Illuminate\Support\Facades\DB;

class LogActivity
{


    public static function addToLog($subject, $properties, $flag = 0)
    {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
        $log['company_id'] = auth()->user()->company_id ? auth()->user()->company_id : auth()->user()->id;
        $log['properties'] = json_encode($properties);
        $log['properties'] = json_encode($properties);
        if ($flag == 1) {
            $log['log_id'] = $properties['log_id'];
            $log['log_type'] = $properties['log_type'];
        }

        LogActivityModel::create($log);
    }


    public static function logActivityLists($log_id, $log_type)
    {
//        DB::enableQueryLog();
        $records = LogActivityModel::query()
            ->where(function ($query) use ($log_id, $log_type) {
                $query->where(function ($query) use ($log_id) {
                    $query->where('log_id', $log_id);
                });
                $query->where(function ($query) use ($log_type) {
                    $query->whereIn('log_type', $log_type);
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate(3);

        $log_activity_log = '';




        foreach ($records as $record) {
            $proArr = json_decode($record->properties,true);
            $notes='';
            if($record->log_type=='event-follow-up')
                $notes=$proArr['notes'];
            $log_activity_log .= ' <div class="timeline-item"> <i class="mdi mdi-upload bg-info-lighten text-info timeline-icon"></i><div class="timeline-item-info"><a href="#" class="text-info fw-bold mb-1 d-block">' . $record->subject . '</a><small>' . $notes . '</small><p class="mb-0 pb-2"><small class="text-muted">'.$record->created_at.'</small></p></div></div>';
        }
        return $log_activity_log;

//            ->get();
//        dd(DB::getQueryLog());
    }

    public static function convertToIndianCurrency($number)
    {

        $no = round($number);
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
            } else {
                $str [] = null;
            }
        }

        $Rupees = implode(' ', array_reverse($str));
        $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal % 10]) . " " . ($words[$decimal % 10]) : '';
        return ($Rupees ? 'Indian Rupees ' . $Rupees : '') . $paise . " Only";
    }


}
