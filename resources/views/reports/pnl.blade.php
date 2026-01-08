@extends('layouts.app')

@section('title', 'Profit & Loss')
@section('page-title', 'Profit & Loss Statement')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Report</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.pnl') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Financial Summary</h6>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <td class="fw-bold">Revenue (Sales)</td>
                        <td class="text-end">KES {{ number_format($revenue, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold ps-4">Cost of Goods Sold</td>
                        <td class="text-end text-danger">({{ number_format($cogs, 2) }})</td>
                    </tr>
                    <tr class="table-active">
                        <td class="fw-bold">Gross Profit</td>
                        <td class="text-end fw-bold">KES {{ number_format($grossProfit, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold ps-4">Operating Expenses</td>
                        <td class="text-end text-danger">({{ number_format($expenses, 2) }})</td>
                    </tr>
                    <tr class="table-success">
                        <td class="fw-bold fs-5">Net Profit</td>
                        <td class="text-end fw-bold fs-5 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            KES {{ number_format($netProfit, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Gross Margin</h6>
                            <h4>{{ $revenue > 0 ? number_format(($grossProfit / $revenue) * 100, 1) : 0 }}%</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Net Margin</h6>
                            <h4>{{ $revenue > 0 ? number_format(($netProfit / $revenue) * 100, 1) : 0 }}%</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Expense Ratio</h6>
                            <h4>{{ $revenue > 0 ? number_format(($expenses / $revenue) * 100, 1) : 0 }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
