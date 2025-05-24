<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5">Shop by Category</h2>
            <p class="lead">Explore our curated collection of premium spices</p>
        </div>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="category-card" style="background-image: url('{{ asset($category->image) }}')">
                    <div class="category-overlay">
                        <div class="text-center">
                            <h3 class="h4">{{ $category->name }}</h3>
                            <a href="{{ route('shop') }}" class="btn btn-sm btn-light mt-2">Explore</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>