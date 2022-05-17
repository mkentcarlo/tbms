@extends('dashboard.base')

@section('content')

          <div class="container-fluid">
            <div class="fade-in">
              <div class="row">
                <div class="col-sm-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="">Select Office</label>
                        <select name="" id="" class="form-control">
                          <option value="">All Offices</option>
                        </select>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-6">
                          <label for="">Select Month</label>
                          <select name="" id="" class="form-control">
                            <option value="">Entire Year</option>
                          </select>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Select Day</label>
                          <select name="" id="" class="form-control">
                            <option value="">Entire Month</option>
                          </select>
                        </div>
                      </div>
                      <div class="c-callout c-callout-success"><small class="text-muted">Total Allotment</small>
                        <div class="text-value-lg">Php 98,123.00</div>
                      </div>
                      <div class="c-callout c-callout-danger"><small class="text-muted">Total Expenses</small>
                        <div class="text-value-lg">9,123</div>
                      </div>
                      <div class="c-callout c-callout-info"><small class="text-muted">Remaining Balance</small>
                        <div class="text-value-lg">9,123</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-9">
                  <div class="card">
                    <div class="card-header">
                      <div class="float-right">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#expenseModal">New Expense</button>
                        <button class="btn btn-success">New Allotment</button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Transaction No.</th>
                            <th>Office</th>
                            <th>Amount</th>
                            <th>Ending Balance</th>
                            <th>Transaction date</th>
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
                              <td>{{$transaction->reference->office->name}}</td>
                              <td>{{number_format($transaction->amount, 2)}}</td>
                              <td>{{number_format($transaction->ending_balance, 2)}}</td>
                              <td>{{$transaction->transaction_date}}</td>
                            </tr>
                            @else
                            <tr class="text-success">
                              <td>{{$transaction->id}}</td>
                              <td>{{$transaction->reference->office->name}}</td>
                              <td>{{number_format($transaction->amount, 2)}}</td>
                              <td>{{number_format($transaction->ending_balance, 2)}}</td>
                              <td>{{$transaction->transaction_date}}</td>
                            </tr>
                            @endif
                          @endforeach
                        </tbody>
                      </table>

                      <!-- Modal -->
                      <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="expenseModalLabel">Quick add expense</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body p-4">
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
                            <input type="text" class="form-control" name="account_code"  required/>
                            <span class="help-block">Auto-generated account code</span>
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
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-danger">Submit</button>
                            </div>
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
@endsection
