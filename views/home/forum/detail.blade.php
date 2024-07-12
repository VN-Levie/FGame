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
     <div class="container mt-5">
          <div class="row">
               <div class="col-md-12">
                    <div class="card shadow-sm">
                         <div class="card-body">
                              <h1 class="card-title">{{ $forum->title }}</h1>
                              <p class="card-text text-muted">
                                   Đăng bởi <a href="#">{!! $forum->getUser()->getName() !!}</a>
                              </p>
                              <p>
                                   {{-- user-id trùng người đăng thì hiện nút xóa và sửa --}}
                                   @if ($forum->getUser()->id == $user->id)
                                        <a href="{{ route("forum.post", ["id" => $forum->id]) }}" class="btn btn-primary">Sửa</a>
                                        {{-- <a href="{{ route("forum.delete", ["id" => $forum->id]) }}" class="btn btn-danger">Xóa</a> --}}
                                   @endif
                              </p>
                              <hr>
                              <div class="card-text bai-viet-p">
                                   {!! $forum->content !!}
                              </div>
                         </div>
                    </div>
               </div>
               <hr>
               {{-- hiển bình luận --}}
               <div class="col-md-12 mt-3">
                    <div class="card shadow-sm">
                         <div class="card-body">
                              <h3 class="card-title">Bình luận</h3>
                              <hr>
                              <div class="card-text">
                                   <form method="post" id="dang_nhap">
                                        <input type="hidden" name="forum_id" value="{{ $forum->id }}">
                                        <div class="form-group">
                                             <label for="content">Nội dung</label>
                                             <textarea class="form-control" name="content" id="content" rows="3"></textarea>

                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Gửi</button>
                                   </form>
                              </div>
                              <hr>
                              <div class="card-text">
                                   @foreach ($forum->getComments() as $comment)
                                        <div class="card mb-3">
                                             <div class="card-body">
                                                  <p class="card-text">
                                                       {!! $comment->content !!}
                                                  </p>
                                                  <p class="card-text text-muted">
                                                       Ngày đăng bởi <a href="#">{!! $comment->getUser()->getName() !!}</a>
                                                  </p>
                                             </div>
                                        </div>
                                   @endforeach
                              </div>
                         </div>

                    </div>
               </div>
          </div>
     </div>
     <script>
          $(document).ready(function() {
               // forum.comment.submit
               $("#dang_nhap").ajaxForm({
                    dataType: 'json',
                    url: '{{ route("forum.comment.submit") }}',
                    beforeSend: function() {
                         $('button[name="dang_nhap"]').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').attr('disabled', 'disabled');

                    },
                    success: function(data) {
                         $('button[name="dang_nhap"]').html('Đăng nhập').removeAttr('disabled');
                         if (data.status == 'success') {
                              $("#dang_nhap").resetForm();
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
                         $('button[name="dang_nhap"]').html('Đăng nhập').removeAttr('disabled');
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
