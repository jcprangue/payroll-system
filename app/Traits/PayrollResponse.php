<?php

namespace App\Traits;

use App\Models\casual_employee;
use App\Models\casual_deduction;
use App\Models\casual_deduction_exempt;
use App\Models\casual_no_late;
use App\Models\casual_withoutpay;
use App\Models\payroll_signatory;

trait PayrollResponse
{
    public static function getGroupEmployee($id)
    {
        
        $employees = casual_employee::where('group_id', $id)->orderBy('last_name', 'ASC')->get();
        return $employees;
    }

    public static function rangeQuincena($quincena, $month)
    {
        $rangeInWord = '';
        if ($quincena == 1) {
            $rangeInWord = date('F', strtotime($month)) . ' 1-15, ' . date('Y', strtotime($month));
        } elseif ($quincena == 2) {
            $rangeInWord = date('F', strtotime($month)) . ' 16-' . date('t', strtotime($month)) . ', ' . date('Y', strtotime($month));
        } elseif ($quincena == 3) {
            $rangeInWord = date('F', strtotime($month)) . ' 1-' . date('t', strtotime($month)) . ', ' . date('Y', strtotime($month));
        }

        return $rangeInWord;
    }

    public static function casualWithoutPay($employee, $month, $payroll_type,$quin)
    {
        $monthWithoutPay = date('Y-m-01', strtotime($month));
        $daysInMonth = date('t', strtotime($monthWithoutPay));
        $undertime = casual_withoutpay::where('month', $monthWithoutPay)->where('casual_employee_id', $employee->employee_id)->where('quin', $quin)->first();
        if ($undertime) {
            if ($payroll_type == 2 || $payroll_type == 4) {

                //compute
                list($hours, $mins, $secs) = explode(":", $undertime->under);
                $ratePerDay = self::floatvalsafe($employee->salary);
                $ratePerHR = $ratePerDay / 8;
                $ratePerMin = $ratePerHR / 60;
               
                $hours = 0;
                $mins = 0;
                $secs = 0;


            } elseif ($payroll_type == 1 || $payroll_type == 3) {
                $nolate = casual_no_late::where('employee_id',$employee->employee_id)->first();
                if ($nolate){
                    $undertime->under = "00:00:00";
                }

                list($hours, $mins, $secs) = explode(":", $undertime->under);
                $ratePerDay = (self::floatvalsafe($employee->salary) / $daysInMonth);
                $ratePerHR = self::floatvalsafe($ratePerDay) / 8;
                $ratePerMin = self::floatvalsafe($ratePerHR) / 60;
            }

            // $amountInDays = $ratePerDay * $daysRender;
            $amountInHrs = $ratePerHR * $hours;
            $fDay = $hours / 8 ;
            $fhour = $hours % 8;
            $amountInMins = $ratePerMin * $mins;
            $formatDay = sprintf('%02d', $fDay).'d '. sprintf('%02d', $fhour) . "h " . $mins . "m";
            $withoutpay = $amountInHrs + $amountInMins;
        } else {
            $formatDay = 0;
            $withoutpay = 0;
        }

       
        return [
            "amount_deducted" => $withoutpay,
            "number_of_days" => $undertime,
            "format_days" => $formatDay
        ];
    }

  
    public static function employeeTax($employee_id, $salary, $withOutPay, $deduction,$basic_salary,$quin)
    {
        if ($quin == 2){
            return 0;
        }
        
        if ($basic_salary >= config('constants.tax.excess')){
            $excess = $basic_salary - config('constants.tax.excess');
            return $excess * config('constants.tax.percent');
        }
        

        $tax = casual_employee::where('employee_id', $employee_id)->first();
        $taxInPercent = $tax->tax / 100;
        $totalDeduction = collect($deduction)->sum('Amount');

        $takeHomePay = self::floatvalsafe($salary['amount_render']) - (self::floatvalsafe($withOutPay["amount_deducted"]) + self::floatvalsafe($totalDeduction));
        $taxAmount = self::floatvalsafe($takeHomePay) * $taxInPercent;
        return number_format(self::floatvalsafe($taxAmount), 2);
    }

    public static function getDeductionsListFromGroup($month)
    {
        // $deductionList = casual_deduction::where('date_start', '<=', $month)->get();
        $deductionList = casual_deduction::all();
        return $deductionList;
    }

    public static function employeeNetPay($deduction, $salaryPeriod, $withoutpay,$tax)
    {
        $totalDeduction = collect($deduction)->sum('Amount');
        return number_format(self::floatvalsafe($salaryPeriod['amount_render']) - (self::floatvalsafe($totalDeduction) + self::floatvalsafe($withoutpay['amount_deducted']) + self::floatvalsafe($tax)), 2);
    }

    public static function employeeNetWOdeduction($salaryPeriod, $withoutpay)
    {
        return number_format(self::floatvalsafe($salaryPeriod['amount_render']) - self::floatvalsafe($withoutpay['amount_deducted']),2);
    }

