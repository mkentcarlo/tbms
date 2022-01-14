@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <div class="card">
                  <form class="form-horizontal" action="{{route('expense.store')}}" method="post">
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
                            <label class="col-md-3 col-form-label" for="account_code">Account Code</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="account_code" value="{{$account_code}}" required/>
                            <span class="help-block">Auto-generated account code</span>
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
                            <select name="office_id" id="office_id" class="form-control">
                              <option value="">-----------</option>
                              @foreach($offices as $office)
                              <option value="{{$office->id}}">{{$office->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_allotment_balance">Allotment Available</label>
                            <div class="col-md-9">
                            <input type="number" id="office_allotment_balance" class="form-control" disabled/>
                            <span class="help-block">Office allotment balance</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="expense_class">Expense Class</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="expense_class" id="expense_class" required/>
                            <span class="help-block">Please enter expense class</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="amount">Amount</label>
                            <div class="col-md-9">
                            <input type="number" class="form-control" name="amount" id="amount" required/>
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
                            <label class="col-md-3 col-form-label" for="remarks">Remarks</label>
                            <div class="col-md-9">
                              <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Remarks.."></textarea>
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
    jQuery(document).ready(function($){
     $('#office_id').change( function(e){
       var office_id = $("#office_id").val();
       var month = $("#month").val();
       var year = $("#year").val();
      $.ajax({
        url : "{{route('expense.get_office_allotment_balance')}}?office_id=" + office_id + "&month=" + month + "&year=" + year,
        method: "GET",
        success: function(data){
          $("#office_allotment_balance").val(data);
          $("#amount").attr('max', data);
        }
      })
     });
     $('#amount').on('keyup',function(e){
       var amount = $(this).val();
       var balance =  $("#office_allotment_balance").val();
        $("#ending_balance").val(balance - amount);
     });
    });
  </script>
@endsection

