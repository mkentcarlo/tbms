@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Allotments / Appropriations') }}</h5></div>
                    <div class="card-body">
                        <a href="{{route('allotment.create')}}" class="btn btn-primary"><i class="c-icon cil-plus float-left mr-2"></i> Create</a>
                        <div class="float-right w-50 text-right mb-3">
                          <form action="" method="GET" id="frmFilter">
                          <select class="form-control d-inline-block" style="max-width: 300px" id="office_id" name="office_id">
                            <option value="">Office</option>
                            @foreach($offices as $office)
                            <option value="{{$office->id}}" {{@$_GET['office_id'] == $office->id ? 'selected' : ''}}>{{$office->name}}</option>
                            @endforeach
                          </select>
                          </form>
                        </div>
                        <div class="clearfix"></div>
                        <table class="table table-responsive-sm table-striped" id="tableAllotment">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Office Group</th>
                            <th>Office</th>
                            <th>Expense Class</th>
                            <th>Amount</th>
                            <th>Remarks</th>
                            <th>Transaction Date</th>
                            <th style="width: 150px"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($allotments as $allotment)
                            <tr>
                              <td>{{$allotment->id}}</td>
                              <td>{{$allotment->year}}</td>
                              <td>{{($allotment->month!='' ? date('F', mktime(0, 0, 0, $allotment->month, 1)) : '-')}}</td>
                              <td>{{$allotment->expense_class->category->parent->name}}</td>
                              <td>{{$allotment->expense_class->category->name}}</td>
                              <td>{{$allotment->expense_class->name}}</td>
                              <td>{{number_format($allotment->amount, 2)}}</td>
                              <td>{{$allotment->remarks}}</td>
                              <td>{{$allotment->created_at}}</td>
                              <td><a class="btn btn-sm btn-primary" href="{{route('allotment.edit',['id' => $allotment->id])}}">Edit</a>
                            <a class="btn btn-sm btn-danger delete" href="{{route('allotment.delete',['id' => $allotment->id])}}">Delete</a></td>
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
    $('#tableAllotment').DataTable();
    $(document).on('click', '.delete', function(e){
      if(!confirm('Are you sure you want to delete this?')){
        e.preventDefault();
      }
    });
    $("#office_id").change(function(){
      $("#frmFilter").submit();
    });
  });

</script>
@endsection

