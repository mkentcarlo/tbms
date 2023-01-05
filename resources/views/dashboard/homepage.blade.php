@extends('dashboard.base')

@section('content')

          <div class="container-fluid">
            <div class="fade-in">
              <div class="row">
                <div class="col-sm-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="float-left">
                      <button class="btn btn-primary" data-toggle="modal" data-target="#selectOfficeModal">Selected Office: <span class="badge badge-pill badge-success">{{$selected_office->getDescription()}}</span></button>
                      <button class="btn btn-primary" data-toggle="modal" data-target="#selectDateModal">Selected Date: <span class="badge badge-pill badge-success">{{date('F', mktime(0, 0, 0, $month, 1)).' '.$year}}</span></button>
                      </div>
                      <div class="float-right">
                        <div class="dropdown">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="icon icon-2xl mt-5 mb-2 cil-playlist-add"></i> Quick add
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#addExpenseModal" data-toggle="modal">Expense</a>
                            <a class="dropdown-item" href="{{url('allotments/create')}}">Allotment</a>
                            <!-- <a class="dropdown-item" href="#">Appropriation</a> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <h4>Last 20 transactions</h4>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Account Code</th>
                            <th>Expense Class</th>
                            <th>Amount</th>
                            <th>Ending Balance</th>
                            <th>Date</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($transactions as $transaction)
                            @if(!$transaction->reference)
                              @continue
                            @endif  
                            @if($transaction->type == 'expense')
                            <tr class="text-danger">
                              <td>{{$transaction->reference->account_code}}</td>
                              <td>{{$transaction->reference->office->getDescription()}}</td>
                              <td>{{number_format($transaction->amount, 2)}}</td>
                              <td>{{number_format($transaction->ending_balance, 2)}}</td>
                              <td>{{$transaction->transaction_date}}</td>
                              <th class="text-center"><a class="btn btn-sm btn-success print" href="{{route('expense.print',['id' => $transaction->reference->id])}}"><i class="icon icon-2xl mt-5 mb-2 cil-print"></i></a></th>
                            </tr>
                            @else
                            <tr class="text-success">
                              <td>{{$transaction->id}}</td>
                              <td>{{@$transaction->reference->office->name}}</td>
                              <td>{{number_format($transaction->amount, 2)}}</td>
                              <td>{{number_format($transaction->ending_balance, 2)}}</td>
                              <td>{{$transaction->transaction_date}}</td>
                              <th></th>
                            </tr>
                            @endif
                          @endforeach
                        </tbody>
                      </table>

                    
                      <div class="modal fade" tabindex="-1" role="dialog" id="selectOfficeModal">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <form class="form-horizontal" action="{{route('dashboard.select_office')}}" method="post">
                            <div class="modal-header">
                              <h5 class="modal-title">Select Office</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                           
                             @csrf
                              <div class="mb-3">
                                <select id="category" class="form-control" required>
                                  <option value="">Select Office</option>
                                  @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="mb-3">
                                <select id="object_of_expenditures" name="object_of_expenditures" class="form-control" required>
                                </select>
                              </div>
                              <div class="mb-3">
                                <select name="office_id" id="office_id" class="form-control" required>
                                </select>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Select</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <div class="modal fade" tabindex="-1" role="dialog" id="selectDateModal">
                        <div class="modal-dialog modal-sm" role="document">
                          <div class="modal-content">
                          <form class="form-horizontal" action="{{route('dashboard.select_date')}}" method="post">
                          @csrf
                            <div class="modal-header">
                              <h5 class="modal-title">Select Date</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <select name="year" id="" class="form-control">
                                  @for($i = 2021; $i <= date('Y'); $i++)
                                  <option {{date('Y') == $i ? 'selected' : ''}}>{{$i}}</option>
                                  @endfor
                                </select>
                              </div>
                              <div class="mb-3">
                                <select name="month" id="" class="form-control">
                                  @foreach(range(1, 12) as $m)
                                    <option value="{{$m}}" {{$m == $month ? 'selected' : ''}}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Select</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>


                      <div class="modal fade" tabindex="-1" role="dialog" id="addExpenseModal">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                          <form class="form-horizontal" action="{{route('expense.store')}}"method="post">
                            <input type="hidden" id="office_allotment_balance" name="allotment_available" />
                            <input type="hidden" id="total_allotment_quarter" name="total_allotment_quarter" />
                            <input type="hidden" id="total_expenses" name="total_expenses" />
                            <input type="hidden" id="ending_balance" name="ending_balance" />
                            <input type="hidden" name="year" value="{{$year}}" />
                            <input type="hidden" name="month" value="{{$month}}" />
                            <input type="hidden" name="office_id" value="{{$selected_office->id}}" />
                            <input type="hidden" name="redirect_to" value="dashboard.index" />
                            <input type="hidden" class="form-control" name="transaction_date" id="transaction_date" value="{{date('Y-m-d')}}" />
                            @csrf
                            <div class="modal-header">
                              <h5 class="modal-title">Add Expense</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <table class="table table-bordered">
                                  <tbody>
                                    <tr>
                                      <th colspan="2">{{$selected_office->getDescription()}}</th>
                                    </tr>
                                    <tr>
                                      <td>Month/Year</td>
                                      <th>{{date('F', mktime(0, 0, 0, $month, 1)).' '.$year}}</th>
                                    </tr>
                                    <tr>
                                      <td>ID</td>
                                      <th><input type="text" name="expense_id" class="form-control" value="{{$expense_id}}" /></th>
                                    </tr>
                                    <tr>
                                      <td>Account Code</td>
                                      <th><input type="text" name="account_code" class="form-control" /></th>
                                    </tr>
                                    <tr>
                                      <td>Amount</td>
                                      <th><input type="text" name="amount" id="amount" class="form-control" /></th>
                                    </tr>
                                    <tr>
                                      <td>Payee</td>
                                      <th><textarea name="remarks" class="form-control"></textarea></th>
                                    </tr>
                                  </tbody>
                              </table>  
                              <table class="table table-stripped">
                                <tbody>
                                  <tr class="text-center">
                                    <td><small>Alloment Released Quarterly</small><br>
                                    <strong class="total_allotment_quarter">0.00</strong></td>
                                    <td><small>Total Expenses</small><br>
                                    <strong class="total_expenses">0.00</strong></td>
                                    <td><small>Alloment Available</small><br>
                                    <strong class="office_allotment_balance">0.00</strong></td>
                                    <td><small>Ending Balance</small><br>
                                    <strong class="ending_balance">0.00</strong></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Submit</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>

                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection

@section('javascript')

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>


    <script>
      var url = "{{url('offices/expense_classes/load_ooes/')}}/";
      var url_expense_classes = "{{url('offices/load_expense_classes/')}}/";

      $(document).ready(function(){

        $.ajax({
          url : "{{route('expense.get_office_allotment_balance')}}?office_id=" + "{{$selected_office->id}}&month={{$month}}&year={{$year}}",
          method: "GET",
          success: function(data){
            $("#office_allotment_balance").val(data.total_allotment_balance);
            $("#total_allotment_quarter").val(data.total_allotment_quarter);
            $("#total_expenses").val(data.total_expenses);
            $(".office_allotment_balance").text(data.total_allotment_balance.toLocaleString());
            $(".total_allotment_quarter").text(data.total_allotment_quarter.toLocaleString());
            $(".total_expenses").text(data.total_expenses.toLocaleString());
            $("#amount").attr('max', data);
          }
        });


        $('#amount').on('keyup',function(e){
          var amount = $(this).val();
          var balance =  $("#office_allotment_balance").val();
            $("#ending_balance").val(balance - amount);
            $(".ending_balance").text((balance - amount).toLocaleString());
            console.log('ss');
        });

        $('#category').change(function(){
          var id = $(this).val();
          $.ajax({
            url: url + id,
            method: "GET",
            success: function(data){
              $("select[name=object_of_expenditures]").html(data);
            } 
          });
        });

        $('#object_of_expenditures').change(function(){
          var id = $(this).val();
          $.ajax({
            url: url_expense_classes + id,
            method: "GET",
            success: function(data){
              $("#office_id").html(data);
              $('#office_id').select2({
                "theme" : 'bootstrap',
                placeholder: "Select expense class"
              });
            }
          });
        });

        $('#frmCreateExpense').on('submit', function(e){
          if($('input#ending_balance').val() < 0){
            alert('You have insufficient balance!')
            e.preventDefault();
          }
        });

      });
    </script>
@endsection
