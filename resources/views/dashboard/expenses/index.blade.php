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

                        <div class="float-left w-50">
                        <a href="{{route('expense.create')}}" class="btn btn-primary btn-sm mb-2"><i class="c-icon cil-plus float-left mr-2"></i> New Expense</a>
                        </div>
                        <div class="float-right w-50 text-right mb-3">
                          <form action="" method="GET" id="frmFilter">
                          <input type="text" class="form-control d-inline-block" style="max-width: 300px" placeholder="Search by account code / budget" value="{{@$_GET['s']}}" name="s" />
                          <select class="form-control d-inline-block" style="max-width: 200px" id="office_id" name="office_id">
                            <option value="">Office</option>
                            @foreach($offices as $office)
                            <option value="{{$office->id}}" {{@$_GET['office_id'] == $office->id ? 'selected' : ''}}>{{$office->name}}</option>
                            @endforeach
                          </select>
                          <select class="form-control d-inline-block" style="max-width: 160px" name="m" id="month">
                            <option value="">-----</option>
                            @php($sm = isset($_GET['m']) ? $_GET['m'] : date('m'))
                            @foreach(range(1, 12) as $m)
                                <option value="{{$m}}" {{$m == $sm ? 'selected' : ''}}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                              @endforeach
                          </select>
                          <select class="form-control d-inline-block" style="max-width: 100px" name="y" id="year">
                           @php($sy = isset($_GET['y']) ? $_GET['y'] : date('Y'))
                            @for($i = 2021; $i <= date('Y'); $i++)
                            <option {{$sy == $i ? 'selected' : ''}}>{{$i}}</option>
                            @endfor
                          </select>
                          </form>
                        </div>
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
                            <th style="width: 170px"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                              <tr>
                                <td>{{$expense->account_code}}</td>
                                <td>{{$expense->office->name}}</td>
                                <td>{{$expense->expense_class}}</td>
                                <td>{{number_format($expense->transaction()->amount, 2)}}</td>
                                <td>{{number_format($expense->transaction()->ending_balance, 2)}}</td>
                                <td>{{$expense->transaction()->remarks}}</td>
                                <td>{{$expense->transaction()->transaction_date}}</td>
                                <td><a class="btn btn-sm btn-primary" href="{{route('expense.edit',['id' => $expense->id])}}">Edit</a>
                                <a class="btn btn-sm btn-danger delete" href="{{route('expense.delete',['id' => $expense->id])}}">Delete</a>
                              <a class="btn btn-sm btn-success print" href="{{route('expense.print',['id' => $expense->id])}}">Print</a></td>
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
<script>
  $(document).ready(function(){
    $("#office_id, #month, #year").change(function(){
      $("input[name=s]").val('');
      $("#frmFilter").submit();
    });
    $(document).on('click', '.delete', function(e){
      if(!confirm('Are you sure you want to delete this?')){
        e.preventDefault();
      }
    });
    $('.print').click(function(e){
      e.preventDefault();
      var link = $(this).attr('href');
      window.open(link, 'Print haha', 'toolbar=no,location=no,menubar=no,scrollbars=no,resizable=no,titlebar=no');
    })
  })
</script>
@endsection

