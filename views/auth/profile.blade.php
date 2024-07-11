@extends("layouts.app")
@section("content")
     <style>
          .profile-card {
               margin-top: 50px;
               border-radius: 15px;
               box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          }

          .profile-card img {
               border-radius: 50%;
               width: 150px;
               height: 150px;
               object-fit: cover;
               margin-top: -75px;
          }

          .profile-card .card-body {
               text-align: center;
          }

          .profile-card .card-body h5 {
               margin-top: 20px;
               font-size: 1.5rem;
               font-weight: bold;
          }

          .profile-card .card-body p {
               color: #777;
          }

          .profile-card .card-footer {
               background: none;
               border-top: none;
          }

          .profile-card .social-links a {
               color: #007bff;
               font-size: 1.2rem;
               margin: 0 10px;
          }
     </style>
     <div class="container">
          <div class="row justify-content-center">
               <div class="col-md-8">
                    <div class="card profile-card">
                         <div class="card-body">
                              <img src="/assets/images/logo.png" alt="Profile Image">
                              <h5>
                                   <?= $user->username ?>
                                   <a href="/edit-profile" class="btn-link">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                   </a>
                              </h5>
                              <p>
                                   {!! $user->getRoles() !!}
                              </p>
                              <p>Là thành viên của Fgame từ ngày <?= date("d/m/Y", strtotime($user->created_at)) ?></p>
                         </div>
                         <div class="card-footer text-center">
                              <!-- đăng xuất/đổi mật khẩu/giỏ hàng/quầy hàng/hóa đơn/dashboard/sổ đại chỉ/edit -->
                              <?php if ($user->checkRole('mod')) : ?>
                              <a href="/dashboard" class="btn btn-dark">
                                   <i class="fas fa-tachometer-alt me-2"></i>
                                   Dashboard
                              </a>
                              <hr>
                              <?php endif; ?>
                              <?php if ($user->checkRole('seller')) : ?>

                              <a href="/shop" class="btn btn-info">
                                   <i class="fas fa-store me-2"></i>
                                   Quầy hàng
                              </a>
                              <hr>
                              <?php endif; ?>

                              <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                   <i class="fas fa-key me-2"></i>
                                   Đổi mật khẩu
                              </a>
                              <a href="/address-book" class="btn btn-primary">
                                   <i class="fas fa-address-book me-2"></i>
                                   Sổ địa chỉ
                              </a>
                              <!-- <a href="/edit-profile" class="btn btn-info">
                             <i class="fas fa-user-edit me-2"></i>
                             Edit
                         </a> -->
                              <a href="/cart" class="btn btn-success">
                                   <i class="fas fa-shopping-cart me-2"></i>
                                   Giỏ hàng
                              </a>
                              <a href="/invoice" class="btn btn-secondary">
                                   <i class="fas fa-file-invoice me-2"></i>
                                   Hóa đơn
                              </a>
                              <a href="/logout" class="btn btn-danger">
                                   <i class="fas fa-sign-out-alt"></i>
                                   Đăng xuất
                              </a>
                              <hr>


                              <!-- <a href="#"><i class="fab fa-facebook-f"></i></a>
                         <a href="#"><i class="fab fa-twitter"></i></a>
                         <a href="#"><i class="fab fa-linkedin-in"></i></a>
                         <a href="#"><i class="fab fa-github"></i></a> -->
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <!-- model đổi mật khuẩ  + ajax -->
     <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                         <form action="#" method="post" id="changePasswordForm">
                              <!-- username - hidend -->
                              <input type="hidden" name="username" value="<?= $user->username ?>">
                              <div class="form-group">
                                   <label for="old_password">Mật khẩu cũ</label>
                                   <input type="password" id="old_password" name="old_password" class="form-control" required>
                              </div>
                              <div class="form-group">
                                   <label for="new_password">Mật khẩu mới</label>
                                   <input type="password" id="new_password" name="new_password" class="form-control" required>
                              </div>
                              <div class="form-group">
                                   <label for="new_password_confirmation">Nhập lại mật khẩu mới</label>
                                   <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                              </div>
                              <div class="form-group">
                                   <button type="submit" class="btn btn-primary" name="change_password">Đổi mật khẩu</button>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>




     <script>
          $(document).ready(function() {
               // Xử lý đăng nhập
               $("#changePasswordForm").ajaxForm({
                    dataType: 'json',
                    url: '/change-password',
                    beforeSend: function() {
                         $('button[name="change_password"]').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').attr('disabled', 'disabled');

                    },
                    success: function(data) {
                         $('button[name="change_password"]').html('Đổi mật khẩu').removeAttr('disabled');
                         if (data.status == 'success') {
                              $("#changePasswordForm").resetForm();
                              Swal.fire({
                                   title: "Xong !",
                                   text: data.message,
                                   type: "success",
                                   icon: "success",
                                   confirmButtonClass: 'btn-success',
                                   confirmButtonText: 'OK'
                              }).then((result) => {
                                   //close modal
                                   $('#changePasswordModal').modal('hide');
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
                         $('button[name="change_password"]').html('Đổi mật khẩu').removeAttr('disabled');
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
