<div class="container">
     <main class="col-md-12 ml-sm-auto col-lg-12 px-md-4 py-4 p-3">
          <h1 class="h2">Dashboard </h1>
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
                                                       <td> {{ $post?->user?->username ?? null}} </td>
                                                       <td> {{ number_format($post->views) }}</td>
                                                       <td> {{ $post->created_at }} </td>
                                                       <td>
                                                            {{-- <a href="#" class="btn btn-sm btn-primary">View</a> --}}
                                                            <a href="{{ route("dashboard.forum.form.post", ['id'=>  $post->id]) }}" class="btn btn-sm btn-warning">Sửa</a>

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
