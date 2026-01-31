@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Offices') }}</h5></div>
                    <div class="card-body">
                        <div class="row">
                          <div class="col-md-6 col-lg-6 col-xl-6">
                          <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Office name</th>
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
                          @foreach($offices as $office)
                            <tr>
                              <td>
                                <form method="POST" action="{{route('office.categories.store')}}">
                                @csrf
                                  <div class="row">
                                    <div class="col-md-8">
                                      <input type="text" class="form-control" value="{{$office->name}}" name="name">
                                      <input type="hidden" name="id" value="{{$office->id}}">
                                    </div>
                                    <div class="col-md-4">
                                    <button class="btn btn-primary">Save</button> <a class="btn btn-danger delete" href="{{route('office.categories.delete',['id' => $office->id])}}">Delete</a>
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

