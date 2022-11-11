<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\casual_charging;
use App\Models\casual_deduction;
use App\Models\casual_deduction_exempt;
use Illuminate\Http\Request;
use App\Models\casual_monitoring;
use App\Models\casual_payroll_group;
use App\Models\department;
use App\Models\payroll_casual_records;
use App\Models\PayrollControlMonitoring;
use PDF, App;
use App\Traits\PayrollResponse;
use Illuminate\Support\Str;
class PayrollController extends Controller
{
    use PayrollResponse;
    private $amount = 0;
    public function generatePayroll($id)
    {
      

        $payrollDetails = casual_monitoring::findOrFail($id);
     
        $employees = $this->getGroupEmployee($payrollDetails->casual_payroll_group);

        $user = Auth()->user();
        if ($user->hasRoles('OPA')) {
            //save
            
            $data = $this->payrollPRocess($payrollDetails, $employees);
            $recordedPayroll = payroll_casual_records::updateOrCreate([
                'casual_payroll_group_id' => $payrollDetails->casual_payroll_group,
                'month' => $payrollDetails->month
            ], [
                'casual_payroll_group_id' => $payrollDetails->casual_payroll_group,
                'month' => $payrollDetails->month,
                'data' => json_encode($data)
            ]);

            $updateMonitoring = casual_monitoring::updateOrCreate([
                "id" => $id
            ], [
                "amount" => $data["footerInfo"]["Total Netpay"]
            ]);
        } else {
            //has it's record
            $recorded = payroll_casual_records::where('month', $payrollDetails->month)
                ->where('casual_payroll_group_id', $payrollDetails->casual_payroll_group)
                ->first();
                $data = $this->payrollPRocess($payrollDetails, $employees);
                
                // if ($recorded) {
                //     $data = json_decode($recorded->data, true);
                // } else {
                //     $data = $this->payrollPRocess($payrollDetails, $employees);
                // }
        }

        $count = 0;
        foreach ($data["data"] as $key => $charging) {
            $count = $count + count($charging) + 1;
        }
        $data['totalcount'] = $count;
        $data['style'] = $payrollDetails->payrollGroups->is_style;
        // return view('print',$data);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('print', $data)->setPaper(array(0, 0, 612, 936), 'landscape');
        return $pdf->stream('temp.pdf');
    }
    public function generatePayrollPrint($id)
    {
        $groupDetails = casual_payroll_group::findOrFail($id);
        $employees = $this->getGroupEmployee($id);
        $user = Auth()->user();
        
        $data = $this->payrollPRocessPO(request()->quincena,$groupDetails, $employees);
        
        $count = 0;
        foreach ($data["data"] as $key => $charging) {
            $count = $count + count($charging) + 1;
        }

        $data['totalcount'] = $count;
        $data['style'] = $groupDetails->is_style;

        // return view('print',$data);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('print', $data)->setPaper('LEGAL', 'landscape');
        return $pdf->stream('temp.pdf');
    }

