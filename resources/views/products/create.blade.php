@extends('layouts.app')

@section('title', 'Create Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category *</label>
                            <input type="text" id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" 
                                   value="{{ old('category_id') }}" placeholder="Enter category name" required>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="sku" class="form-label">
                                SKU <span class="text-muted">(Stock Keeping Unit)</span> *
                                <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="Unique identifier for tracking and inventory management"></i>
                            </label>
                            <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku') }}" placeholder="e.g., PROD001" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" id="barcode" name="barcode" class="form-control @error('barcode') is-invalid @enderror" 
                                   value="{{ old('barcode') }}">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="reorder_level" class="form-label">Reorder Level *</label>
                            <input type="number" id="reorder_level" name="reorder_level" class="form-control @error('reorder_level') is-invalid @enderror" 
                                   value="{{ old('reorder_level', 10) }}" required>
                            @error('reorder_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cost_price" class="form-label">Cost Price per Item (KES)</label>
                            <input type="number" step="0.01" id="cost_price" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" 
                                   value="{{ old('cost_price') }}" placeholder="0.00">
                            <small class="text-muted">Cost for a single item (Optional)</small>
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="selling_price" class="form-label">Selling Price per Item (KES) *</label>
                            <input type="number" step="0.01" id="selling_price" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" 
                                   value="{{ old('selling_price') }}" placeholder="0.00" required>
                            <small class="text-muted">Selling price for a single item</small>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="quantity_in_stock" class="form-label">Initial Stock Quantity</label>
                            <input type="number" id="quantity_in_stock" name="quantity_in_stock" class="form-control @error('quantity_in_stock') is-invalid @enderror" 
                                   value="{{ old('quantity_in_stock', 0) }}" min="0">
                            <small class="text-muted">Number of items to add initially</small>
                            @error('quantity_in_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total Cost Value</label>
                            <input type="text" id="total_cost_display" class="form-control" readonly disabled>
                            <small class="text-muted">Cost × Quantity</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-bold">Total Selling Value</label>
                            <input type="text" id="total_selling_display" class="form-control border-primary" readonly disabled>
                            <small class="text-muted text-primary">Selling × Quantity</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Product
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const costPriceInput = document.getElementById('cost_price');
    const sellingPriceInput = document.getElementById('selling_price');
    const quantityInput = document.getElementById('quantity_in_stock');
    const totalCostDisplay = document.getElementById('total_cost_display');
    const totalSellingDisplay = document.getElementById('total_selling_display');

    function calculateTotals() {
        const costPrice = parseFloat(costPriceInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        
        const totalCost = (costPrice * quantity).toFixed(2);
        const totalSelling = (sellingPrice * quantity).toFixed(2);
        
        totalCostDisplay.value = 'KES ' + totalCost;
        totalSellingDisplay.value = 'KES ' + totalSelling;
    }

    // Calculate on input change
    costPriceInput.addEventListener('input', calculateTotals);
    sellingPriceInput.addEventListener('input', calculateTotals);
    quantityInput.addEventListener('input', calculateTotals);

    // Initial calculation
    calculateTotals();
});
</script>
@endsection
