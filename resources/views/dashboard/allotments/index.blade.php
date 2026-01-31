@extends('layouts.modern')

@section('title', 'Allotments')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li class="text-gray-500">Allotments</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Allotments / Appropriations</h1>
            <p class="mt-1 text-sm text-gray-500">Manage budget allotments and appropriations</p>
        </div>
        <a href="{{ route('allotment.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Allotment
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Filters Card -->
        <div class="card">
            <div class="card-body">
                <form action="" method="GET" id="frmFilter" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Office Group -->
                        <div>
                            <label for="office_group" class="form-label">Office Group</label>
                            <select name="office_group" id="office_group" class="form-input">
                                <option value="">All Office Groups</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('office_group') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Office (OOE) -->
                        <div>
                            <label for="ooe" class="form-label">Office</label>
                            <select name="ooe" id="ooe" class="form-input">
                                <option value="">All Offices</option>
                                @foreach($ooes as $ooe)
                                    <option value="{{ $ooe->id }}" {{ request('ooe') == $ooe->id ? 'selected' : '' }}>
                                        {{ $ooe->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Expense Class -->
                        <div>
                            <label for="office_id" class="form-label">Expense Class</label>
                            <select name="office_id" id="office_id" class="form-input">
                                <option value="">All Expense Classes</option>
                                @foreach($expense_classes as $office)
                                    <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Allotments Table -->
        <div class="card">
            @if($allotments->count() > 0)
                <!-- Top Pagination -->
                <div class="px-4 py-3 border-b border-gray-200">
                    {{ $allotments->appends(request()->input())->links('vendor.pagination.tailwind') }}
                </div>
            @endif
            <div class="overflow-x-auto">
                @if($allotments->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Office Group</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Office</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Class</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50 shadow-[-4px_0_6px_-4px_rgba(0,0,0,0.1)]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($allotments as $allotment)
                                <tr class="hover:bg-gray-50 group">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $allotment->id }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $allotment->year }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @if($allotment->month && $allotment->month != 0)
                                            <span class="badge-primary">{{ date('F', mktime(0, 0, 0, $allotment->month, 1)) }}</span>
                                        @else
                                            <span class="badge-warning">Appropriation</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $allotment->expense_class->category->parent->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $allotment->expense_class->category->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $allotment->expense_class->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                        ₱{{ number_format($allotment->amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 max-w-[150px] truncate" title="{{ $allotment->remarks }}">
                                        {{ $allotment->remarks ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $allotment->created_at ? $allotment->created_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium sticky right-0 bg-white group-hover:bg-gray-50 shadow-[-4px_0_6px_-4px_rgba(0,0,0,0.1)]">
                                        <div class="flex items-center justify-end gap-1">
                                            <a 
                                                href="{{ route('allotment.edit', ['id' => $allotment->id]) }}" 
                                                class="btn-primary btn-sm inline-flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <a 
                                                href="{{ route('allotment.delete', ['id' => $allotment->id]) }}" 
                                                class="btn-danger btn-sm inline-flex items-center gap-1 delete-link"
                                                onclick="return confirm('Are you sure you want to delete this allotment?');">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12 px-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No allotments found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new allotment.</p>
                        <div class="mt-6">
                            <a href="{{ route('allotment.create') }}" class="btn-primary inline-flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                New Allotment
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            @if($allotments->count() > 0)
                <!-- Footer Pagination -->
                <div class="card-footer bg-gray-50 border-t border-gray-200 px-4 py-3">
                    {{ $allotments->appends(request()->input())->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            var url = "{{ url('offices/expense_classes/load_ooes/') }}/";
            var url_expense_classes = "{{ url('offices/load_expense_classes/') }}/";

            // Auto-submit on filter change
            $("#office_id, #office_group, #ooe").change(function(){
                $("#frmFilter").submit();
            });

            // Office group change - load offices (OOEs)
            $('#office_group').change(function(){
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: url + id,
                        method: "GET",
                        success: function(data){
                            $("#ooe").html('<option value="">All Offices</option>' + data);
                        }
                    });
                } else {
                    $("#ooe").html('<option value="">All Offices</option>');
                    $("#office_id").html('<option value="">All Expense Classes</option>');
                }
            });

            // OOE change - load expense classes
            $('#ooe').change(function(){
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: url_expense_classes + id,
                        method: "GET",
                        success: function(data){
                            $("#office_id").html('<option value="">All Expense Classes</option>' + data);
                        }
                    });
                } else {
                    $("#office_id").html('<option value="">All Expense Classes</option>');
                }
            });
        });
    </script>
@endsection
