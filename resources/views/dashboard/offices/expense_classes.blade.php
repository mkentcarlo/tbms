@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Offices') }}</h5></div>
                    <div class="card-body">
                        <a href="{{route('office.create')}}" class="btn btn-primary btn-sm mb-2"><i class="c-icon cil-plus float-left mr-2"></i> Add new expense class</a>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Office Name</th>
                            <th>Description</th>
                            <th>Office Category</th>
                            <th>Date Added</th>
                            <th style="width: 150px;"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($expense_classes as $expense_class)
                          <tr>
                            <td>{{$expense_class->name}}</td>
                            <td>{{$expense_class->description}}</td>
                            <td>{{$expense_class->category->name}}</td>
                            <td>{{$expense_class->created_at}}</td>
                            <td><a class="btn btn-sm btn-primary" href="{{route('office.edit',['id' => $expense_class->id])}}">Edit</a>
                            <a class="btn btn-sm btn-danger" href="{{route('office.delete',['id' => $expense_class->id])}}">Delete</a></td>
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