    public static function totalDeduction($deduction)
    {
        $totalDeduction = collect($deduction)->sum('Amount');
        return number_format(self::floatvalsafe($totalDeduction), 2);
    }

    public static function floatvalsafe($numStr)
    {
        return (float) preg_replace("/[^0-9.]/", "", $numStr);
    }

    public static function salaryPeriod($employee, $payroll)
    {
        $salary = $employee->salary;
        if ($payroll->quincena == 1 || $payroll->quincena == 2) {
            $salary = $employee->salary / 2;
        }
        return [
            'amount_render' => self::floatvalsafe($salary),
            'number_of_days' => null,
            'format_days' => null

        ];
    }

    public static function salaryPeriodPO($employee, $payroll,$quin)
    {
   
        $salary = $employee->salary;
        if ($quin == 1 || $quin == 2) {
            $salary = $employee->salary / 2;
        }
        return [
            'amount_render' => self::floatvalsafe($salary),
            'number_of_days' => null,
            'format_days' => null

        ];
    }


    public static function salaryPeriodCasual($employee, $payroll,$quin)
    {
        $monthWithoutPay = date('Y-m-01', strtotime($payroll->month));

        $amountInHrss = 0;
        $amountInHrs = 0;
        $amountInMins = 0;
        $ratePerDay = 0;
        $ratePerHR = 0;
        $ratePerMin = 0;

        $undertime = casual_withoutpay::where('month', $monthWithoutPay)->where('casual_employee_id', $employee->employee_id)->where('quin', $quin)->first();
        $hours = 0;
        $mins = 0;
        if ($undertime) {
            list($hours, $mins, $secs) = explode(":", $undertime->credit);
           
            // $amountInDays = $amountInDays * $days;
            $amountInHrss = $amountInHrs * $hours;
            $amountInMins = $amountInMins * $mins;

            $ratePerDay = self::floatvalsafe($employee->salary);
            $ratePerHR = self::floatvalsafe($ratePerDay) / 8;
            $ratePerMin = self::floatvalsafe($ratePerHR) / 60;
            
        }

        $amountInHrs = $ratePerHR * $hours;
        $fDay = $hours / 8 ;
        $fhour = $hours % 8;
        $amountInMins = $ratePerMin * $mins;
        $formatDay = sprintf('%02d', $fDay).'d '. sprintf('%02d', $fhour) . "h " . $mins . "m";
        // $daysRender = $amountInHrs + $amountInMins; //old computation
        $daysRender = $amountInHrs + $amountInMins;
        
        return [
            'amount_render' => $daysRender,
            'number_of_days' => $undertime,
            'format_days' => $formatDay
        ];
    }

    public static function salaryPeriodCasualPO($employee, $payroll,$month,$quin)
    {
        $monthWithoutPay = date('Y-m-01', strtotime($month));
        $amountInHrss = 0;
        $amountInHrs = 0;
        $amountInMins = 0;
        $ratePerDay = 0;
        $ratePerHR = 0;
        $ratePerMin = 0;

        $undertime = casual_withoutpay::where('month', $monthWithoutPay)->where('casual_employee_id', $employee->employee_id)->where('quin', $quin)->first();
        $hours = 0;
        $mins = 0;
        if ($undertime) {
            list($hours, $mins, $secs) = explode(":", $undertime->credit);
           
            // $amountInDays = $amountInDays * $days;
            $amountInHrss = $amountInHrs * $hours;
            $amountInMins = $amountInMins * $mins;

            $ratePerDay = self::floatvalsafe($employee->salary);
            $ratePerHR = self::floatvalsafe($ratePerDay) / 8;
            $ratePerMin = self::floatvalsafe($ratePerHR) / 60;
          
            // $amountInHrs = $ratePerHR * $hours;
            // $fDay = $hours / 8 ;
            // $fhour = $hours % 8;
            // $amountInMins = $ratePerMin * $mins;
            // $formatDay = sprintf('%02d', $fDay).'d '. sprintf('%02d', $fhour) . "h " . $mins . "m";
            // $withoutpay = $amountInHrs + $amountInMins;
        }

       
        $amountInHrs = $ratePerHR * $hours;
        $fDay = $hours / 8 ;
        $fhour = $hours % 8;
        $amountInMins = $ratePerMin * $mins;
        $formatDay = sprintf('%02d', $fDay).'d '. sprintf('%02d', $fhour) . "h " . $mins . "m";
        $daysRender = $amountInHrs + $amountInMins;

        

        return [
            'amount_render' => $daysRender,
            'number_of_days' => $undertime,
            'format_days' => $formatDay

        ];
    }



    public static function getSignatory($department_id)
    {
        $signed = payroll_signatory::where('department_id', $department_id)
            ->orWhereNull('department_id')
            ->where('status', 1)
            ->get();

        return $signed;
    }
}
