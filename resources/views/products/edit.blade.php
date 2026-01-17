@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Product: {{ $product->name ?? 'N/A' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" 
                                   value="{{ old('category_id', $product->category->name ?? '') }}" placeholder="Enter category name" required>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" id="barcode" name="barcode" class="form-control @error('barcode') is-invalid @enderror" 
                                   value="{{ old('barcode', $product->barcode) }}">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cost_price" class="form-label">Cost Price (KES)</label>
                            <input type="number" step="0.01" id="cost_price" name="cost_price" 
                                   class="form-control @error('cost_price') is-invalid @enderror" 
                                   value="{{ old('cost_price', $product->cost_price) }}">
                            <small class="text-muted">Cost for a single item (Optional)</small>
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="selling_price" class="form-label">Selling Price (KES) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="selling_price" name="selling_price" 
                                   class="form-control @error('selling_price') is-invalid @enderror" 
                                   value="{{ old('selling_price', $product->selling_price) }}" required>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="quantity_in_stock" class="form-label">Current Stock</label>
                            <input type="number" id="quantity_in_stock" class="form-control" 
                                   value="{{ $product->quantity_in_stock }}" disabled>
                            <small class="text-muted">Added: {{ $product->quantity_in_stock }} units</small>
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

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="reorder_level" class="form-label">Reorder Level</label>
                            <input type="number" id="reorder_level" name="reorder_level" 
                                   class="form-control @error('reorder_level') is-invalid @enderror" 
                                   value="{{ old('reorder_level', $product->reorder_level) }}">
                            @error('reorder_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Product is Active
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Product
                        </button>
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
    // quantityInput is disabled on edit, but we still trigger initial calculation
    
    // Initial calculation
    calculateTotals();
});
</script>
@endsection
