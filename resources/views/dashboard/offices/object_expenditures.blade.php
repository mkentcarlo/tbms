@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Object of Expenditures') }}</h5></div>
                    <div class="card-body">
                        <a href="{{route('office.create_ooe')}}" class="btn btn-primary">Add new</a> <br><br>
                        <table class="table table-striped" id="tableObejectExpenditures">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Office</th>
                              <th style="width: 150px"></th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($object_expenditures as $ooe)
                            <tr>
                              <td>{{$ooe->name}}</td>
                              <td>{{$ooe->parent->name}}</td>
                              <td><a class="btn btn-primary btn-sm" href="{{route('office.edit_ooe',['id' => $ooe->id])}}">Edit</a> <a class="btn btn-danger btn-sm delete" href="{{route('office.delete_ooe',['id' => $ooe->id])}}">Delete</a></td>
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
    $('#tableObejectExpenditures').DataTable();
    $(document).on('click', '.delete', function(e){
      if(!confirm('Are you sure you want to delete this?')){
        e.preventDefault();
      }
    });
  });
</script>
@endsection

