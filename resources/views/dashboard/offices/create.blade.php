@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <div class="card">
                  <form class="form-horizontal" action="{{route('office.store')}}" method="post">
                  @csrf
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Create Expense Class') }}</h5></div>
                    <div class="card-body">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Expense Class Name</label>
                            <div class="col-md-9">
                              <input class="form-control" id="name" type="text" name="name" placeholder="Enter name.." autocomplete="email"><span class="help-block">Please enter office name</span>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="category">Select Office</label>
                            <div class="col-md-9">
                            <select name="office_category_id" id="category" class="form-control">
                              <option value="">-----------</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="category">Object of Expenditures</label>
                            <div class="col-md-9">
                            <select name="object_of_expenditures" id="category" class="form-control">
                            </select>  
                            <span class="help-block">Please select Object of Expenditures</span>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Tag</label>
                            <div class="col-md-9">
                              <input class="form-control" id="description" type="text" name="description" placeholder="Enter tag..">
                            </div>
                          </div>
                        
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-primary" type="submit">Save</button>
                      <a class="btn btn-danger" href="{{route('office.expense_classes')}}"> Cancel</a>
                    </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')
<script src="{{ asset('js/typeahead.js') }}"></script>
<script>
  var url = "{{url('offices/expense_classes/load_ooes/')}}/";
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
    });

    var path = "{{ route('office.load_tags') }}";
    $('input#description').typeahead({
        source:  function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        }
    });
  })
</script>
@endsection

