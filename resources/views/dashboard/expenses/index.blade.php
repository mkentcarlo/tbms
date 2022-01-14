@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Expenses') }}</h5></div>
                    <div class="card-body">
                        <a href="{{route('allotment.create')}}" class="btn btn-primary btn-sm mb-2"><i class="c-icon cil-plus float-left mr-2"></i> New Expense</a>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Account Code</th>
                            <th>Office</th>
                            <th>Budget</th>
                            <th>Amount</th>
                            <th>Ending Balance</th>
                            <th>Remarks</th>
                            <th>Transaction Date</th>
                            <th style="width: 150px"></th>
                          </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection

