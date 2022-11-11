<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        @page {
            color: #000 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0px;
        }

        @media all {
            body {
                font-size: 11px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin-top: 0.11in;
                margin-bottom: 0.55in;
                margin-left: 0.33in;
                margin-right: 0.33in;
            }

            html {
                color: #000;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            table {
                border-collapse: collapse;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                
            }

            #maintbl,
            #maintbl1,
            #summarytbl {
                border-collapse: collapse;
                border: 1px solid #333333;
                width: 100%
            }

            #maintbl th,
            #maintbl1 th,
            #summarytbl th {
                height: 10px;
                background-color: #c9d5ff;
                font-size: 0.85em;
                font-weight: bold;
                text-align: center;
                border: 1px solid #333333;
                padding: 3px 2px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

          

            #maintbl td {
                height: 15px;
            }

            #maintbl1 td {
                height: 15px;
                padding: 5px 2px;
            }

            #signatories #summarytbl td {
                height: 15px;
                vertical-align: middle;
            }

            #signatories {
                width: 100%;
            }

            #signatories td {
                height: 8px;
                font-size: 0.9em;
                empty-cells: show;
                vertical-align: top;
            }

            .headings {
                height: 30px;
                font-weight: bold;
                vertical-align: middle;
                white-space: nowrap;
            }

            .text-bold {
                font-weight: bold;
            }

            .text-left {
                text-align: left !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-small {
                font-size: 0.8em !important;
                line-height: 1em !important;
            }

            .text-smaller {
                font-size: 0.6em !important;
                line-height: 1em !important;
            }

            .text-white {
                color: transparent;
            }

            .white {
                padding: 0 3px;
                border: 1px solid #333333;
            }

            .colored {
                padding: 0 3px;
                border: 1px solid #333333;
                background-color: #e8eff9 !important;
            }

            .colored2 {
                padding: 0 3px;
                border: 1px solid #333333;
                background-color: #c9d5ff !important;
            }

            .colored3 {
                padding: 0 3px;
                border: 1px solid #333333;
                background-color: palegreen !important;
            }

            .colored4 {
                padding: 0 3px;
                border: 1px solid #333333;
                color: #fff;
                background-color: #ff0000 !important;
            }

            .colored5 {
                padding: 0 3px;
                border: 1px solid #333333;
                background-color: #f0f373 !important;
            }

            .currency {
                font-size: 1.1em !important;
                text-align: right;
            }

            .notes {
                font-size: 0.8em !important;
                font-weight: normal;
            }

            .signhead {
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #000;
                font-size: 1.1em;
                font-weight: bold;
            }

            .text-xs {
                font-size: 0.6em !important;
                font-weight: normal;
            }

            .break-here {
                page-break-inside: always;
                box-decoration-break: slice;
            }

            .fullscreen {
                min-height: 100%;
                min-width: 1024px;
                width: 100%;
                height: auto;
                position: fixed;
                top: 0;
                left: 0;
                background-color: rgba(255, 255, 255, 0.5);
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

            #loaderText {
                font-family: "Times New Roman", Arial, sans-serif, Helvetica, Arial, sans-serif;
                font-size: 20px;
                font-weight: bold;
            }

            #note {
                font-family: "Times New Roman", Arial, sans-serif, Helvetica, Arial, sans-serif;
                font-size: 14px;
                font-style: italic;
            }

            td>div span.pastTotal {
                display: none;
                height: 0;
                width: 0;
            }

            td>div span.currentTotal {
                text-decoration: overline;
            }

            #watermark {
                position: fixed;
                top: 250px;
                left: 120px;
                font-size: 100px;
                font-weight: 400;
                opacity: 0.1;
                /* z-index: -99; */
                color: black;
                text-transform: uppercase;
                transform: rotate(-25deg);
            }

            #control-number {
                position: absolute;
                top: 10px;
                right: 50px;
                font-size: 14px;
                font-weight: 500;
                color: black;
            }

            body{
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .page-break{
                page-break-after: always;
            }


        }
    </style>
</head>

