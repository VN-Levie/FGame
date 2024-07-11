@extends("layouts.app")
@section("content")
<div class="container-fluid">
     <main class="col-md-12 ml-sm-auto col-lg-12 px-md-4 py-4 p-3">
          <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Categories</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category?->id ? 'Chỉnh sửa' : 'Tạo mới' }}</li>
               </ol>
          </nav>          
          <div class="row">
               <div class="col-12 col-xl-12 mb-4 mb-lg-0 mt-3">
                    <div class="card">
                         <h5 class="card-header">
                              {{ $category?->id ? 'Chỉnh sửa' : 'Tạo mới' }} danh mục
                         </h5>
                         <div class="card-body">
                              <form method="POST" action="#" id="postSubmit">
                                   <input type="hidden" name="id" value="{{ $category?->id ?? null }}">
                                   <div class="form-group">
                                        <label for="name">Tên danh mục</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tiêu đề" value="{{ $category?->name ?? null }}">                                       
                                        <div class="form-group">
                                             <label for="content">Mô tả</label>
                                             <textarea class="form-control" id="content" name="content" cols="30" rows="10">{{ $category?->description ?? null }}</textarea>
                                        </div>

                                        <div class="form-group">
                                             <hr>
                                             <button class="btn btn-primary" type="submit" id="submit">Lưu</button>
                                        </div>

                              </form>
                         </div>
                         <hr>
                         <a href="{{ route("dashboard.forum.categories") }}" class="btn btn-block btn-light">
                              <i class="fas fa-arrow-left"></i> Quay lại
                         </a>
                         <a href="{{ route("dashboard") }}" class="btn btn-block btn-light">
                              <i class="fas fa-tachometer-alt"></i>
                               Dashboard
                         </a>
                    </div>
               </div>
          </div>
     </main>
     <script src="/assets/js/ckeditor/ckeditor.js"></script>
     <script>
          // Default ckeditor
          CKEDITOR.replace('content', {
               // height: 300,              
          });
          // console.log('ready1');
     </script>
     <script>
          // console.log('ready2');
          $(document).ready(function() {
               // console.log('ready3');

               $('#postSubmit').on('submit', function(e) {
                    e.preventDefault();
                    var form = e.target;
                    var data = new FormData(form);
                    var content = CKEDITOR.instances.content.getData();
                    data.append('description', content);
                    $("#submit").text("Đợi tý...");
                    $('#submit').prop('disabled', true);
                    $.ajax({
                         url: "{{ route("dashboard.forum.categories.form.submit") }}",
                         method: "POST",
                         processData: false,
                         contentType: false,
                         dataType: 'json',
                         data: data,
                         processData: false,
                         success: function(data) {
                              $("#submit").text("Lưu");
                              $('#submit').prop('disabled', false);
                              // console.log(data);
                              if (data.status == 'success') {
                                   $("#postSubmit").resetForm();
                                   Swal.fire({
                                        title: "Xong !",
                                        text: data.message,
                                        type: "success",
                                        icon: "success",
                                        confirmButtonClass: 'btn-success',
                                        confirmButtonText: 'OK'
                                   }).then((result) => {
                                        // if (result.value) {
                                        //      window.location.assign('/');
                                        // }
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
                         error: function(data) {
                              $("#submit").text("Lưu");
                              $('#submit').prop('disabled', false);
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
          });
     </script>

</div>
@endsection