@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Product Inventory</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Cost</th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td><strong>{{ $product->sku }}</strong></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            @if ($product->quantity_in_stock <= $product->reorder_level)
                                <span class="badge bg-danger">{{ $product->quantity_in_stock }}</span>
                            @else
                                {{ $product->quantity_in_stock }}
                            @endif
                        </td>
                        <td>KES {{ number_format($product->cost_price, 2) }}</td>
                        <td><strong>KES {{ number_format($product->selling_price, 2) }}</strong></td>
                        <td>
                            @if ($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No products found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="row mt-4">
    <div class="col">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
