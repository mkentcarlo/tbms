<html>
    <head>
        <title>Print expenses</title>
        <style>
            @media print {
            @page { margin: 0; }
            body { margin: 1.6cm; }
            }
            table {
              border-collapse: collapse;
            }
            table td, table th {
              border: 1px solid #000;
              padding: 3px;
            }
            .head {
              text-align: center;
            }
            .account-code {
              width: 200px;
            }
        </style>
    </head>
    <body>
        <div class="print-container">
            <div class="head">
                Republic of the Philippines <br>
                <strong>PROVINCE OF AGUSAN DEL SUR</strong><br>
                <strong>Municipality of Talacogon</strong>
            </div>
            <div class="content">
              
            <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th class="account-code">Account Code</th>
                    <th>Amount</th>
                    <th>Ending Balance</th>
                    <th>Remarks</th>
                    <th>Transaction Date</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                      <tr>
                        <td>{{$expense->account_code}}</td>
                        <td>{{number_format($expense->transaction()->amount, 2)}}</td>
                        <td>{{number_format($expense->transaction()->ending_balance, 2)}}</td>
                        <td>{{$expense->transaction()->remarks}}</td>
                        <td>{{$expense->transaction()->transaction_date}}</td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
              
            </div>
        </div>
        <script>
            window.print();
        </script>
    </body>
</html>