<body>
    <div id="control-number">
        Control Number : {{ $headerInfo['Control Number']}}<br>
        Payroll Name : {{ $headerInfo['Payroll Name'] }}
    </div>
    @if (auth()->user()->hasRoles('OPA') || auth()->user()->hasRoles('HR'))
    <div id="watermark">Review Payroll</div>
    @elseif (auth()->user()->hasRoles('PSU'))

    @elseif (auth()->user()->hasRoles('PAYROLL_OFFICER'))
    <!-- <div id="watermark">Preview Payroll</div> -->
    @else
    <div id="watermark">Recorded Payroll</div>
    @endif
    <table border="0" width="100%" align="center" cellspacing="0" cellpadding="0">
        <tr>
            <th rowspan="3" width="33.33%"><img src="{{ asset('/images/logo.png') }}" alt="PGOM Logo" width="60px"></th>
            <th style="font-size:18px">GENERAL PAYROLL</th>
            <th rowspan="3" width="33.33%" class="text-smaller">
                &nbsp;
            </th>
        </tr>
        <tr>
            <th colspan="1" class="titles" style="font-size:16px">{{ strtoupper($headerInfo['Department']) }}</th>
        </tr>
        <tr>
            <th style="font-size:14px">{{ strtoupper($headerInfo['Period']) }}</th>
        </tr>
    </table>
    <BR>
    We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated:

    <table id='maintbl' autosize='1' border='1' >
        <thead>
            <tr style="page-break-inside: auto;">
                <th rowspan='2'>#</th>
                <th rowspan='2'>Name</th>
                <th rowspan='2'>Designation</th>
                @if ($headerInfo["Type"] == 1 || $headerInfo["Type"] == 3)
                <th rowspan='2'>Rate Per Month</th>
                @else  
                <th rowspan='2'>Monthly Rate</th>
                @endif
                <th rowspan='2'>Period</th>
                <th rowspan='2'>{{ ($headerInfo["Type"] == 1 || $headerInfo["Type"] == 3) ? 'Salary per period' : 'Net Pay' }}</th>
                <th rowspan='2'>WithoutPay</th>
                <th rowspan='2'>Net W/O Deduction</th>
                <th rowspan='2'>Tax</th>
               
                @if(count($headerInfo['DeductionHeader'])>0)
                <th colspan="{{ count($headerInfo['DeductionHeader']) }}">Deductions</th>
                @endif
                <th rowspan='2'>Total</th>
                <th rowspan='2'>Amount Due</th>
                <th rowspan='2'>Amount Received</th>
                <th rowspan='2'>Signature</th>
            </tr>
            <tr>
                @if(count($headerInfo['DeductionHeader'])>0)
                @foreach($headerInfo['DeductionHeader'] as $deductionHeader)
                <th>{{ $deductionHeader['deductions']['deduction_nick'] }}</th>
                @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @php
            $x = 0;
            $i = 0;
            $initialColumn = 12
            @endphp

            @foreach($data as $charging => $group)
            @php
            $i++;
            $color = ($x % 2 == 1) ? "colored" : "white";
            
            @endphp
            <tr>
                <td class='{{$color}} text-center'></td>
                <td colspan="{{ $initialColumn + count($headerInfo['DeductionHeader']) }}" class='{{$color}} headings text-left'>{{$charging}}</td>
            </tr>

            @foreach($group as $key => $employee)
            @php
            $color = ($x % 2 == 1) ? "colored" : "white";
            $x++;
            $i++;
            @endphp
           
            <tr>
                <td style="{{ $style }}" class='{{$color}} text-center'>{{$x}}</td>
                <td style="{{ $style }}" class='{{$color}} text-left'>{{$employee['Employee Name']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-center'>{{$employee['Employee Position']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-right'>{{$employee['Employee Salary']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-center'>{{$employee['Payroll Period']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-right'>
                    <small>{{ $employee['Salary Period']['amount_render'] == 0 ? '' : number_format($employee['Salary Period']['amount_render'],2) }}</small>
                    <br>
                    <small style="color:#006400;">{{ $employee['Salary Period']['format_days'] == null ? '' : '(' . $employee['Salary Period']['format_days'] . ')' }}</small>
                </td>
                <td style="{{ $style }}" class='{{$color}} text-right'>
                    <small>{{ $employee['WithoutPay']['amount_deducted'] == 0 ? '' : number_format($employee['WithoutPay']['amount_deducted'],2) }}</small>
                    <br>
                    @if ($employee['WithoutPay']['amount_deducted'] != 0)
                    <small style="color:#FF0000;">{{ $employee['WithoutPay']['format_days'] == null ? '' : '(' . $employee['WithoutPay']['format_days'] . ')' }}</small>
                    @endif
                </td>
                <td style="{{ $style }}" class='{{$color}} text-right'>{{ $employee['NetpayWOdeduction'] == 0 ? '' : $employee['NetpayWOdeduction']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-right'>{{ $employee['Tax'] == 0 ? '' : $employee['Tax']}}</td>
                @foreach($headerInfo['DeductionHeader'] as $deductionHeader)
                @foreach($employee['Deductions'] as $deduction)
                @if($deductionHeader['deductions']['deduction_nick'] == $deduction['Deduction Name'])
                <td style="{{ $style }}" class='{{$color}} text-right'>{{$deduction['Amount'] != 0 ? $deduction['Amount'] : ''}}</td>
                @endif
                @endforeach
                @endforeach
                <td style="{{ $style }}" class='{{$color}} text-right'>{{$employee['Netpay']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-right'>{{$employee['Netpay']}}</td>
                <td style="{{ $style }}" class='{{$color}} text-right'></td>
                <td style="{{ $style }}" class='{{$color}} text-right'></td>
            </tr>
            
            @endforeach

            
            @endforeach

        </tbody>
   
        <tfoot>
            <tr>
                <td style="{{ $style }}" class='colored2 text-right'></td>
                <td style="{{ $style }}" class='colored2 text-right headings text-center'>TOTAL</td>
                <td style="{{ $style }}" class='colored2 text-right'></td>
                <td style="{{ $style }}" class='colored2 text-right'>{{number_format($footerInfo['Total Rate Per Month'],2)}}</td>
                <td style="{{ $style }}" class='colored2 text-right'></td>
                <td style="{{ $style }}" class='colored2 text-right'>{{number_format($footerInfo['Total Salary Period'],2)}}</td>
                <td style="{{ $style }}" class='colored2 text-right'>{{$footerInfo['Total WithoutPay'] == 0 ? '' : number_format($footerInfo['Total WithoutPay'],2)}}</td>
                <td style="{{ $style }}" class='colored2 text-right'>{{ $footerInfo['Total NetpayWOdeduction'] == 0 ? '' : number_format($footerInfo['Total NetpayWOdeduction'],2) }}</td>
                <td style="{{ $style }}" class='colored2 text-right'>{{ $footerInfo['Total Tax'] == 0 ? '' : number_format($footerInfo['Total Tax'],2) }}</td>
                @foreach($headerInfo['DeductionHeader'] as $deductionHeader)
                @foreach ($footerInfo['Total Deduction'] as $footer)
                @if($deductionHeader['deductions']['id'] == $footer['Deduction ID'])
                <td style="{{ $style }}" class='colored2 text-right'>{{ $footer['Total Deduction'] == 0 ? '' : number_format($footer['Total Deduction'],2) }}</td>
                @endif
                @endforeach
                @endforeach
                <td style="{{ $style }}" class='colored2 text-right'>{{ $footerInfo['Total Netpay'] == 0 ? '' : number_format($footerInfo['Total Netpay'],2) }}</td>
                <td style="{{ $style }}" class='colored2 text-right'>{{ $footerInfo['Total Netpay'] == 0 ? '' : number_format($footerInfo['Total Netpay'],2) }}</td>
                <td style="{{ $style }}" class='colored2 text-right'></td>
                <td style="{{ $style }}" class='colored2 text-right'></td>


            </tr>
        </tfoot>
    </table>

<!--  style="margin-top:50px" -->
    <table border="0" align="left" cellspacing="0" cellpadding="0" id="signatories" class="page-me">
        <tr height="10">
            <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="text-bold">CERTIFIED:</div>
                <div class="notes">Each person whose name appears on this roll had rendered services for the time stated.</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">
                <div class="text-bold">PHRMO CERTIFICATION:</div>
                <div class="notes">Verified as to Salary Rate, Position Title & Leave Credits</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">
                <div class="text-bold">CERTIFIED:</div>
                <div class="notes">Funds available in the amount of P __________________</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" style="white-space:nowrap">';
                <div class="signhead">&nbsp;&nbsp;&nbsp;{{ $signatory['Department Head']['Head'] }}&nbsp;&nbsp;&nbsp;</div>
                <div>{{ $signatory['Department Head']['Position'] }}</div>
            </td>

            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" style="width:200px">
                <div class="signhead">&nbsp;&nbsp;&nbsp;{{ $signatory['HR Head']['Head'] }}&nbsp;&nbsp;&nbsp;</div>
                <div>{{ $signatory['HR Head']['Position'] }}</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" style="width:200px">
                <div class="signhead">&nbsp;&nbsp;&nbsp;{{ $signatory['PTO Head']['Head'] }}&nbsp;&nbsp;&nbsp;</div>
                <div>{{ $signatory['PTO Head']['Position'] }}</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="text-bold">CERTIFIED:</div>
                <div class="notes">* Allotment obligated for the purpose as indicated above<br>* Supporting documents complete</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">
                <div class="text-bold">Approved for Payment:</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">
                <div class="text-bold">CERTIFIED:</div>
                <div class="notes">Each person whose name appears on this roll has been paid the amount stated apposite his name after identifying him/her.</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>

        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" style="width:200px">
                <div class="signhead">&nbsp;&nbsp;&nbsp;{{ $signatory['OPA Head']['Head'] }}&nbsp;&nbsp;&nbsp;</div>
                <div>{{ $signatory['OPA Head']['Position'] }}</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" style="width:200px">
                <div class="signhead">{{ $signatory['Governor']['Head'] }}</div>
                <div>{{ $signatory['Governor']['Position'] }}</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" style="width:200px">
                <div class="signhead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div>Disbursing Officer / Date</div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="3"></td>
        </tr>
    </table>




</body>

</html>