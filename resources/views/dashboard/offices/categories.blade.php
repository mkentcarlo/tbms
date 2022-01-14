@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Office categories') }}</h5></div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Category name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            
                            <td>
                              <form method="POST" action="{{route('office.categories.store')}}">
                                @csrf
                                <div class="row">
                                  <div class="col-md-8">
                                    <input type="text" class="form-control" name="name">
                                  </div>
                                  <div class="col-md-4">
                                  <button class="btn btn-primary">Add</button>
                                  </div>
                                </div>
                              </form>
                            </td>
                          </tr>
                          @foreach($categories as $category)
                            <tr>
                              <td>
                                <form method="POST" action="{{route('office.categories.store')}}">
                                @csrf
                                  <div class="row">
                                    <div class="col-md-8">
                                      <input type="text" class="form-control" value="{{$category->name}}" name="name">
                                      <input type="hidden" name="id" value="{{$category->id}}">
                                    </div>
                                    <div class="col-md-4">
                                    <button class="btn btn-primary">Save</button> <a class="btn btn-danger delete" href="{{route('office.categories.delete',['id' => $category->id])}}">Delete</a>
                                    </div>
                                  </div>
                                </form>
                              </td>
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
    $('a.delete').click(function(e){
      var confirm_delete = confirm('Are you sure you want to delete?');
      if(!confirm_delete){
        e.preventDefault();
      }
    })
  });
</script>

@endsection

