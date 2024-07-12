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
                                                       <th scope="col">Username</th>
                                                       <th scope="col">Email</th>
                                                       <th scope="col">Role</th>
                                                       <th scope="col">Status</th>
                                                       <th scope="col">Ngày đăng ký</th>
                                                       <th scope="col"></th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  @if (count($users) == 0)
                                                       <tr>
                                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                                       </tr>
                                                  @endif
                                                  @foreach ($users as $row)
                                                       <tr>
                                                            {{-- <pre>
                                                              {{ print_r($row) }}
                                                       </pre> --}}

                                                            <th scope="row"> {{ $row->id }} </th>
                                                            <td>
                                                                 {!! $row->getName() !!}
                                                            </td>
                                                            <td> {{ $row->email }} </td>
                                                            <td> {!! $row->getRoles() ?? null !!} </td>
                                                            <td>
                                                                 @if ($row->baned == 1)
                                                                      <span class="badge bg-dark">Baned</span>
                                                                 @else
                                                                      <span class="badge bg-success">Active</span>
                                                                 @endif
                                                            </td>
                                                            <td> {{ $row->created_at }} </td>
                                                            <td>
                                                                 {{-- <a href="#" class="btn btn-sm btn-primary">View</a> --}}
                                                                 {{-- <a href="{{ route("dashboard.forum.form.post", ["id" => $row->id]) }}" class="btn btn-sm btn-warning">Sửa</a> --}}
                                                                 <a onclick="del({{ $row->id }})" class="btn btn-sm btn-{{ $row->soft_delete ? "success" : "danger" }}">
                                                                      {{ $row->soft_delete ? "Khôi phục" : "Xóa" }}
                                                                 </a>
                                                                 <a onclick="ban({{ $row->id }})" class="btn btn-sm btn-{{ $row->baned ? "success" : "danger" }}">
                                                                      {{ $row->baned ? "Mở" : "Khóa" }}
                                                                 </a>
                                                                 <a onclick="changeRole({{ $row->id }}, {{ $row->role }})" class="btn btn-sm btn-primary">
                                                                      Thay đổi quyền
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
                              url: "{{ route("dashboard.users.delete.submit") }}",
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

          function ban(id) {
               Swal.fire({
                    title: 'Thực hiện thao tác?',
                    text: "Thao tác này sẽ khóa/mở tài khoản người dùng. Bạn có chắc chắn?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
               }).then((result) => {
                    if (result.isConfirmed) {
                         $.ajax({
                              url: "{{ route("dashboard.users.ban.submit") }}",
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
          //   change role (id, current_role) -> swal -> ajax -> reload
          function changeRole(id, current_role) {
               Swal.fire({
                    title: 'Thay đổi quyền',
                    input: 'select',
                    // 0: user, 1: seller, 3: mod, 5: s-mod, 8: admin, 9: s-admin
                    inputOptions: {
                         0: 'User',
                         1: 'Seller',
                         3: 'Moderator',
                         5: 'Super Moderator',
                         8: 'Admin',
                         9: 'Super Admin'
                    },
                    inputValue: current_role,
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy',
                    inputValidator: (value) => {
                         return new Promise((resolve) => {
                              if (value !== current_role) {
                                   resolve()
                              } else {
                                   resolve('Vui lòng chọn quyền khác quyền hiện tại')
                              }
                         })
                    }
               }).then((result) => {
                    if (result.isConfirmed) {
                         $.ajax({
                              url: "{{ route("dashboard.users.change-role.submit") }}",
                              method: "POST",
                              data: {
                                   id: id,
                                   role: result.value
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
