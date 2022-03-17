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
                      <h5><i class="fa fa-align-justify"></i>{{ __('Edit Allotment') }}</h5></div>
                    <div class="card-body">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="year">Year</label>
                            <div class="col-md-9">
                            <select name="year" id="year" class="form-control" readonly required>
                              <option value="">-----------</option>
                              @for($i = 2021; $i < date('Y'); $i++)
                              <option {{$i == $allotment->year ? 'selected' : ''}}>{{$i}}</option>
                              @endfor

                            </select>  
                            <span class="help-block">Please select year</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="month">Month</label>
                            <div class="col-md-9">
                            <select name="month" id="month" class="form-control" readonly required>
                              <option value="">-----------</option>
                              @foreach(range(1, 12) as $m)
                                <option value="{{$m}}" {{$m == $allotment->month ? 'selected' : ''}}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select month</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="office_id">Office</label>
                            <div class="col-md-9">
                            <select name="office_id" id="office_id" class="form-control" readonly>
                              <option value="">-----------</option>
                              @foreach($offices as $office)
                              <option value="{{$office->id}}" {{$office->id == $allotment->office_id ? 'selected' : ''}}>{{$office->name}}</option>
                              @endforeach
                            </select>  
                            <span class="help-block">Please select office</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="amount">Amount</label>
                            <div class="col-md-9">
                            <input type="number" class="form-control" name="amount" value="{{$allotment->amount}}" required/>
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
                      <button class="btn btn-danger" type="reset"> Cancel</button>
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

