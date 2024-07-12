@extends("layouts.app")

@section("content")
     <div class="container mt-4">
          <h2 class="mb-4">Checkout</h2>
          <div class="card mb-4">
               <div class="card-body">
                    <h5 class="card-title"> {{ $product->name }}</h5>
                    <p class="card-text">{!! $product->getShortContent(20) !!}</p>
                    <p class="card-text"><strong>Price:</strong> {{ number_format($product->price) }} VND</p>
               </div>
          </div>
          <form id="checkoutForm" method="post">
               <h4 class="mb-3">Payment Information</h4>
               <div class="form-group">
                    <label for="cc-name">Name on card</label>
                    <input type="text" class="form-control" id="cc-name" placeholder="Full name as displayed on card" name="cc-name" required>
               </div>
               <div class="form-group">
                    <label for="cc-number">Credit card number</label>
                    <input type="text" class="form-control" id="cc-number" placeholder="Credit card number" name="cc-number" required>
               </div>
               <div class="form-row">
                    <div class="form-group col-md-6">
                         <label for="cc-expiration">Expiration</label>
                         <input type="text" class="form-control" id="cc-expiration" placeholder="MM/YY" name="cc-expiration" required>
                    </div>
                    <div class="form-group col-md-6">
                         <label for="cc-cvv">CVV</label>
                         <input type="text" class="form-control" id="cc-cvv" placeholder="CVV" name="cc-cvv" required>
                    </div>
               </div>
               {{-- thong6 tin sản phẩm hiden --}}
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <input type="hidden" name="quantity" value="1">
               <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
          </form>
     </div>
     <script>
        $(document).ready(function() {
             // forum.comment.submit
             $("#checkoutForm").ajaxForm({
                  dataType: 'json',
                  url: '{{ route("shop.buy.submit") }}',
                  beforeSend: function() {
                       $('button[name="checkoutForm"]').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').attr('disabled', 'disabled');

                  },
                  success: function(data) {
                       $('button[name="checkoutForm"]').html('Mua').removeAttr('disabled');
                       if (data.status == 'success') {
                            $("#checkoutForm").resetForm();
                            Swal.fire({
                                 title: "Xong !",
                                 text: data.message,
                                 type: "success",
                                 icon: "success",
                                 confirmButtonClass: 'btn-success',
                                 confirmButtonText: 'OK'
                            }).then((result) => {
                                 if (result.value) {
                                     //reload lại trang
                                         location.reload();
                                 }
                            });
                       } else {

                            Swal.fire({
                                 title: "Lỗi!",
                                 text: data.message,
                                 type: "error",
                                 icon: "error",
                                 confirmButtonClass: 'btn-danger',
                                 confirmButtonText: 'OK'
                            });

                       }


                  },
                  error: function(xhr, ajaxOptions, thrownError) {
                       $('button[name="checkoutForm"]').html('Mua').removeAttr('disabled');
                       Swal.fire({
                            title: "Lỗi!",
                            text: "Có lỗi trong quá trình thực hiện!",
                            type: "error",
                            icon: "error",
                            confirmButtonClass: 'btn-danger',
                            confirmButtonText: 'OK'
                       });
                  }
             });
        });
   </script>
@endsection
