@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Product
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search products..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body">
        @if($products->isEmpty())
            <p class="text-muted text-center">No products found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->variants->count() > 0)
                                    <br>
                                    <small class="text-muted">{{ $product->variants->count() }} variants</small>
                                @endif
                            </td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>
                                @if($product->variants->count() > 0)
                                    <div>
                                        @php
                                            $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
                                        @endphp
                                        @if($minPrice == $maxPrice)
                                            ₹{{ number_format($minPrice, 2) }}
                                        @else
                                            ₹{{ number_format($minPrice, 2) }} - ₹{{ number_format($maxPrice, 2) }}
                                        @endif
                                        <br>
                                        <small class="text-muted">Base: ₹{{ number_format($product->price, 2) }}</small>
                                    </div>
                                @else
                                    ₹{{ number_format($product->price, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($product->variants->count() > 0)
                                    @php
                                        $inStockVariants = $product->variants->where('is_available', true)->where('stock_quantity', '>', 0)->count();
                                        $totalVariants = $product->variants->count();
                                    @endphp
                                    @if($inStockVariants == $totalVariants)
                                        <span class="badge bg-success">All In Stock</span>
                                    @elseif($inStockVariants > 0)
                                        <span class="badge bg-warning">{{ $inStockVariants }}/{{ $totalVariants }} In Stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        @foreach($product->variants as $variant)
                                            <span class="d-block">{{ $variant->weight }}: {{ $variant->stock_quantity }}</span>
                                        @endforeach
                                    </small>
                                @else
                                    <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                                        {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                    @if($product->stock_quantity !== null)
                                        <br><small class="text-muted">Qty: {{ $product->stock_quantity }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($product->featured)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $product->id) }}" 
                                   class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" 
                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection