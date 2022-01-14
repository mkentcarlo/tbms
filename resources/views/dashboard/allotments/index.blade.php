@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Allotments') }}</h5></div>
                    <div class="card-body">
                        <a href="{{route('allotment.create')}}" class="btn btn-primary btn-sm mb-2"><i class="c-icon cil-plus float-left mr-2"></i> Create allotment</a>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Office</th>
                            <th>Amount</th>
                            <th>Ending Balance</th>
                            <th>Remarks</th>
                            <th>Transaction Date</th>
                            <th style="width: 150px"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($allotments as $allotment)
                            <tr>
                              <td>{{$allotment->id}}</td>
                              <td>{{$allotment->office->name}}</td>
                              <td>{{number_format($allotment->amount, 2)}}</td>
                              <td>{{number_format($allotment->transaction->ending_balance, 2)}}</td>
                              <td>{{$allotment->remarks}}</td>
                              <td>{{$allotment->transaction->transaction_date}}</td>
                              <td><a class="btn btn-sm btn-primary" href="{{route('allotment.edit',['id' => $allotment->id])}}">Edit</a>
                            <a class="btn btn-sm btn-danger" href="{{route('allotment.delete',['id' => $allotment->id])}}">Delete</a></td>
                            </tr>
                          @endforeach
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