    public function generateOBR($id)
    {
        $payrollDetails = casual_monitoring::findOrFail($id);
        $employees = $this->getGroupEmployee($payrollDetails->casual_payroll_group);
        $employeeIds = collect($employees)->pluck('charging_id')->values()->toArray();
        $chargingIds = $this->getUniqueChargingParent($employeeIds);

        if ($payrollDetails) {
            $recorded = payroll_casual_records::where('casual_payroll_group_id', $payrollDetails->casual_payroll_group)
                ->where('month', $payrollDetails->month)
                ->first();

            if ($recorded) {
                $jsonData = json_decode($recorded->data, true);
                // return "Payroll has not been recorded, Please return to OPA for review";
            }else{
            }
            $jsonData = $this->payrollPRocess($payrollDetails, $employees,$obr = true);
            $total = 0;
            foreach ($jsonData['data'] as $charging_name => $payrollValues) {
                // $sumCharging = collect($payrollValues)->sum(function ($query) {
                //     return $query['Salary Period']['amount_render'] - $query['WithoutPay']['amount_deducted'];
                // });

                $sumCharging = collect($payrollValues)->sum(function ($query) {
                    return $this->floatvalsafe($query["NetpayWOdeduction"]);
                });

                $total += self::floatvalsafe(number_format($sumCharging, 2));

            }
            $charging = casual_charging::with('children')
                ->whereNull('parent_id')
                ->whereIn('id',$chargingIds)
                ->where('department_id', $payrollDetails->department_id)
                ->get()
                ->toArray();

            


            $newValue = [];
            foreach ($charging as $value) {
                foreach ($jsonData['data'] as $charging_name => $payrollValues) {
                    // $sumCharging = collect($payrollValues)->sum(function ($query) {
                    //     return $query['Salary Period']['amount_render'] - $query['WithoutPay']['amount_deducted'];
                    // });

                    $sumCharging = collect($payrollValues)->sum(function ($query) {
                        return $this->floatvalsafe($query["NetpayWOdeduction"]);
                    });

                    $value['children'] = $this->recursive($value['children'], $charging_name, $sumCharging,$sumCharging);
                    $newValue[$value['id'] . "@@" .$value['charging_name']] = $value;
                    
                }
            }

            // $finalData = [];
            // foreach ($newValue as $key => $project){
            //     $sum = $project['amount'];
            //     if (count($project["children"])>0){
            //         $project["children"] = $this->sumPartial($project["children"],$sum);
            //     }
            //     $finalData[$key] = $project;
            // }

          

            if (!$payrollDetails->payrollGroups->is_recompute){
                $finalData = [];
                foreach ($newValue as $key => $project){
                    $sum = $project['amount'];
                    if (count($project["children"])>0){
                        $project["children"] = $this->sumPartial($project["children"],$sum);
                    }
                    $finalData[$key] = $project;
                }

                $data['data'] = $finalData;
            }


            $chargingDept = ($payrollDetails->payrollGroups->department_charging_id != null) ? $payrollDetails->payrollGroups->departmentCharging : $payrollDetails->department;
            
            $middle = !empty($employees[0]->middle_name) ? $employees[0]->middle_name[0] : null;
            $data['header'] = [
                'Payee' => count($employees) > 1 ?
                    $employees[0]->last_name . ', ' . $employees[0]->first_name . " " . $middle . ". et al." :
                    $employees[0]->last_name . ', ' . $employees[0]->first_name . " " . $middle,
                'Office' => $payrollDetails->department->description,
                "Address" => 'Calapan City',
                "Responsibility_Center" => $chargingDept->department_initial,
                "Service" => $chargingDept->payroll_title,
                "Obr_code" => $chargingDept->payroll_code,
                "Net Pay" => number_format($total, 2)
            ];

            $data['footer'] = $this->setSignatoryDetails($payrollDetails->payrollGroups,false);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('printOBR', $data)->setPaper('A4', 'portrait');
            return $pdf->stream('temp.pdf');
        }
    }


    public function getUniqueChargingParent($employeeIds){
        $listOfChargingId = [];
        foreach ($employeeIds as $key => $id) {
            if ($id != null){
                
                $chargingItem = casual_charging::find($id);
                $parentChargingId = $chargingItem->getParentsIdAttribute();
                $listOfChargingId = array_merge($listOfChargingId,$parentChargingId);
            }
            
            
        }
        return array_unique($listOfChargingId);
    }

    public function generateObrPrint($id)
    {
        $quin = request()->input('quincena');
        $month = request()->input('month') . '-01';
        $groupDetails = casual_payroll_group::findOrFail($id);
        $dept = $groupDetails->department_charging_id == null ? $groupDetails->department : $groupDetails->department_charging_id;
        $employees = $this->getGroupEmployee($id);
        $employeeIds = collect($employees)->pluck('charging_id')->values()->toArray();
        $chargingIds = $this->getUniqueChargingParent($employeeIds);
        if ($groupDetails) {
            $recorded = payroll_casual_records::where('casual_payroll_group_id', $id)
                ->where('month', $month)
                ->first();

            if ($recorded) {
                $jsonData = json_decode($recorded->data, true);
                // return "Payroll has not been recorded, Please return to OPA for review";
            }else{
                $jsonData = $this->payrollPRocessPO(request()->quincena,$groupDetails, $employees, $obr = true);
            }

            $jsonData = $this->payrollPRocessPO(request()->quincena,$groupDetails, $employees, $obr = true);

            $total = 0;
            foreach ($jsonData['data'] as $charging_name => $payrollValues) {
                // $sumCharging = collect($payrollValues)->sum(function ($query) {
                //     return $query['Salary Period']['amount_render'] - $query['WithoutPay']['amount_deducted'];
                // });

                $sumCharging = collect($payrollValues)->sum(function ($query) {
                    return $this->floatvalsafe($query["NetpayWOdeduction"]);
                });
                
                $total += self::floatvalsafe(number_format($sumCharging, 2));
            }


            $chargingDept = ($groupDetails->department_charging_id != null) ? $groupDetails->departmentCharging : $groupDetails->department;
            $charging = casual_charging::with('children')
                ->whereNull('parent_id')
                ->whereIn('id',$chargingIds)
                ->where('department_id', $groupDetails->department)
                ->get()
                ->toArray();
            $newValue = [];

            foreach ($charging as $value) {
                foreach ($jsonData['data'] as $charging_name => $payrollValues) {

                    // $sumCharging = collect($payrollValues)->sum(function ($query) {
                    //     return $query['Salary Period']['amount_render'] - $query['WithoutPay']['amount_deducted'];
                    // });

                    $sumCharging = collect($payrollValues)->sum(function ($query) {
                        return $this->floatvalsafe($query["NetpayWOdeduction"]);
                    });

                    $value['children'] = $this->recursive($value['children'], $charging_name, $sumCharging,$sumCharging);
                    $newValue[$value['id'] . "@@" .$value['charging_name']] = $value;

                }
            }
            $data['data'] = $newValue;

            if (!$groupDetails->is_recompute){
                $finalData = [];
                foreach ($newValue as $key => $project){
                    $sum = $project['amount'];
                    if (count($project["children"])>0){
                        $project["children"] = $this->sumPartial($project["children"],$sum);
                    }
                    $finalData[$key] = $project;
                }

                $data['data'] = $finalData;
            }
            

            $data['header'] = [
                'Payee' => count($employees) > 1 ?
                    $employees[0]->last_name . ', ' . $employees[0]->first_name . ". et al." :
                    $employees[0]->last_name . ', ' . $employees[0]->first_name . " " ,
                'Office' => $groupDetails->department_->description,
                "Address" => 'Calapan City',
                "Responsibility_Center" => $chargingDept->department_initial,
                "Service" => $chargingDept->payroll_title,
                "Obr_code" => $chargingDept->payroll_code,
                "Net Pay" => number_format($total, 2)
            ];

            
            $data['footer'] = $this->setSignatoryDetails($groupDetails,false);
            // return view('printOBR', $data);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('printOBR', $data)->setPaper('A4', 'portrait');
            return $pdf->stream('temp.pdf');
        }
    }


    public function sumPartial($data,$sum){
        foreach ($data as $key => $value){
            $sum = $sum + $data[$key]['amount'];

            if (count($data[$key]["children"]) > 0){
                $data[$key]["children"] = $this->sumPartial($data[$key]["children"],$sum);
            }
    
            if ($data[$key]["accounts"] && $data[$key]["amount"] == 0){
                $data[$key]["amount"] = $sum ;
            }
           
        }
        
        return $data;

    }

    public function recursive($data, $query_string, $amount,$sumAmount = 0)
    {
        $returnValue = [];

        foreach ($data as $value) {
            list($id,$charging)=explode("@@",$query_string);


            if ($value['id'] == $id && $value['charging_name'] == $charging) {
                $value['amount'] = $amount;
                $this->amount = $this->amount + $amount;
            }

            if (!empty($value['children'])) {
                $value['children'] = $this->recursive($value['children'], $query_string, $amount,$sumAmount);
            }
            $returnValue[$value['id'] . '-' . $value['charging_name']]  = $value;
        }
        return $returnValue;
    }

