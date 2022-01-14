@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <div class="card">
                  <form class="form-horizontal" action="{{route('office.update',['id' => $office->id])}}" method="post">
                  @csrf
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Edit office') }}</h5></div>
                    <div class="card-body">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Office name</label>
                            <div class="col-md-9">
                              <input class="form-control" id="name" value="{{$office->name}}" type="text" name="name" placeholder="Enter office name.." autocomplete="email"><span class="help-block">Please enter office name</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="category">Office category</label>
                            <div class="col-md-9">
                            <select name="office_category_id" id="category" class="form-control">
                              <option value="">-----------</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}" {{$category->id == $office->office_category_id ? 'selected' : ''}}>{{$category->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office category</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="description">Description</label>
                            <div class="col-md-9">
                              <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description..">{{$office->description}}</textarea>
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

