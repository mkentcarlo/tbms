@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <div class="card">
                  <form class="form-horizontal" action="{{route('expense.store')}}" method="post" id="frmCreateExpense">
                    <input type="hidden" name="redirect_to" value="expense.index" />
                  @csrf
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Add new expense') }}</h5></div>
                    <div class="card-body">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label" for="transaction_date">Transaction Date</label>
                          <div class="col-md-9">
                          <input type="date" class="form-control" name="transaction_date" id="transaction_date" value="{{date('Y-m-d')}}" required/>
                          <span class="help-block">Please select transaction date</span>
                          </div>
                        </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="expense_id">ID</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="expense_id" value="{{$expense_id}}" required/>
                            <span class="help-block">Auto-generated ID</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="account_code">Account Code</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="account_code" required/>
                            <span class="help-block"></span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="year">Year</label>
                            <div class="col-md-9">
                            <select name="year" id="year" class="form-control" required>
                              <option value="">-----------</option>
                              @for($i = 2021; $i <= date('Y'); $i++)
                              <option {{date('Y') == $i ? 'selected' : ''}}>{{$i}}</option>
                              @endfor

                            </select>  
                            <span class="help-block">Please select year</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="month">Month</label>
                            <div class="col-md-9">
                            <select name="month" id="month" class="form-control" required>
                              <option value="">-----------</option>
                              @foreach(range(1, 12) as $m)
                                <option value="{{$m}}" {{$m == date('m') ? 'selected' : ''}}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select month</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Office</label>
                            <div class="col-md-9">
                            <select name="office_category_id" id="category" class="form-control">
                              <option value="">-----------</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Object of Expenditure</label>
                            <div class="col-md-9">
                            <select name="object_of_expenditures" id="object_of_expenditures" class="form-control">
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Expense Class</label>
                            <div class="col-md-9">
                            <select name="office_id" id="office_id" class="form-control">
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="total_allotment_quarter">4th quarter allotment total</label>
                            <div class="col-md-9">
                            <input type="number" id="total_allotment_quarter" name="total_allotment_quarter" class="form-control" readonly/>
                            <span class="help-block">Total allotment release as of 4th quarter</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="total_expenses">Total Expenses</label>
                            <div class="col-md-9">
                            <input type="number" id="total_expenses" class="form-control" name="total_expenses" readonly/>
                            <span class="help-block">Less: Total obligation incurred</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_allotment_balance">Allotment Available</label>
                            <div class="col-md-9">
                            <input type="number" id="office_allotment_balance" name="allotment_available" class="form-control" readonly/>
                            <span class="help-block">Office allotment balance</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="amount">Amount</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="amount" id="amount" required/>
                            <span class="help-block">Please enter amount</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="ending_balance">Ending Balance</label>
                            <div class="col-md-9">
                            <input type="number" class="form-control" name="ending_balance" id="ending_balance" readonly required/>
                            <span class="help-block">Allotment available after the amount</span>
                          </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="remarks">Payee</label>
                            <div class="col-md-9">
                              <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Payee.."></textarea>
                            </div>
                          </div>
                        
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-primary" type="submit">Save</button>
                      <button class="btn btn-danger" type="reset"> Cancel</button>
                    </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')
  <script>
    var url = "{{url('offices/expense_classes/load_ooes/')}}/";
    var url_expense_classes = "{{url('offices/load_expense_classes/')}}/";
    jQuery(document).ready(function($){
     $('#office_id').change( function(e){
       var office_id = $("#office_id").val();
       var month = $("#month").val();
       var year = $("#year").val();
      $.ajax({
        url : "{{route('expense.get_office_allotment_balance')}}?office_id=" + office_id + "&month=" + month + "&year=" + year,
        method: "GET",
        success: function(data){
          $("#office_allotment_balance").val(data.total_allotment_balance);
          $("#total_allotment_quarter").val(data.total_allotment_quarter);
          $("#total_expenses").val(data.total_expenses);
          $("#amount").attr('max', data);
        }
      })
     });
     $('#amount').on('keyup',function(e){
       var amount = $(this).val();
       var balance =  $("#office_allotment_balance").val();
        $("#ending_balance").val(balance - amount);
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
