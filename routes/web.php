<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//reset password
// Route::get('changepassword', function() {
//     $user = App\Models\User::where('name', 'admin')->first();
//     $user->password = Hash::make('admin123');
//     $user->save();
  
//     echo 'Password changed successfully.';
// });

Route::get('backup', function() {
    $filename = "tbms_".strtotime(now()).".sql";
    $command = "C:/xampp/mysql/bin/mysqldump --user=".env('DB_USERNAME')." --password=".env('DB_PASSWORD')." --host=".env('DB_HOST')." ".env('DB_DATABASE') . " > ".env('BACKUP_DIR').$filename;
    exec($command);
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['get.menu']], function () {
    

    Route::group(['middleware' => ['role:user', 'auth']], function () {
        Route::get('/colors', function () {     return view('dashboard.colors'); });
        Route::get('/typography', function () { return view('dashboard.typography'); });
        Route::get('/charts', function () {     return view('dashboard.charts'); });
        Route::get('/widgets', function () {    return view('dashboard.widgets'); });
        Route::get('/404', function () {        return view('dashboard.404'); });
        Route::get('/500', function () {        return view('dashboard.500'); });
        Route::prefix('base')->group(function () {  
            Route::get('/breadcrumb', function(){   return view('dashboard.base.breadcrumb'); });
            Route::get('/cards', function(){        return view('dashboard.base.cards'); });
            Route::get('/carousel', function(){     return view('dashboard.base.carousel'); });
            Route::get('/collapse', function(){     return view('dashboard.base.collapse'); });

            Route::get('/forms', function(){        return view('dashboard.base.forms'); });
            Route::get('/jumbotron', function(){    return view('dashboard.base.jumbotron'); });
            Route::get('/list-group', function(){   return view('dashboard.base.list-group'); });
            Route::get('/navs', function(){         return view('dashboard.base.navs'); });

            Route::get('/pagination', function(){   return view('dashboard.base.pagination'); });
            Route::get('/popovers', function(){     return view('dashboard.base.popovers'); });
            Route::get('/progress', function(){     return view('dashboard.base.progress'); });
            Route::get('/scrollspy', function(){    return view('dashboard.base.scrollspy'); });

            Route::get('/switches', function(){     return view('dashboard.base.switches'); });
            Route::get('/tables', function () {     return view('dashboard.base.tables'); });
            Route::get('/tabs', function () {       return view('dashboard.base.tabs'); });
            Route::get('/tooltips', function () {   return view('dashboard.base.tooltips'); });
        });
        Route::prefix('buttons')->group(function () {  
            Route::get('/buttons', function(){          return view('dashboard.buttons.buttons'); });
            Route::get('/button-group', function(){     return view('dashboard.buttons.button-group'); });
            Route::get('/dropdowns', function(){        return view('dashboard.buttons.dropdowns'); });
            Route::get('/brand-buttons', function(){    return view('dashboard.buttons.brand-buttons'); });
        });
        Route::prefix('icon')->group(function () {  // word: "icons" - not working as part of adress
            Route::get('/coreui-icons', function(){         return view('dashboard.icons.coreu
                i-icons'); });
            Route::get('/flags', function(){                return view('dashboard.icons.flags'); });
            Route::get('/brands', function(){               return view('dashboard.icons.brands'); });
        });
        Route::prefix('notifications')->group(function () {  
            Route::get('/alerts', function(){   return view('dashboard.notifications.alerts'); });
            Route::get('/badge', function(){    return view('dashboard.notifications.badge'); });
            Route::get('/modals', function(){   return view('dashboard.notifications.modals'); });
        });
        Route::resource('notes', 'NotesController');
    });
    Auth::routes();

    Route::resource('resource/{table}/resource', 'ResourceController')->names([
        'index'     => 'resource.index',
        'create'    => 'resource.create',
        'store'     => 'resource.store',
        'show'      => 'resource.show',
        'edit'      => 'resource.edit',
        'update'    => 'resource.update',
        'destroy'   => 'resource.destroy'
    ]);

    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('bread',  'BreadController');   //create BREAD (resource)
        Route::resource('users',        'UsersController')->except( ['create', 'store'] );

        Route::get('offices',        'admin\OfficesController@index')->name('office.index');
        Route::post('offices/store',        'admin\OfficesController@store')->name('office.store');
        Route::post('offices/store_ooe',        'admin\OfficesController@store_ooe')->name('office.store_ooe');
        Route::post('offices/update/{id}',        'admin\OfficesController@update')->name('office.update');
        Route::post('offices/update_ooe/{id}',        'admin\OfficesController@update_ooe')->name('office.update_ooe');
        Route::get('offices/create',        'admin\OfficesController@create')->name('office.create');
        Route::get('offices/create_ooe',        'admin\OfficesController@create_ooe')->name('office.create_ooe');
        
        Route::get('offices/edit/{id}',        'admin\OfficesController@edit')->name('office.edit');
        Route::get('offices/edit_ooe/{id}',        'admin\OfficesController@edit_ooe')->name('office.edit_ooe');
        Route::get('offices/delete/{id}',        'admin\OfficesController@delete')->name('office.delete');
        Route::get('offices/delete_ooe/{id}',        'admin\OfficesController@delete_ooe')->name('office.delete_ooe');
        Route::get('offices/categories',        'admin\OfficesController@categories')->name('office.categories');
        Route::post('offices/categories_store',        'admin\OfficesController@categories_store')->name('office.categories.store');
        Route::get('offices/categories_delete/{id}',        'admin\OfficesController@categories_delete')->name('office.categories.delete');
        Route::get('offices/expense_classes',        'admin\OfficesController@expense_classes')->name('office.expense_classes');
        Route::get('offices/object_expenditures',        'admin\OfficesController@object_expenditures')->name('office.object_expenditures');

        // These are routes for office groups
        Route::get('offices/office_groups',        'admin\OfficesController@office_groups')->name('office.office_groups');
        Route::get('offices/create_office_group',        'admin\OfficesController@create_office_group')->name('office.create_office_group');
        Route::post('offices/store_office_group',        'admin\OfficesController@store_office_group')->name('office.store_office_group');
        Route::get('offices/edit_office_group/{id}',        'admin\OfficesController@edit_office_group')->name('office.edit_office_group');
        Route::post('offices/update_office_group/{id}',        'admin\OfficesController@update_office_group')->name('office.update_office_group');
        Route::get('offices/delete_office_group/{id}',        'admin\OfficesController@delete_office_group')->name('office.delete_office_group');

        // These are routes for main offices
        Route::post('offices/store_main_office',        'admin\OfficesController@store_main_office')->name('office.store_main_office');
        Route::get('offices/create_main_office',        'admin\OfficesController@create_main_office')->name('office.create_main_office');
        Route::get('offices/delete_main_office/{id}',        'admin\OfficesController@delete_main_office')->name('office.delete_main_office');
        Route::get('offices/edit_main_office/{id}',        'admin\OfficesController@edit_main_office')->name('office.edit_main_office');
        Route::post('offices/update_main_office/{id}',        'admin\OfficesController@update_main_office')->name('office.update_main_office');
        
        Route::get('offices/expense_classes/load_ooes/{parent_id}',        'admin\OfficesController@load_ooes')->name('office.expense_classes.load_ooes');
        Route::get('offices/load_tags',        'admin\OfficesController@load_tags')->name('office.load_tags');
        Route::get('offices/load_expense_classes/{category_id}',        'admin\OfficesController@load_expense_classes')->name('office.load_expense_classes');

        Route::get('allotments', 'admin\AllotmentController@index')->name('allotment.index');
        Route::get('allotments/create',        'admin\AllotmentController@create')->name('allotment.create');
        Route::post('allotments/store',        'admin\AllotmentController@store')->name('allotment.store');
        Route::get('allotments/edit/{id}',        'admin\AllotmentController@edit')->name('allotment.edit');
        Route::get('allotments/delete/{id}',        'admin\AllotmentController@delete')->name('allotment.delete');
        Route::post('allotments/update/{id}',        'admin\AllotmentController@update')->name('allotment.update');
        Route::get('allotments/delete/{id}',        'admin\AllotmentController@delete')->name('allotment.delete');

        Route::get('/', 'admin\DashboardController@index')->name('dashboard.index');
        Route::post('/select_office', 'admin\DashboardController@selectOffice')->name('dashboard.select_office');
        Route::post('/select_date', 'admin\DashboardController@selectDate')->name('dashboard.select_date');

        Route::get('expenses', 'admin\ExpenseController@index')->name('expense.index');
        Route::get('expenses/create',        'admin\ExpenseController@create')->name('expense.create');
        Route::post('expenses/store',        'admin\ExpenseController@store')->name('expense.store');
        Route::get('expenses/edit/{id}',        'admin\ExpenseController@edit')->name('expense.edit');
        Route::get('expenses/delete/{id}',        'admin\ExpenseController@delete')->name('expense.delete');
        Route::post('expenses/update/{id}',        'admin\ExpenseController@update')->name('expense.update');
        Route::get('expenses/delete/{id}',        'admin\ExpenseController@delete')->name('expense.delete');
        Route::get('expenses/get_office_allotment_balance',        'admin\ExpenseController@get_office_allotment_balance')->name('expense.get_office_allotment_balance');
        Route::get('expenses/print/{id}',        'admin\ExpenseController@print')->name('expense.print');

        Route::get('reports', 'admin\ReportsController@index')->name('reports.index');
        Route::get('reports/export', 'admin\ReportsController@export')->name('reports.export');

        Route::resource('roles',        'RolesController');
        Route::resource('mail',        'MailController');
        Route::get('prepareSend/{id}',        'MailController@prepareSend')->name('prepareSend');
        Route::post('mailSend/{id}',        'MailController@send')->name('mailSend');
        Route::get('/roles/move/move-up',      'RolesController@moveUp')->name('roles.up');
        Route::get('/roles/move/move-down',    'RolesController@moveDown')->name('roles.down');
        Route::prefix('menu/element')->group(function () { 
            Route::get('/',             'MenuElementController@index')->name('menu.index');
            Route::get('/move-up',      'MenuElementController@moveUp')->name('menu.up');
            Route::get('/move-down',    'MenuElementController@moveDown')->name('menu.down');
            Route::get('/create',       'MenuElementController@create')->name('menu.create');
            Route::post('/store',       'MenuElementController@store')->name('menu.store');
            Route::get('/get-parents',  'MenuElementController@getParents');
            Route::get('/edit',         'MenuElementController@edit')->name('menu.edit');
            Route::post('/update',      'MenuElementController@update')->name('menu.update');
            Route::get('/show',         'MenuElementController@show')->name('menu.show');
            Route::get('/delete',       'MenuElementController@delete')->name('menu.delete');
        });
        Route::prefix('menu/menu')->group(function () { 
            Route::get('/',         'MenuController@index')->name('menu.menu.index');
            Route::get('/create',   'MenuController@create')->name('menu.menu.create');
            Route::post('/store',   'MenuController@store')->name('menu.menu.store');
            Route::get('/edit',     'MenuController@edit')->name('menu.menu.edit');
            Route::post('/update',  'MenuController@update')->name('menu.menu.update');
            Route::get('/delete',   'MenuController@delete')->name('menu.menu.delete');
        });
        Route::prefix('media')->group(function () {
            Route::get('/',                 'MediaController@index')->name('media.folder.index');
            Route::get('/folder/store',     'MediaController@folderAdd')->name('media.folder.add');
            Route::post('/folder/update',   'MediaController@folderUpdate')->name('media.folder.update');
            Route::get('/folder',           'MediaController@folder')->name('media.folder');
            Route::post('/folder/move',     'MediaController@folderMove')->name('media.folder.move');
            Route::post('/folder/delete',   'MediaController@folderDelete')->name('media.folder.delete');;

            Route::post('/file/store',      'MediaController@fileAdd')->name('media.file.add');
            Route::get('/file',             'MediaController@file');
            Route::post('/file/delete',     'MediaController@fileDelete')->name('media.file.delete');
            Route::post('/file/update',     'MediaController@fileUpdate')->name('media.file.update');
            Route::post('/file/move',       'MediaController@fileMove')->name('media.file.move');
            Route::post('/file/cropp',      'MediaController@cropp');
            Route::get('/file/copy',        'MediaController@fileCopy')->name('media.file.copy');
        });
    });
});