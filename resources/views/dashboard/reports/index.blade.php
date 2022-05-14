@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <h5><i class="fa fa-align-justify"></i>{{ __('Reports') }}</h5></div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="">Year</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="">Select Year</option>
                                        @for($i = 2021; $i <= date('Y'); $i++)
                                            <option {{date('Y') == $i ? 'selected' : ''}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Quarter</label>
                                    <select name="quarter" id="quarter" class="form-control">
                                        <option value="">Entire year</option>
                                        <option value="1" {{@$filters['quarter'] == 1 ? 'selected' : ''}}>1st</option>
                                        <option value="2" {{@$filters['quarter'] == 2 ? 'selected' : ''}}>2nd</option>
                                        <option value="3" {{@$filters['quarter'] == 3 ? 'selected' : ''}}>3rd</option>
                                        <option value="4" {{@$filters['quarter'] == 4 ? 'selected' : ''}}>4th</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Month</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="">Entire quarter</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{$m}}" {{@$filters['month'] == $m ? 'selected' : ''}}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">From</label>
                                    <input type="date" class="form-control" name="date_from" readonly value="{{@$filters['date_from']}}" />
                                </div>
                                <div class="col-md-3">
                                    <label for="">To</label>
                                    <input type="date" class="form-control" name="date_to" readonly {{@$filters['date_to']}} />
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary mb-2" value="yes" name="generate_report">Generate Report</button>
                            </div>
                        </form>
                        @if(isset($filters['generate_report']))
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="2">Office Group</th>
                                        <th>Expense Class</th>
                                        <th>Appropriation</th>
                                        <th>Allotment</th>
                                        <th>Obligation Incurred</th>
                                        <th>Unobligated Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $overall_total_allotment_total = 0;
                                    $overall_total_expenses_total = 0;
                                    $overall_total_balance_total = 0;
                                    $overall_total_appropriation = 0;
                                    foreach($office_groups as $office_group): ?>
                                        <tr>
                                            <th><?php echo $office_group->name; ?></th>
                                            <th  colspan="6"></th>
                                        </tr>
                                        <?php foreach($office_group->children as $office): 
                                            $total_allotment_total = 0;
                                            $total_expenses_total = 0;
                                            $total_balance_total = 0;
                                            $total_appropriation = 0;
                                            ?>
                                            <tr>
                                                <th colspan="2" class="text-right"><?php echo $office->name; ?></th>
                                                <th colspan="5" class="text-right"></th>
                                            </tr>
                                            <?php foreach($office->getUniqueDescriptions() as $officebydescription): 
                                                $d_total_allotment_total = 0;
                                                $d_total_expenses_total = 0;
                                                $d_total_balance_total = 0;
                                                $d_total_appropriation = 0;
                                                ?>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <th><?php echo $officebydescription->description; ?></th>
                                                    <th colspan="4"></th>
                                                </tr>
                                                <?php foreach($officebydescription->getExpenseClassesByDescription($office->id) as $expense_class): 
                                                    $allotment_total = $expense_class->getAllotmentTotal(@$filters['date_from'], @$filters['date_to']);
                                                    $expenses_total = $expense_class->getExpensesTotal(@$filters['date_from'], @$filters['date_to']);
                                                    $appropriation = $expense_class->getAppropriation(@$filters['year']);
                                                    $balance = $allotment_total - $expenses_total;

                                                    $total_allotment_total+=$allotment_total;
                                                    $total_expenses_total+=$expenses_total;
                                                    $total_balance_total+=$balance;
                                                    $total_appropriation+=$appropriation;

                                                    $d_total_allotment_total+=$allotment_total;
                                                    $d_total_expenses_total+=$expenses_total;
                                                    $d_total_balance_total+=$balance;
                                                    $d_total_appropriation+=$appropriation;

                                                    $overall_total_allotment_total+=$allotment_total;
                                                    $overall_total_expenses_total+=$expenses_total;
                                                    $overall_total_balance_total+=$balance;
                                                    $overall_total_appropriation+=$appropriation;

                                                    
                                                    ?>
                                                    <tr>
                                                        <td colspan="2"></td>
                                                        <td><?php echo $expense_class->name; ?></td>
                                                        <td><?php echo format_amount($appropriation) ?></td>
                                                        <td><?php echo format_amount($allotment_total) ?></td>
                                                        <td><?php echo format_amount($expenses_total) ?></td>
                                                        <td><?php echo format_amount($balance) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <th><?php echo format_amount($d_total_appropriation) ?></th>
                                                        <th><?php echo format_amount($d_total_allotment_total) ?></th>
                                                        <th><?php echo format_amount($d_total_expenses_total) ?></th>
                                                        <th><?php echo format_amount($d_total_balance_total) ?></th>
                                                    </tr>

                                            <?php endforeach; ?>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th>{{format_amount($total_appropriation)}}</th>
                                                <th>{{format_amount($total_allotment_total)}}</th>
                                                <th>{{format_amount($total_expenses_total)}}</th>
                                                <th>{{format_amount($total_balance_total)}}</th>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th>{{format_amount($overall_total_allotment_total)}}</th>
                                        <th>{{format_amount($overall_total_expenses_total)}}</th>
                                        <th>{{format_amount($overall_total_balance_total)}}</th>
                                        <th>{{format_amount($overall_total_appropriation)}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('css')
<link href="{{ asset('css/bootstrap-datatable.css') }}" rel="stylesheet">
@endsection


@section('javascript')
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/datatable-bootstrap.js') }}"></script>

<script>
    $(document).ready(function(){
        function format_date(d){
            var day = ("0" + d.getDate()).slice(-2);
            var month = ("0" + (d.getMonth() + 1)).slice(-2);
            var today = d.getFullYear()+"-"+(month)+"-"+(day);
            return today;
        }
        $("select#year").change(function(){
            var $this_val = $(this).val();
            var d_from = new Date($this_val, 0, 1);
            var d_to = new Date($this_val, 12, 0);

            $('input[name=date_from]').val(format_date(d_from));
            $('input[name=date_to]').val(format_date(d_to));
        });

        $("select#quarter").change(function(){
            var year =  $("select#year").val();
            var $this_val = $(this).val();
            var end = parseInt($this_val) * 3;
            var start = end - 2;

            var d_from = new Date(year, (start - 1), 1);
            var d_to = new Date(year, end, 0);

            $('input[name=date_from]').val(format_date(d_from));
            $('input[name=date_to]').val(format_date(d_to));
        });

        $("select#quarter").change(function(){
            var year =  $("select#year").val();
            var $this_val = $(this).val();
            var end = parseInt($this_val) * 3;
            var start = end - 2;

            var d_from = new Date(year, (start - 1), 1);
            var d_to = new Date(year, end, 0);

            $('input[name=date_from]').val(format_date(d_from));
            $('input[name=date_to]').val(format_date(d_to));
        });

        $("select#month").change(function(){
            var year =  $("select#year").val();
            var month = $(this).val();

            var d_from = new Date(year, (month - 1), 1);
            var d_to = new Date(year, month, 0);

            $('input[name=date_from]').val(format_date(d_from));
            $('input[name=date_to]').val(format_date(d_to));
        });
    });
</script>
@endsection

