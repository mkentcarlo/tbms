@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Reports') }}</h5></div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="">Year</label>
                                <select name="" id="" class="form-control">
                                    <option value="">Select Year</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Quarter</label>
                                <select name="" id="" class="form-control">
                                    <option value="">1st</option>
                                    <option value="">2nd</option>
                                    <option value="">3rd</option>
                                    <option value="">4th</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Month</label>
                                <select name="" id="" class="form-control">
                                    <option value="">Select Month</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">From</label>
                                <input type="date" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label for="">From</label>
                                <input type="date" class="form-control" />
                            </div>
                        </div>
                        <button class="btn btn-primary mb-2">Print</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Office Group</th>
                                    <th>Office</th>
                                    <th>Expense Class</th>
                                    <th>Allotment</th>
                                    <th>Balance</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('css')
<link href="{{ asset('css/bootstrap-datatable.css') }}" rel="stylesheet">
@endsection


@section('javascript')
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/datatable-bootstrap.js') }}"></script>
@endsection

