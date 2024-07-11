@extends("layouts.app")
@section("content")
     <div class="container-fluid">
          <main class="col-md-12 ml-sm-auto col-lg-12 px-md-4 py-4 p-3">
               <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Dashboard</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Posts</li>
                    </ol>
               </nav>
               <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0 mt-3">
                         <div class="card">
                              <h5 class="card-header">Danh sách bài viết |
                                   <a href="{{ route("dashboard.forum.form.post") }}" class="btn btn-sm btn-primary">Tạo bài viết</a>
                              </h5>
                              <div class="card-body">

                                   <div class="table-responsive">
                                        <table class="table">
                                             <thead>
                                                  <tr>
                                                       <th scope="col">#</th>
                                                       <th scope="col">Tiêu đề</th>
                                                       <th scope="col">Danh mục</th>
                                                       <th scope="col">Người đăng</th>
                                                       <th scope="col">View</th>
                                                       <th scope="col">Ngày đăng</th>
                                                       <th scope="col"></th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  @foreach ($posts as $post)
                                                       <tr>
                                                            {{-- <pre>
                                                              {{ print_r($post) }}
                                                       </pre> --}}

                                                            <th scope="row"> {{ $post->id }} </th>
                                                            <td> {{ $post->title }} </td>
                                                            <td> {{ $post?->forum_category?->name ?? null }} </td>
                                                            <td> {{ $post?->user?->username ?? null }} </td>
                                                            <td> {{ number_format($post->views) }}</td>
                                                            <td> {{ $post->created_at }} </td>
                                                            <td>
                                                                 {{-- <a href="#" class="btn btn-sm btn-primary">View</a> --}}
                                                                 <a href="{{ route("dashboard.forum.form.post", ["id" => $post->id]) }}" class="btn btn-sm btn-warning">Sửa</a>
                                                                 <a onclick="del({{ $post->id }})" class="btn btn-sm btn-{{ $post->soft_delete ? 'success' : 'danger' }}">
                                                                      {{ $post->soft_delete ? 'Khôi phục' : 'Xóa' }}
                                                                 </a>
                                                                 <a onclick="hide({{ $post->id }})" class="btn btn-sm btn-{{ $post->hide ? 'success' : 'danger' }}">
                                                                      {{ $post->hide ? 'Mở' : 'Khóa' }}
                                                                 </a>

                                                            </td>

                                                       </tr>
                                                  @endforeach
                                             </tbody>
                                        </table>
                                   </div>
                                   <a href="{{ route("dashboard") }}" class="btn btn-block btn-light">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                   </a>
                              </div>
                         </div>
                    </div>

               </div>
          </main>
     </div>
     <script>
         
          function del(id) {
               Swal.fire({
                    title: 'Thực hiện thao tác?',
                    text: "Thao tác này có thể hành hưởng những dữ liệu khác. Bạn có chắc chắn?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
               }).then((result) => {
                    if (result.isConfirmed) {
                         $.ajax({
                              url: "{{ route("dashboard.forum.post.delete.submit") }}",
                              method: "POST",
                              data: {
                                   id: id
                              },
                              success: function(data) {
                                   if (data.status == 'success') {
                                        Swal.fire(
                                             'Xong!',
                                             data.message,
                                             'success'
                                        ).then((result) => {
                                             location.reload();
                                        });
                                   } else {
                                        Swal.fire(
                                             'Xóa không thành công!',
                                             'Có lỗi xảy ra.',
                                             'error'
                                        );
                                   }
                              }
                         });
                    }
               });
          }

          function hide(id) {
               Swal.fire({
                    title: 'Thực hiện thao tác?',
                    text: "Thao tác này sẽ khóa/mở bình luận của bài viết. Bạn có chắc chắn?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
               }).then((result) => {
                    if (result.isConfirmed) {
                         $.ajax({
                              url: "{{ route("dashboard.forum.post.hide.submit") }}",
                              method: "POST",
                              data: {
                                   id: id
                              },
                              success: function(data) {
                                   if (data.status == 'success') {
                                        Swal.fire(
                                             'Xong!',
                                             data.message,
                                             'success'
                                        ).then((result) => {
                                             location.reload();
                                        });
                                   } else {
                                        Swal.fire(
                                             'Xóa không thành công!',
                                             'Có lỗi xảy ra.',
                                             'error'
                                        );
                                   }
                              }
                         });
                    }
               });
          }
     </script>
@endsection
