<?php

use Illuminate\Support\Facades\Auth;

function get_month_quarter($m)
{
    $m = ceil($m/3);
    return $m.date('S',mktime(1,1,1,1,( (($m>=10)+($m>=20)+($m==0))*10 + $m%10) ));
}

function format_amount($amount)
{
    return number_format($amount,2);
}

function format_date($date){
    return date('Y-m-d', strtotime($date));
}