    public function payrollPRocess($payrollDetails, $employees, $obr = false)
    {

        if ($payrollDetails->payrollGroups->payroll_type == 1 || $payrollDetails->payrollGroups->payroll_type == 3) {
            $group_employee = collect($employees)->map(function ($query) use ($payrollDetails,$obr) {
                $salaryPeriod = $this->salaryPeriod($query,$payrollDetails,$payrollDetails->quincena);
                $withOutPay = $this->casualWithoutPay($query,$payrollDetails->month, $payrollDetails->payrollGroups->payroll_type,$payrollDetails->quincena);
                $deduction = $this->employeeDeductions($query->employee_id,$payrollDetails->month,$payrollDetails->quincena);
                $tax = $this->employeeTax($query->employee_id, $salaryPeriod,$withOutPay,$deduction,$query->salary,$payrollDetails->quincena);
                $netWOdeduction = $this->employeeNetWOdeduction($salaryPeriod,$withOutPay);
                $netPay = $this->employeeNetPay($deduction,$salaryPeriod,$withOutPay,$tax);

                // $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                // $charging = null;
                // if ($query->charging != null){
                //     $parentCharging = $query->charging->getParentsAttribute();
                //     $charging = $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc : null;
                // }

                
                $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                $chargingDescKra = "" ;
                if ($query->charging != null){
                    $chargingDescKra .= $query->charging->kraCharging ? " | " . $query->charging->kraCharging->account_title : null; 
                }
                $charging = null;
                if ($query->charging != null){
                    $parentCharging = $query->charging->getParentsAttribute();
                    $charging =  $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc .$chargingDescKra   : null;
                }
   
                $data = [
                    'Employee Number' => $query->employee_id,
                    'Employee Name' => Str::upper($query->last_name) . " " . Str::upper($query->first_name),
                    'Employee Salary' => number_format($query->salary, 2),
                    'Employee Position' => $query->position->job_title ?? null,
                    'Charging' => ($obr) ? $query->charging_id . '@@' . $chargingDesc : $charging,
                    'Salary Period' => $salaryPeriod,
                    'Payroll Period' => $this->rangeQuincena($payrollDetails->quincena, $payrollDetails->month),
                    'WithoutPay' => $withOutPay,
                    'Deductions' => $deduction,
                    'Tax' => $tax > 0 ? number_format($tax,2) : 0,
                    'NetpayWOdeduction' => $netWOdeduction,
                    'Netpay' => $netPay,
                ];

                return $data;
            });
        } elseif ($payrollDetails->payrollGroups->payroll_type == 2 || $payrollDetails->payrollGroups->payroll_type == 4) {

            $group_employee =   collect($employees)->map(function ($query) use ($payrollDetails,$obr) {

                $salaryPeriod = $this->salaryPeriodCasual($query,$payrollDetails,$payrollDetails->quincena);

                $withOutPay = $this->casualWithoutPay($query, $payrollDetails->month, $payrollDetails->payrollGroups->payroll_type,$payrollDetails->quincena);
                $deduction = $this->employeeDeductions($query->employee_id, $payrollDetails->month,$payrollDetails->quincena);
                $tax = $this->employeeTax($query->employee_id, $salaryPeriod,$withOutPay,$deduction,$salaryPeriod['amount_render'],$payrollDetails->quincena);
                $netWOdeduction = $this->employeeNetWOdeduction($salaryPeriod,$withOutPay);
                $netPay = $this->employeeNetPay($deduction,$salaryPeriod,$withOutPay,$tax);
                // $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                // $charging = null;

                // if ($query->charging != null){
                //     $parentCharging = $query->charging->getParentsAttribute();
                //     $charging = $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc : null;
                // }

                $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                $chargingDescKra = "" ;
                
                if ($query->charging != null){
                    $chargingDescKra .= $query->charging->kraCharging ? " | " . $query->charging->kraCharging->account_title : null; 
                }

                $charging = null;
                if ($query->charging != null){
                    $parentCharging = $query->charging->getParentsAttribute();
                    $charging =  $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc .$chargingDescKra   : null;
                }

                return [
                    'Employee Number' => $query->employee_id,
                    'Employee Name' => Str::upper($query->last_name) . " " . Str::upper($query->first_name),
                    'Employee Salary' => number_format($query->salary, 2),
                    'Employee Position' => $query->position->job_title ?? null,
                    'Charging' => ($obr) ? $query->charging_id . '@@' . $chargingDesc : $charging,
                    'Salary Period' => $salaryPeriod,
                    'Payroll Period' => $this->rangeQuincena($payrollDetails->quincena, $payrollDetails->month),
                    'WithoutPay' => $withOutPay,
                    'Deductions' => $deduction,
                    'Tax' => $tax > 0 ? number_format($tax,2) : 0,
                    'NetpayWOdeduction' => $netWOdeduction,
                    'Netpay' => $netPay,
                ];

            });
        }

        $payrollTotals = [
            "Total Rate Per Month" => $this->payrollTotals($group_employee, 'Employee Salary'),
            "Total Salary Period" => $this->payrollTotalsWithParams($group_employee, 'Salary Period', 'amount_render'),
            "Total WithoutPay" => $this->payrollTotalsWithParams($group_employee, 'WithoutPay', 'amount_deducted'),
            "Total Netpay" => $this->payrollTotals($group_employee, 'Netpay'),
            "Total Tax" => $this->payrollTotals($group_employee, 'Tax'),
            "Total NetpayWOdeduction" => $this->payrollTotals($group_employee, 'NetpayWOdeduction'),
            "Total Deduction" => collect($this->getDeductionsListFromGroup($payrollDetails->month))->map(function ($query) use ($group_employee) {
                return [
                    "Deduction ID" => $query->deduction_id,
                    "Total Deduction" => $this->payrollDeductTotal($group_employee, $query->deduction_id)
                ];
            })
        ];
  
        $data['headerInfo'] = [
            'Department' => $payrollDetails->department->description,
            'Period' => $this->rangeQuincena($payrollDetails->quincena, $payrollDetails->month),
            'DeductionHeader' => $this->getDeductionsListFromGroup($payrollDetails->month),
            'Control Number' => $payrollDetails->control_number,
            'Payroll Name' => $payrollDetails->payrollGroups->group_name,
            'Type' => $payrollDetails->payroll_type,
        ];

        $data['footerInfo'] = $payrollTotals;
        $data['signatory'] = $this->setSignatoryDetails($payrollDetails,false);
        $group_employee = collect($group_employee)->values()->groupBy('Charging');
        $data['data'] = $group_employee;
        
        return $data;
    }

