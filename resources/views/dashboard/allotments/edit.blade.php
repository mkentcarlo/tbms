@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <div class="card">
                  <form class="form-horizontal" action="{{route('allotment.update',['id' => $allotment->id])}}" method="post">
                  @csrf
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Edit') }}</h5></div>
                    <div class="card-body">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="year">Year</label>
                            <div class="col-md-9">
                            <select name="year" id="year" class="form-control" required>
                              <option value="">-----------</option>
                              @for($i = 2021; $i <= date('Y'); $i++)
                              <option {{$i == $allotment->year ? 'selected' : ''}}>{{$i}}</option>
                              @endfor

                            </select>  
                            <span class="help-block">Please select year</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="month">Month</label>
                            <div class="col-md-9">
                            <select name="month" id="month" class="form-control">
                              <option value="">-----------</option>
                              @foreach(range(1, 12) as $m)
                                <option value="{{$m}}" {{$m == $allotment->month ? 'selected' : ''}}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select month</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Office group</label>
                            <div class="col-md-9">
                            <select name="office_category_id" id="category" class="form-control">
                              <option value="">-----------</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}" {{$category->id == $allotment->expense_class->category->parent->id ? 'selected' : ''}}>{{$category->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office group</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Select office</label>
                            <div class="col-md-9">
                            <select name="object_of_expenditures" id="object_of_expenditures" class="form-control">
                              @foreach($object_expenditures as $object_expenditure)
                                <option value="{{$object_expenditure->id}}" {{$object_expenditure->id == $allotment->expense_class->category->id ? 'selected' : ''}}>{{$object_expenditure->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Expense Class</label>
                            <div class="col-md-9">
                            <select name="office_id" id="office_id" class="form-control">
                              @foreach($expense_classes as $expense_class)
                                <option value="{{$expense_class->id}}">{{$expense_class->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="amount">Amount</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="amount" value="{{$allotment->amount}}" required/>
                            <span class="help-block">Please enter amount</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="remarks">Remarks</label>
                            <div class="col-md-9">
                              <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Remarks..">{{$allotment->remarks}}</textarea>
                            </div>
                          </div>
                        
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-primary" type="submit">Save</button>
                      <a class="btn btn-danger" href="{{route('allotment.index')}}"> Cancel</a>
                    </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')
<script>
  var url = "{{url('offices/expense_classes/load_ooes/')}}/";
  var url_expense_classes = "{{url('offices/load_expense_classes/')}}/";
  $(document).ready(function(){
    $('#category').change(function(){
      var id = $(this).val();
      $.ajax({
        url: url + id,
        method: "GET",
        success: function(data){
          $("select[name=object_of_expenditures]").html(data);
        }
      });
    });jj,iiu
    $('#object_of_expenditures').change(function(){
      var id = $(this).val();
      $.ajax({
        url: url_expense_classes + id,
        method: "GET",
        success: function(data){
          $("#office_id").html(data);
          $('#office_id').select2({
            "theme" : 'bootstrap',
            placeholder: "Select expense class"
          });
        }
      });
    });
  });
</script>
@endsection

