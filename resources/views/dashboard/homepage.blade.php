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
                        <button class="btn btn-danger">New Expense</button>
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
                      </table>
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
