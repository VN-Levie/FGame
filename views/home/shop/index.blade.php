@extends("layouts.app")

@section("content")
     <div class="container mt-4">
          <div class="row">
               <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
               </div>
               <div class="col-md-4">
                    <select id="categorySelect" class="form-control">
                         <option value="all">All Categories</option>
                         {{-- <option value="electronics">Electronics</option>
                         <option value="fashion">Fashion</option>
                         <option value="books">Books</option> --}}
                         @foreach ($product_categories as $category)
                              <option value="{{ $category->id }}">{{ $category->name }}({{ $category->countProduct() }})</option>
                              {{-- @dd( $product_categories ) --}}
                         @endforeach
                    </select>
               </div>
          </div>
     </div>
     <div class="container mt-4">
          <div class="row" id="productList">
               @foreach ($products as $product)
                    <div class="col-md-4 mb-4" data-category="{{ $product->category_id }}">
                         <div class="card">
                              <img src="{{ $product->getThumbnail() }}" class="card-img-top" alt="{{ $product->name }}">
                              <div class="card-body">
                                   <h5 class="card-title">
                                        <a href="{{ route("shop.detail", ["id" => $product->id]) }}">{{ $product->name }}</a>
                                   </h5>
                                   <p class="card-text">{{ $product->description }}</p>
                              </div>
                         </div>
                    </div>
               @endforeach
               <div class="col-md-4 mb-4" data-category="electronics">
                    <div class="card">
                         <img src="image1.jpg" class="card-img-top" alt="Product 1">
                         <div class="card-body">
                              <h5 class="card-title">Product 1</h5>
                              <p class="card-text">Description for product 1.</p>
                         </div>
                    </div>
               </div>
               <div class="col-md-4 mb-4" data-category="fashion">
                    <div class="card">
                         <img src="image2.jpg" class="card-img-top" alt="Product 2">
                         <div class="card-body">
                              <h5 class="card-title">Product 2</h5>
                              <p class="card-text">Description for product 2.</p>
                         </div>
                    </div>
               </div>
               <div class="col-md-4 mb-4" data-category="books">
                    <div class="card">
                         <img src="image3.jpg" class="card-img-top" alt="Product 3">
                         <div class="card-body">
                              <h5 class="card-title">Product 3</h5>
                              <p class="card-text">Description for product 3.</p>
                         </div>
                    </div>
               </div>
               <!-- Add more products here -->
          </div>
     </div>
     <script>
          document.getElementById('searchInput').addEventListener('input', function() {
               let searchQuery = this.value.toLowerCase();
               let products = document.querySelectorAll('#productList .col-md-4');
               products.forEach(product => {
                    let title = product.querySelector('.card-title').textContent.toLowerCase();
                    if (title.includes(searchQuery)) {
                         product.style.display = 'block';
                    } else {
                         product.style.display = 'none';
                    }
               });
          });

          document.getElementById('categorySelect').addEventListener('change', function() {
               let selectedCategory = this.value;
               let products = document.querySelectorAll('#productList .col-md-4');
               products.forEach(product => {
                    if (selectedCategory === 'all' || product.getAttribute('data-category') === selectedCategory) {
                         product.style.display = 'block';
                    } else {
                         product.style.display = 'none';
                    }
               });
          });
     </script>
@endsection
