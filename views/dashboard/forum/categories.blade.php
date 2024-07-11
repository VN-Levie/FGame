<div class="container">
     <main class="col-md-12 ml-sm-auto col-lg-12 px-md-4 py-4 p-3">
          <h1 class="h2">Dashboard </h1>
          <div class="row">
               <div class="col-12 col-xl-12 mb-4 mb-lg-0 mt-3">
                    <div class="card">
                         <h5 class="card-header">Quản lý danh mục diễn đàn |
                              <a href="{{ route("dashboard.forum.categories.form") }}" class="btn btn-sm btn-primary">Tạo mới</a>
                         </h5>
                         <div class="card-body">

                              <div class="table-responsive">
                                   <table class="table">
                                        <thead>
                                             <tr>
                                                  <th scope="col">#</th>
                                                  <th scope="col">Tên</th>
                                                  <th scope="col">Bài viết</th>
                                                  <th scope="col">Người tạo</th>
                                                  <th scope="col">Ngày tạo</th>
                                                  <th scope="col"></th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             @if (count($form_categories) == 0)
                                                  <tr>
                                                       <td colspan="6">Không có dữ liệu</td>
                                                  </tr>
                                             @endif
                                             @foreach ($form_categories as $row)
                                                  <tr>
                                                       {{-- <pre>
                                                             {{ print_r($row) }}
                                                      </pre> --}}

                                                       <th scope="row"> {{ $row->id }} </th>
                                                       <td> {{ $row->name }} </td>
                                                       <td> {{ number_format(count($row->posts())) }}</td>
                                                       <td> {{ $row?->user?->username }} </td>
                                                       <td> {{ $row->created_at }} </td>
                                                       <td>
                                                            <a href="#" class="btn btn-sm btn-primary">View</a>
                                                            <a href="{{ route("dashboard.forum.categories.form", ["id" => $row->id]) }}" class="btn btn-sm btn-warning">Edit</a>
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