    public function payrollTotals($collect, $key)
    {
        $totalAmount = collect($collect)->sum(function ($query) use ($key) {
            return $this->floatvalsafe($query[$key]);
        });
        return $totalAmount;
    }

  
    public function payrollTotalsWithParams($collect, $key, $params)
    {
        $totalAmount = collect($collect)->sum(function ($query) use ($key, $params) {
            return $this->floatvalsafe($query[$key][$params]);
        });
        return $totalAmount;
    }

    public function payrollDeductTotal($collect, $deduction)
    {
        $totalAmount = collect($collect)->sum(function ($query) use ($deduction) {
            foreach ($query['Deductions'] as $key => $empDeduction) {
                if ($empDeduction['Deduction ID'] == $deduction) {
                    return $this->floatvalsafe($empDeduction['Amount']);
                }
            }
        });
        return $totalAmount;
    }

    public function setSignatoryDetails($payrollDetails,$default=true)
    {
        if ($default){
            $deptId = $payrollDetails->department_id;
            $signatory = $this->getSignatory($deptId);
        }else{
            $deptId = $payrollDetails->department_charging_id == null ? $payrollDetails->department_id : $payrollDetails->department_charging_id;
            $signatory = $this->getSignatory($deptId);
        }
        $listOfSignatory = [];
        
        $listOfSignatory['Department Head'] = [
            'Head' => "<NOT SET>",
            'Position' => "<NOT SET>"
        ];

        foreach ($signatory as $key => $signed) {
            if ($signed->signatory_role == 1 && $signed->department_id == $deptId) {
                $listOfSignatory['Department Head'] = [
                    'Head' => $signed->department_head,
                    'Position' => $signed->department_head_position
                ];
                $listOfSignatory['Department Head Obr'] = [
                    'Head' => $signed->obr_signatory,
                    'Position' => $signed->obr_signatory_position
                ];
            } elseif ($signed->signatory_role == 2) { //hr
                $listOfSignatory['HR Head'] = [
                    'Head' => $signed->department_head,
                    'Position' => $signed->department_head_position
                ];
            } elseif ($signed->signatory_role == 3) { // pto
                $listOfSignatory['PTO Head'] = [
                    'Head' => $signed->department_head,
                    'Position' => $signed->department_head_position
                ];
            } elseif ($signed->signatory_role == 4) { // opa
                $listOfSignatory['OPA Head'] = [
                    'Head' => $signed->department_head,
                    'Position' => $signed->department_head_position
                ];
            } elseif ($signed->signatory_role == 7 && $signed->company == 'SP') { // vice gov
                if ($payrollDetails->is_signatory == 1){
                    $listOfSignatory['Governor'] = [
                        'Head' => $signed->department_head,
                        'Position' => $signed->department_head_position
                    ];
                }

            } elseif ($signed->signatory_role == 5 && $signed->company == 'PGOM') { // gov
                $listOfSignatory['Governor'] = [
                    'Head' => $signed->department_head,
                    'Position' => $signed->department_head_position
                ];
               
               
            } elseif ($signed->signatory_role == 6) { // PBO
                $listOfSignatory['PBO Head'] = [
                    'Head' => $signed->department_head,
                    'Position' => $signed->department_head_position
                ];
            }
        }
        return $listOfSignatory;
    }


