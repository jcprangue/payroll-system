<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBR</title>

    <style>
        @page {
            color: #000 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0px;
        }

        @media all {
            body {
                font-size: 13px !important;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin-top: 0.11in;
                margin-bottom: 0.11in;
                margin-left: 0.11in;
                margin-right: 0.11in;
            }

            html {
                color: #000;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 10px;
            }

            #table_obr {
                border-collapse: collapse;
                border-top: 3px double #333333;
                border-bottom: 3px double #333333;
                border-left: 1px solid #333333;
                border-right: 1px solid #333333;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                width: 100%
            }

            #table_obr thead,
            #table_obr tfoot {
                border-left: 1px solid #333333;
                border-right: 1px solid #333333;
                border-top: 1px solid #333333;
                border-bottom: 3px double #333333;
            }

            #table_obr thead td,
            #table_obr tfoot td {
                border-right: 1px solid #333333;
                border-bottom: 1px solid #333333;
            }

            #table_obr td {
                // height:10px;
                padding: 0 5px;
            }

            #table_obr tbody {
                border-left: 1px solid #333333;
                border-right: 1px solid #333333;
                border-top: 3px double #333333;
                border-bottom: 3px double #333333;
            }

            #table_obr tbody td {
                border-right: 1px solid #333333;
                border-bottom: 0.5px solid #e0e0e0;
            }

            #table_obr .sep td {
                height: 7px;
            }

            #table_obr .twice td {
                height: 30px;
            }

            #table_obr td.no-border-right,
            #table_obr tfoot td .no-border-right {
                border-right: 0;
            }

            #table_obr td.thick-border-btm {
                border-bottom: 3px double #333333;
            }

            #table_obr td.semithick-border-right {
                border-right: 1px solid #333333;
            }

            #table_obr td.border-top {
                border-top: 1px solid #333333;
            }

            #table_obr td.notes {
                line-height: 1.4em !important;
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
                font-size: 0.9em !important;
                line-height: 1.2em !important;
            }

            .text-smaller {
                font-size: 0.7em !important;
                line-height: 1.2em !important;
            }

            .text-smallest {
                font-size: 0.6em !important;
                line-height: 1.2em !important;
            }

            .text-smallestest {
                font-size: 0.5em !important;
            }

            .text-big {
                font-size: 1.2em !important;
            }

            .text-bigger {
                font-size: 1.4em !important;
            }

            .text-white {
                color: transparent;
            }


        }
    </style>
</head>

<body>
    <table id='table_obr' border='0' cellpadding="3">
        <thead>
            <tr>
                <td style="border-left:none;border-right:none" align="center" colspan="9">
                    <span class="text-smaller">&nbsp;</span><br>
                    Republic of the Philippines
                    <br><b>PROVINCIAL GOVERNMENT OF ORIENTAL MINDORO</b>
                    <br><span class="text-small">Calapan City</span><br>
                    <span class="text-smaller">&nbsp;</span>
                </td>

            </tr>
            <tr>
                <td class="thick-border-btm text-bold text-bigger" colspan="7" align="center">OBLIGATION REQUEST</td>
                <td class="thick-border-btm" colspan="2" align="left">No.</td>
            </tr>
            <tr>
                <td class="text-small">Payee</td>
                <td class="text-bold text-big" colspan="6" align="left">{{$header['Payee']}}</td>
                <td colspan="2" rowspan="3"></td>
            </tr>
            <tr>
                <td class="text-small">Office</td>
                <td colspan="6" align="left">{{$header['Office']}}</td>
            </tr>
            <tr>
                <td class="text-small">Address</td>
                <td colspan="6" align="left">{{$header['Address']}}</td>
            </tr>
            <tr>
                <td class="text-smallest" align="center" width="11%">Responsibility Center</td>
                <td class="text-bold" colspan="5" align="center">PARTICULARS</td>
                <td class="text-bold" align="center" width="8%">F.P.P</td>
                <td class="text-bold" align="center" width="12%">Account</td>
                <td class="text-bold" align="center" width="17%">Amount</td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class='text-center'>{{$header['Responsibility_Center']}}</td>
                <td class='text-bold' colspan='5'>{{ $header['Service'] }}</td>
                <td class='text-bold' align='center'>{{ $header['Obr_code'] }}</td>
                <td class='text-smaller'></td>
                <td align='right'></td>
            </tr>
            @if (count($data) > 0)
            @php
            $indent = 0;
            @endphp
            @foreach ($data as $project)
            @php 
            $sum = 0;
            @endphp
            @include('partials.charging', [
                "project" => $project,
                "indent" => $indent,
            ])
            @endforeach
            @else
            @endif

            <tr class='sep'>
                <td></td>
                <td class='no-border-right'>&nbsp;</td>
                <td colspan='4'></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            
            <tr >
                <td ></td>
                <td class='text-bold text-center' colspan='5' style="border:1px solid #000 !important">&nbsp;TOTAL</td>
                <td class='text-bold' align='center' style="border:1px solid #000 !important"></td>
                <td class='text-smaller' style="border:1px solid #000 !important"></td>
                <td align='right' style="border:1px solid #000 !important">{{$header['Net Pay']}}</td>
            </tr>

        </tbody>


        <tfoot>


            <tr>
                <td class="no-border-right">
                    <img align="right" src="{{ asset('/images/a-left.jpg') }}">
                </td>
                <td class="text-smallest notes" colspan="4" valign="top">
                    <b>Certified</b><br>
                    Charges to appropriation/allotment necessary, lawful and under my direct supervision.<br>
                    Supporting documents valid, proper and legal
                </td>
                <td class="no-border-right">
                    <img align="right" src="{{ asset('/images/b-right.jpg') }}">
                </td>
                <td class="text-smallest notes" colspan="3" valign="top">
                    <b>Certified</b><br><br>
                    Existence of available appropriation
                </td>
            </tr>
            <tr class="twice">
                <td class="text-smallestest" align="right">Signature</td>
                <td colspan="4"></td>
                <td class="text-smallestest" align="right">Signature</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="text-smallestest" align="right">Printed Name</td>
                <td class="text-bold" colspan="4" align="center"><small>{{$footer['Department Head Obr']['Head']}}</small></td>
                <td class="text-smallestest" align="right">Printed Name</td>
                <td class="text-bold" colspan="3" align="center">{{$footer['PBO Head']['Head']}}</td>
            </tr>
            <tr>
                <td class="text-smallestest" align="right">Position</td>
                <td class="text-small" colspan="4" align="center">{{$footer['Department Head Obr']['Position']}}</td>
                <td class="text-smallestest" align="right">Position</td>
                <td class="text-small" colspan="3" align="center">{{$footer['PBO Head']['Position']}}</td>
            </tr>
            <tr>
                <td class="text-smallestest"></td>
                <td class="text-smaller" align="center" colspan="4">Head, Requesting Office/Authorized Representative</td>
                <td class="text-smallestest"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="text-smallestest" align="right"><i>Date</i></td>
                <td colspan="4"></td>
                <td class="text-smallestest" align="right"><i>Date</i></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

</body>

</html>