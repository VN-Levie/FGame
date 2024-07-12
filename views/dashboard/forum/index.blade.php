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
                                                  @if (count($posts) == 0)
                                                       <tr>
                                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                                       </tr>
                                                  @endif
                                                  @foreach ($posts as $row)
                                                       <tr>
                                                            {{-- <pre>
                                                              {{ print_r($row) }}
                                                       </pre> --}}
                                                            @if (($row->soft_delete && $user->checkRole("admin") == false) || $row->archive_by_category)
                                                                 @continue
                                                            @endif

                                                            <th scope="row"> {{ $row->id }} </th>
                                                            <td> {{ $row->title }} </td>
                                                            <td> {{ $row?->forum_category?->name ?? null }} </td>
                                                            <td> {{ $row?->user?->username ?? null }} </td>
                                                            <td> {{ number_format($row->views) }}</td>
                                                            <td> {{ $row->created_at }} </td>
                                                            <td>
                                                                 {{-- <a href="#" class="btn btn-sm btn-primary">View</a> --}}
                                                                 <a href="{{ route("dashboard.forum.form.post", ["id" => $row->id]) }}" class="btn btn-sm btn-warning">Sửa</a>
                                                                 <a onclick="del({{ $row->id }})" class="btn btn-sm btn-{{ $row->soft_delete ? "success" : "danger" }}">
                                                                      {{ $row->soft_delete ? "Khôi phục" : "Xóa" }}
                                                                 </a>
                                                                 <a onclick="hide({{ $row->id }})" class="btn btn-sm btn-{{ $row->hide ? "success" : "danger" }}">
                                                                      {{ $row->hide ? "Mở" : "Khóa" }}
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
                                             'Thao tác không thành công!',
                                             data.message,
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
                                             'Thao tác không thành công!',
                                             data.message,
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
