@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="card">
                  <form class="form-horizontal" action="{{route('office.update_office_group', ['id' => $office_group->id])}}" method="post">
                  @csrf
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Edit Office Group') }}</h5></div>
                    <div class="card-body">
                        <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Name</label>
                        <div class="col-md-9">
                            <input class="form-control" id="name" type="text" value="{{$office_group->name}}" name="name" placeholder="Enter name.." autocomplete="email"><span class="help-block">Please enter office name</span>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-primary" type="submit">Save</button>
                      <a class="btn btn-danger" href="{{route('office.office_groups')}}"> Cancel</a>
                    </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection

