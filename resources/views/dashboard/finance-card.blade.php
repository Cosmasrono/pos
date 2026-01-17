<!-- Finance Quick View Card - Add to Dashboard -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-gradient-purple text-white py-3">
        <h6 class="m-0"><i class="bi bi-graph-up"></i> Financial Summary</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Sales -->
            <div class="col-md-3">
                <div class="text-center mb-3">
                    <div style="font-size: 2rem; margin-bottom: 5px;">💵</div>
                    <small class="text-muted d-block">Today's Sales</small>
                    <h5 class="mb-0">KES {{ $todaySales ?? '0.00' }}</h5>
                </div>
            </div>

            <!-- Expenses -->
            <div class="col-md-3">
                <div class="text-center mb-3">
                    <div style="font-size: 2rem; margin-bottom: 5px;">💸</div>
                    <small class="text-muted d-block">Today's Expenses</small>
                    <h5 class="mb-0">KES {{ $todayExpenses ?? '0.00' }}</h5>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="col-md-3">
                <div class="text-center mb-3">
                    <div style="font-size: 2rem; margin-bottom: 5px;">📈</div>
                    <small class="text-muted d-block">Net Profit Today</small>
                    <h5 class="mb-0 text-success">KES {{ $todayProfit ?? '0.00' }}</h5>
                </div>
            </div>

            <!-- Pending Expenses -->
            <div class="col-md-3">
                <div class="text-center mb-3">
                    <div style="font-size: 2rem; margin-bottom: 5px;">⏳</div>
                    <small class="text-muted d-block">Pending Approvals</small>
                    <h5 class="mb-0">{{ $pendingExpenses ?? 0 }}</h5>
                </div>
            </div>
        </div>

        <hr>

        <!-- Quick Actions -->
        <div class="row text-center">
            <div class="col-6 col-md-3">
                <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-plus"></i> Expense
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('purchase-orders.create') }}" class="btn btn-sm btn-outline-success w-100">
                    <i class="bi bi-plus"></i> Purchase
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-info w-100">
                    <i class="bi bi-list"></i> Expenses
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-outline-warning w-100">
                    <i class="bi bi-list"></i> Orders
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
