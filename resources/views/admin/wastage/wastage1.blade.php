@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/wastage.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Daily Wastage Report (Consumption → Production)</h1>
</div>

<div class="card">
    <div class="card-header">
        <div class="filter-form">
            <form id="filterForm" class="form-inline">
                <div class="form-group mx-2">
                    <label for="from_date" class="mr-2">From Date:</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group mx-2">
                    <label for="to_date" class="mr-2">To Date:</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="resetFilters()">Reset</button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <!-- Total Wastage Box -->
        <div class="total-wastage-box mb-4">
            <div class="wastage-content">
                <h4>Total Wastage</h4>
                <div class="wastage-value" id="total-wastage">
                    {{ number_format(abs($totalWastage), 2) }} kg
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Wastage (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wastageReport as $report)
                    <tr>
                        <td>{{ date('d/m/y', strtotime($report->date)) }}</td>
                        <td>{{ number_format(abs($report->wastage), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route("admin.wastage1.index") }}',
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    updateTable(response.data);
                    $('#total-wastage').text(formatNumber(Math.abs(response.totalWastage)) + ' kg');
                }
            });
        });
    });

    function resetFilters() {
        $('#from_date').val('');
        $('#to_date').val('');
        $('#filterForm').submit();
    }

    function updateTable(data) {
        var tbody = $('tbody');
        tbody.empty();
        
        data.forEach(function(report) {
            tbody.append(`
                <tr>
                    <td>${formatDate(report.date)}</td>
                    <td>${formatNumber(Math.abs(report.wastage))}</td>
                </tr>
            `);
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2);
        return `${day}/${month}/${year}`;
    }

    function formatNumber(number) {
        return parseFloat(number).toFixed(2);
    }
</script>

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