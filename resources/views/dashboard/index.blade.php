@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title text-muted small mb-1">Today's Sales</h6>
                    <button class="btn btn-sm btn-link text-muted p-0" id="toggleSalesBtn" title="Hide/Unhide Sales">
                        <i class="bi bi-eye-slash" id="salesEyeIcon"></i>
                    </button>
                </div>
                <div class="stat-value h4 fw-bold mb-0" id="salesAmountContainer">
                    <span id="salesValueMasked">KES *****</span>
                    <span id="salesValueActual" style="display: none;">KES {{ number_format($todaySales, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    @if(auth()->user()->isSuperAdmin())
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 border-start border-success border-4">
            <div class="card-body">
                <h6 class="card-title text-muted small mb-1">MTD Profit <i class="bi bi-info-circle small" title="Monthly Revenue - (COGS + Expenses)"></i></h6>
                <div class="stat-value h4 fw-bold mb-0 {{ $mtdProfit >= 0 ? 'text-success' : 'text-danger' }}">
                    KES {{ number_format($mtdProfit, 2) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 border-start border-info border-4">
            <div class="card-body">
                <h6 class="card-title text-muted small mb-1">MTD Revenue</h6>
                <div class="stat-value h4 fw-bold mb-0">KES {{ number_format($mtdRevenue, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body">
                <h6 class="card-title text-muted small mb-1">Low Stock Items</h6>
                <div class="stat-value h4 fw-bold mb-0 text-warning">{{ $lowStockProducts }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleSalesBtn');
    const eyeIcon = document.getElementById('salesEyeIcon');
    const maskedValue = document.getElementById('salesValueMasked');
    const actualValue = document.getElementById('salesValueActual');
    
    // Default to hidden (masked)
    let isHidden = true;
    
    toggleBtn.addEventListener('click', function() {
        if (isHidden) {
            maskedValue.style.display = 'none';
            actualValue.style.display = 'inline';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        } else {
            maskedValue.style.display = 'inline';
            actualValue.style.display = 'none';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        }
        isHidden = !isHidden;
    });
});
</script>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Sales</h5>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Receipt</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentSales as $sale)
                                <tr>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none">
                                            #{{ $sale->receipt_number }}
                                        </a>
                                    </td>
                                    <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                                    <td><strong>KES {{ number_format($sale->total_amount, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($sale->primary_payment_method) }}</span>
                                    </td>
                                    <td>{{ $sale->created_at->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No sales yet today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                @if ($activeShift)
                    <a href="{{ route('sales.create') }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-plus-circle"></i> New Sale
                    </a>
                    <a href="#" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#closeShiftModal">
                        <i class="bi bi-x-circle"></i> Close Shift
                    </a>
                @else
                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#openShiftModal">
                        <i class="bi bi-play-circle"></i> Open Shift
                    </button>
                    <p class="text-muted text-center mt-3">No active shift. Open a shift to start selling.</p>
                @endif

                <hr>

                <h6 class="mt-4">Inventory</h6>
                @if ($lowStockProducts > 0)
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-warning w-100">
                        <i class="bi bi-exclamation-triangle"></i> View Low Stock
                    </a>
                @else
                    <p class="text-muted small">All products well stocked</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Open Shift Modal -->
<div class="modal fade" id="openShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Open New Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shifts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="opening_cash" class="form-label">Opening Cash (KES)</label>
                        <input type="number" step="0.01" id="opening_cash" name="opening_cash" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="opening_notes" class="form-label">Notes</label>
                        <textarea id="opening_notes" name="opening_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Open Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
