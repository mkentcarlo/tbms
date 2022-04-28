@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                  <form class="form-horizontal" action="{{route('office.store_main_office')}}" method="post">
                  @csrf
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Create Office') }}</h5></div>
                    <div class="card-body">
                          
                        <div class="row">
                          <div class="col-md-6 col-lg-6">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Name</label>
                            <div class="col-md-9">
                              <input class="form-control" id="name" type="text" name="name" placeholder="Enter name.." autocomplete="email"><span class="help-block">Please enter office name</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="category">Office</label>
                            <div class="col-md-9">
                            <select name="office_category_id" id="category" class="form-control">
                              <option value="">-----------</option>
                              @foreach($office_groups as $office_group)
                              <option value="{{$office_group->id}}">{{$office_group->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office group</span>
                            </div>
                          </div>
                          </div>
                        </div>
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-primary" type="submit">Save</button>
                      <a class="btn btn-danger" href="{{route('office.index')}}"> Cancel</a>
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

