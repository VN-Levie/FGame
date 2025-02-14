@extends("layouts.app")
@section("content")
     @php
          // nếu đã đăng nhập thì chuyển hướng về trang chủ
          if (isset($_SESSION["user"])) {
              header("Location: /");
              die();
          }
     @endphp

<style>
    /* Tùy chỉnh CSS cho form đăng nhập */
    .login-form {
        /* width: 350px; */
        margin: 0 auto;
        /* margin-top: 50px; */
    }
</style>


<div class="container p-5 mx-auto my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-white border-0">
                    <h2>Đăng ký tài khoản</h2>
                </div>
                <div class="card-body">
                    <form action="#" method="post" id="dang_nhap">
                        <div class="form-group">
                            <label for="username">Tài khoản</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Nhập lại mật khẩu</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        </div>
                        <br>
                        <div class="form-group text-center ">
                            <button type="submit" class="btn btn-primary btn-block" name="login">Đăng ký</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-white">
                    <a href="/login" class="btn btn-link">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập
                    </a>
                    <br>
                    <a href="/home" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function() {
        // Xử lý đăng nhập
        $("#dang_nhap").ajaxForm({
            dataType: 'json',
            url: '/register',
            beforeSend: function() {
                $('button[name="login"]').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').attr('disabled', 'disabled');

            },
            success: function(data) {
                $('button[name="login"]').html('Đăng ký').removeAttr('disabled');
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
                            window.location.assign('/login');
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
                $('button[name="login"]').html('Đăng ký').removeAttr('disabled');
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