    public function payrollPRocessPO($quin,$payrollDetails, $employees, $obr = false)
    {
        $quin = request()->input('quincena');
        $month = request()->input('month');

        if ($payrollDetails->payroll_type == 1 || $payrollDetails->payroll_type == 3) { //casual

            $group_employee = collect($employees)->map(function ($query) use ($payrollDetails,$quin,$month,$obr) {
                $salaryPeriod = $this->salaryPeriodPO($query,$payrollDetails,$quin);
                
                $withOutPay = $this->casualWithoutPay($query, $month, $payrollDetails->payroll_type,$quin);
                
                $deduction = $this->employeeDeductions($query->employee_id, $month,$quin);
                $tax = $this->employeeTax($query->employee_id, $salaryPeriod,$withOutPay,$deduction,$query->salary,$quin);
               
                $netWOdeduction = $this->employeeNetWOdeduction($salaryPeriod,$withOutPay);
                $netPay = $this->employeeNetPay($deduction,$salaryPeriod,$withOutPay,$tax);

                $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                $chargingDescKra = "" ;
                
                if ($query->charging != null){
                    $chargingDescKra .= $query->charging->kraCharging ? " | " . $query->charging->kraCharging->account_title : null; 
                }
                $charging = null;
                if ($query->charging != null){
                    $parentCharging = $query->charging->getParentsAttribute();
                    $charging =  $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc .$chargingDescKra   : null;
                }

                $data = [
                    'Employee Number' => $query->employee_id,
                    'Employee Name' => Str::upper($query->last_name) . " " . Str::upper($query->first_name),
                    'Employee Salary' => number_format($query->salary, 2),
                    'Employee Position' => $query->position->job_title ?? null,
                    'Charging' => ($obr) ? $query->charging_id . '@@' . $chargingDesc : $charging,
                    'Salary Period' => $salaryPeriod,
                    'Payroll Period' => $this->rangeQuincena($quin, $month),
                    'WithoutPay' => $withOutPay,
                    'Deductions' => $deduction,
                    'Tax' => $tax > 0 ? number_format($tax,2) : 0,
                    'NetpayWOdeduction' => $netWOdeduction,
                    'Netpay' => $netPay,
                ];
                return $data;
            });


        } elseif ($payrollDetails->payroll_type == 2 || $payrollDetails->payroll_type == 4) {
            $group_employee =   collect($employees)->map(function ($query) use ($payrollDetails,$quin,$month,$obr) {
                
                $salaryPeriod = $this->salaryPeriodCasualPO($query,$payrollDetails,$month,$quin);
                $withOutPay = $this->casualWithoutPay($query, $month, $payrollDetails->payroll_type,$quin);
                $deduction = $this->employeeDeductions($query->employee_id, $month,$quin);
                $tax = $this->employeeTax($query->employee_id, $salaryPeriod,$withOutPay,$deduction,$salaryPeriod['amount_render'],$quin);
                $netWOdeduction = $this->employeeNetWOdeduction($salaryPeriod,$withOutPay);
                $netPay = $this->employeeNetPay($deduction,$salaryPeriod,$withOutPay,$tax);

                $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                $charging = null;
                
                // $chargingDescKra = "" ;
                
                // if ($query->charging != null){
                //     $chargingDescKra .= $query->charging->kraCharging ? " | " . $query->charging->kraCharging->account_title : null; 
                // }

                // if ($query->charging != null){
                //     $parentCharging = $query->charging->getParentsAttribute();
                //     $charging = $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc  : null;
                // }


                $chargingDesc = $query->charging != null ? $query->charging->charging_name : null;
                $chargingDescKra = "" ;
                
                if ($query->charging != null){
                    $chargingDescKra .= $query->charging->kraCharging ? " | " . $query->charging->kraCharging->account_title : null; 
                }

                $charging = null;
                if ($query->charging != null){
                    $parentCharging = $query->charging->getParentsAttribute();
                    $charging =  $parentCharging ? implode(" | ",$parentCharging) . " | " . $chargingDesc .$chargingDescKra   : null;
                }

                return [
                    'Employee Number' => $query->employee_id,
                    'Employee Name' => Str::upper($query->last_name) . " " . Str::upper($query->first_name),
                    'Employee Salary' => number_format($query->salary, 2),
                    'Employee Position' => $query->position->job_title ?? null,
                    'Charging' => ($obr) ? $query->charging_id . '@@' . $chargingDesc : $charging,
                    'Salary Period' => $salaryPeriod,
                    'Payroll Period' => $this->rangeQuincena($quin, $month),
                    'WithoutPay' => $withOutPay,
                    'Deductions' => $deduction,
                    'Tax' => $tax,
                    'NetpayWOdeduction' => $netWOdeduction,
                    'Netpay' => $netPay,
                ];
            });

        }

        $payrollTotals = [
            "Total Rate Per Month" => $this->payrollTotals($group_employee, 'Employee Salary'),
            "Total Salary Period" => $this->payrollTotalsWithParams($group_employee, 'Salary Period', 'amount_render'),
            "Total WithoutPay" => $this->payrollTotalsWithParams($group_employee, 'WithoutPay', 'amount_deducted'),
            "Total Netpay" => $this->payrollTotals($group_employee, 'Netpay'),
            "Total Tax" => $this->payrollTotals($group_employee, 'Tax'),
            "Total NetpayWOdeduction" => $this->payrollTotals($group_employee, 'NetpayWOdeduction'),
            "Total Deduction" => collect($this->getDeductionsListFromGroup($month))->map(function ($query) use ($group_employee) {
                return [
                    "Deduction ID" => $query->deduction_id,
                    "Total Deduction" => $this->payrollDeductTotal($group_employee, $query->deduction_id)
                ];
            })
        ];

        $control = date('ymd') . $quin . $payrollDetails->id;
        $data['headerInfo'] = [
            'Department' => $payrollDetails->department_->description,
            'Period' => $this->rangeQuincena($quin, $month),
            'DeductionHeader' => $this->getDeductionsListFromGroup($month),
            'Control Number' => $control,
            'Payroll Name' => $payrollDetails->group_name,
            'Type' => $payrollDetails->payroll_type,
        ];
        $data['footerInfo'] = $payrollTotals;
        $data['signatory'] = $this->setSignatoryDetails($payrollDetails,false);
        $group_employee = collect($group_employee)->values()->groupBy('Charging');


        $saveData = PayrollControlMonitoring::updateOrCreate([
            "payroll_group_id" => $payrollDetails->id,
            "payroll_period" => $quin,
            "month" => $month . '-01',
        ],[
            "payroll_group_id" => $payrollDetails->id,
            "payroll_period" => $quin,
            "amount" => $payrollTotals['Total Netpay'],
            "month" => $month . '-01',
            "control_number" => $control
        ]);

        $data['data'] = $group_employee;
        return $data;
    }

    public static function employeeDeductions($employee_id, $month,$quin)
    {
       
        $deduction = casual_deduction::all();
        $listOfDeduction = collect($deduction)->map(function ($query) use ($employee_id, $month,$quin) {
            $exemptEmp = casual_deduction_exempt::where('casual_employee_id', $employee_id)
                ->where('deduction_id', $query->id)
                ->whereDate('month', '<=', date('Y-m-01', strtotime($month)))
                ->first();
             
            if ($quin == 2){
                return [
                    'Deduction ID' => $query->deduction_id,
                    'Deduction Name' => $query->deductions->deduction_nick,
                    'Amount' => 0,
                ];
            }

            //9462
            if ($employee_id == '1332' && $query->id == 4){
                $query->amount = number_format(800,2);
            }
            return [
                'Deduction ID' => $query->deduction_id,
                'Deduction Name' => $query->deductions->deduction_nick,
                'Amount' => (!$exemptEmp) ? $query->amount  : 0,
            ];
        })->values();
        return $listOfDeduction;
    }

}
