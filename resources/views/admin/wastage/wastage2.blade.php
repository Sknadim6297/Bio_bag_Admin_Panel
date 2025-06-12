@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/wastage.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<style>
    .total-wastage-box {
        background-color: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    .wastage-content {
        display: inline-block;
    }
    .wastage-content h4 {
        color: #495057;
        margin-bottom: 10px;
        font-size: 18px;
    }
    .wastage-value {
        color: #dc3545;
        font-size: 24px;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Wastage 2 Report (Production → Final Output)</h1>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.wastage2.index') }}" method="GET" class="form-inline filter-form">
            <div class="form-group mx-2">
                <label for="from_date" class="mr-2">From Date:</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="form-group mx-2">
                <label for="to_date" class="mr-2">To Date:</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="form-group mx-2">
                <label for="search" class="mr-2">Search Product:</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search by product name..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.wastage2.index') }}" class="btn btn-secondary ml-2">Reset</a>
        </form>
    </div>

    <div class="card-body">
        {{-- Total Wastage Box --}}
        <div class="total-wastage-box mb-4">
            <div class="wastage-content">
                <h4>Total Wastage</h4>
                @php
                    $filteredWastage = 0;
                    foreach ($wastageReport as $report) {
                        if ($report->total_final_output > 0) {
                            $filteredWastage += $report->wastage;
                        }
                    }
                @endphp
                <div class="wastage-value" id="total-wastage">
                    {{ number_format($filteredWastage, 2) }} kg
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Production (kg)</th>
                        <th>Total Final Output (kg)</th>
                        <th>Wastage (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wastageReport as $report)
                        @if($report->total_final_output > 0)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($report->date)->format('d/m/y') }}</td>
                            <td>{{ number_format($report->total_production, 2) }}</td>
                            <td>{{ number_format($report->total_final_output, 2) }}</td>
                            <td>{{ number_format($report->wastage, 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $wastageReport->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection
