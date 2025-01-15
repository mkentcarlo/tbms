@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-md-6">
              <form method="POST" action="{{route('users.store')}}">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Create') }}</div>
                    <div class="card-body">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Name') }}" value="{{old('name')}}" name="name" required />
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input class="form-control" type="text" value="{{old('email')}}" placeholder="{{ __('E-Mail Address') }}" name="email" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cil-lock-locked"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="password" placeholder="{{ __('Password') }}" name="password" required>
                            </div>
                        
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-success" type="submit">{{ __('Save') }}</button>
                      <a href="{{ route('users.index') }}" class="btn btn-primary">{{ __('Back') }}</a> 
                    </div>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('javascript')

@endsection