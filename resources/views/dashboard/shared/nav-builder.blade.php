<?php
/*
    $data = $menuel['elements']
*/

if(!function_exists('renderDropdown')){
    function renderDropdown($data){
        if(array_key_exists('slug', $data) && $data['slug'] === 'dropdown'){
            echo '<li class="c-sidebar-nav-dropdown">';
            echo '<a class="c-sidebar-nav-dropdown-toggle" href="#">';
            if($data['hasIcon'] === true && $data['iconType'] === 'coreui'){
                echo '<i class="' . $data['icon'] . ' c-sidebar-nav-icon"></i>';    
            }
            echo $data['name'] . '</a>';
            echo '<ul class="c-sidebar-nav-dropdown-items">';
            renderDropdown( $data['elements'] );
            echo '</ul></li>';
        }else{
            for($i = 0; $i < count($data); $i++){
                if( $data[$i]['slug'] === 'link' ){
                    echo '<li class="c-sidebar-nav-item">';
                    echo '<a class="c-sidebar-nav-link" href="' . url($data[$i]['href']) . '">';
                    echo '<span class="c-sidebar-nav-icon"></span>' . $data[$i]['name'] . '</a></li>';
                }elseif( $data[$i]['slug'] === 'dropdown' ){
                    renderDropdown( $data[$i] );
                }
            }
        }
    }
}
?>


        <div class="c-sidebar-brand">
            <h3>TBMS</h3>
        </div>
        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{url('/')}}">Dashboard</a>
            </li>
            <li class="c-sidebar-nav-dropdown">
                <a class="c-sidebar-nav-dropdown-toggle" href="javascript:;">Manage Offices</a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{route('office.office_groups')}}"><span class="c-sidebar-nav-icon"></span>Office groups</a></li>
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{route('office.index')}}"><span class="c-sidebar-nav-icon"></span>Offices</a></li>
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{route('office.expense_classes')}}"><span class="c-sidebar-nav-icon"></span>Expense Classes</a></li>
                </ul>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{route('allotment.index')}}">Manage Allotments & Appropriations</a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{route('expense.index')}}">Manage Expenses</a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{route('users.index')}}">Manage Users</a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{route('reports.index')}}">Reports</a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{url('logout')}}">Logout</a>
            </li>
        </ul>
        <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
    </div>