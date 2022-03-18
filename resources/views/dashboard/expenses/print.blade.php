<html>
    <head>
        <title>Print {{$expense->account_code}}</title>
        <style>
            @media print {
            @page { margin: 0; }
            body { margin: 1.6cm; }
            .print-bar {
                display: none;
            }
            }
            body {
                font-family: arial;
                font-size: 13px;
                line-height: 13px;
            }
            table {
                font-size: 13px;
            }
            .head{
                text-align: center;
            }
            .date {
                text-align: right
            }
            table tr td:last-child {
                border-bottom: 1px solid #000;
                width: 200px;
            }
            table.office {
                width: 300px;
            }
            table.main {
                width: 500px;
            }
            .text-center {
                text-align: center;
            }
            .row {
                
            }
            .col-half {
                width: 50%;
                display: inline-block;
                float: left;
            }
            .clearfix {
                clear: both;
            }
            .print-container {
                max-width: 1200px;
                margin: 0px auto;
            }
            .print-container:first-child {
                padding-bottom: 20px;
                margin-top: -20px
            }
            .red {
                color: red
            }
            .print-bar {
                background: grey;
                position: absolute;
                height: 30px;
                width: 100%;
                left: 0px;
                top: 0px;
                padding-top: 5px;
                padding-left: 5px;
            }
            .close {
                float: right;
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        <div class="print-bar">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()" class="close">Close</button>
        </div>
        <div class="print-container">
            <div class="head">
                Republic of the Philippines <br>
                <strong>PROVINCE OF AGUSAN DEL SUR</strong><br>
                <strong>Municipality of Talacogon</strong>
                <p class="date">Date: {{$expense->created_at}}</p>
            </div>
            <div class="content">
                <table class="table office">
                    <tr>
                        <td>OFFICE:</td>
                        <td>{{$expense->office->category->parent->name}}</td>
                    </tr>
                    <tr>
                        <td>PROGRAM:</td>
                        <td></td>
                    </tr>
                </table>
                <p class="text-center"><strong>STATUS OF APPROPRIATION AND ALLOTMENT</strong></p>
                <table class="main">
                    <tr>
                        <td>EXPENSE CLASS</td>
                        <td>{{$expense->office->name}}</td>
                    </tr>
                    <tr>
                        <td>ACCOUNT CODE</td>
                        <td>{{$expense->account_code}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL APPROPRIATION</td>
                        <td>{{format_amount($expense->office->getAppropriation($expense->year))}}</td>
                    </tr>
                </table>
                <br>
                <table class="main">
                    <tr>
                        <td>Total Allotment Release as <br>
                        of the <strong><u>{{get_month_quarter($expense->month)}}</u></strong> quarter</td>
                        <td>{{format_amount($expense->transaction()->allotment_available)}}</td>
                    </tr>
                    <tr>
                        <td>Less: Total obligation Incurred</td>
                        <td>{{format_amount($expense->transaction()->expense_total)}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL ALLOTMENT AVAILABLE</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Less: this claim (Voucher, Payroll, etc.)</td>
                        <td class="red">{{format_amount($expense->amount)}}</td>
                    </tr>
                    <tr>
                        <td>BALANCE OF ALLOTMENT</td>
                        <td>{{format_amount($expense->transaction()->ending_balance)}}</td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-half">
                        <p>APPROVED:</p>
                        <p>
                            _____________________________ <br>
                            Chief of Office
                        </p>
                    </div>
                    <div class="col-half">
                        <p>CERTIFIED CORRECT:</p>
                        <p><strong>MARILOU P. AZUCENA,MM</strong><br>
                        Budgeting Assistant</p>
                    </div>
                    <div class="clearfix">
                        <p class="text-center">
                            <br>
                            <strong>GWENDOLYN &nbsp;A.&nbsp; CLAROS, REB,MEM</strong> <br>
                            Municipal Budget Officer
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <br>
        <div class="print-container">
            <div class="head">
                Republic of the Philippines <br>
                <strong>PROVINCE OF AGUSAN DEL SUR</strong><br>
                <strong>Municipality of Talacogon</strong>
                <p class="date">Date: {{$expense->created_at}}</p>
            </div>
            <div class="content">
                <table class="table office">
                    <tr>
                        <td>OFFICE:</td>
                        <td>{{$expense->office->category->parent->name}}</td>
                    </tr>
                    <tr>
                        <td>PROGRAM:</td>
                        <td></td>
                    </tr>
                </table>
                <p class="text-center"><strong>STATUS OF APPROPRIATION AND ALLOTMENT</strong></p>
                <table class="main">
                    <tr>
                        <td>EXPENSE CLASS</td>
                        <td>{{$expense->office->name}}</td>
                    </tr>
                    <tr>
                        <td>ACCOUNT CODE</td>
                        <td>{{$expense->account_code}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL APPROPRIATION</td>
                        <td>{{format_amount($expense->office->getAppropriation($expense->year))}}</td>
                    </tr>
                </table>
                <br>
                <table class="main">
                    <tr>
                        <td>Total Allotment Release as <br>
                        of the <strong><u>{{get_month_quarter($expense->month)}}</u></strong> quarter</td>
                        <td>{{format_amount($expense->transaction()->allotment_available)}}</td>
                    </tr>
                    <tr>
                        <td>Less: Total obligation Incurred</td>
                        <td>{{format_amount($expense->transaction()->expense_total)}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL ALLOTMENT AVAILABLE</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Less: this claim (Voucher, Payroll, etc.)</td>
                        <td class="red">{{format_amount($expense->amount)}}</td>
                    </tr>
                    <tr>
                        <td>BALANCE OF ALLOTMENT</td>
                        <td>{{format_amount($expense->transaction()->ending_balance)}}</td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-half">
                        <p>APPROVED:</p>
                        <p>
                            _____________________________ <br>
                            Chief of Office
                        </p>
                    </div>
                    <div class="col-half">
                        <p>CERTIFIED CORRECT:</p>
                        <p><strong>MARILOU P. AZUCENA,MM</strong><br>
                        Budgeting Assistant</p>
                    </div>
                    <div class="clearfix">
                        <p class="text-center">
                            <br>
                            <strong>GWENDOLYN &nbsp;A.&nbsp; CLAROS, REB,MEM</strong> <br>
                            Municipal Budget Officer
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // window.print();
        </script>
    </body>
</html>