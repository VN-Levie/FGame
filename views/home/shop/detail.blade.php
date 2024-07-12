@extends("layouts.app")

@section("content")
<style>
     .bai-viet-p img {
          max-width: 100%;
          height: auto;
          border-radius: 6px;
          display: inline-block;
          padding: 5px;
     }
     /* ifarme 90% */
     .bai-viet-p iframe {
          width: 100%;
          height: 750px;
          border-radius: 10px;
     }
</style>
     <div class="container mt-4">
          <div class="row">
               <div class="col-md-6 mt-3">
                    <img src="{{ $product->getThumbnail() }}" class="img-fluid" alt="Product Image">
               </div>
               <div class="col-md-6 mt-3 bai-viet-p">
                    <h2>
                         {{ $product->name }}
                    </h2>
                    <p class="text-muted">Category:
                         {{ $product->getCategory()->name }}
                    </p>
                    
                    <p>
                         Số lượng trong kho:                        
                         {{ number_format($product->stock) }} sản phẩm
                    </p>
                    <h4>
                         Price:
                         {{ number_format($product->price) }} VND
                    </h4>
                   
                    
                    
                    <a class="btn btn-primary mt-3" href="{{ route("shop.buy", ['id' => $product->id]) }}">Mua ngay</a>
                    
               </div>
               <div class="col-12 mt-5">
                    <div class="alert alert-info mt-3 bai-viet-p">
                         {!! $product->description !!}
                    </div>
               </div>
               
          </div>
     </div>
    
@endsection
