@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Expense Classes') }}</h5></div>
                    <div class="card-body">
                        <a href="{{route('office.create')}}" class="btn btn-primary btn-sm mb-2"><i class="c-icon cil-plus float-left mr-2"></i> Add new expense class</a>
                        <table class="table table-responsive-sm table-striped" id="tableExpenseClasses">
                        <thead>
                          <tr>
                            <th>Expense Class</th>
                            <th>Office</th>
                            <th>Object of Expenditures</th>
                            <th>Date Added</th>
                            <th style="width: 150px;"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($expense_classes as $expense_class)
                          <tr>
                            <td>{{$expense_class->name}}</td>
                            <td>{{$expense_class->category->parent->name}}</td>
                            <td>{{$expense_class->category->name}}</td>
                            <td>{{$expense_class->created_at}}</td>
                            <td><a class="btn btn-sm btn-primary" href="{{route('office.edit',['id' => $expense_class->id])}}"><i class="c-icon cil-pencil"></i></a>
                            <a class="btn btn-sm btn-danger delete" href="{{route('office.delete',['id' => $expense_class->id])}}"><i class="c-icon cil-trash"></i></a></td>
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

@section('css')
<link href="{{ asset('css/bootstrap-datatable.css') }}" rel="stylesheet">
@endsection

@section('javascript')
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/datatable-bootstrap.js') }}"></script>
<script>
  $(document).ready(function(){
    $('#tableExpenseClasses').DataTable();
    $(document).on('click', '.delete', function(e){
      if(!confirm('Are you sure you want to delete this?')){
        e.preventDefault();
      }
    });
  });
</script>
@endsection

