<div class="container-fluid">
     <main class="col-md-12 ml-sm-auto col-lg-12 px-md-4 py-4 p-3">
          <h1 class="h2">Dashboard </h1>
          <div class="row my-4">
               <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card">
                         <h5 class="card-header">
                              <i class="fas fa-users"></i>
                              Người dùng
                         </h5>
                         <div class="card-body">
                              <h5 class="card-title">
                                   {{ count($users) }}
                              </h5>
                              <p class="card-text">Last: {{ $last_user->username }}</p>
                              <!-- <p class="card-text text-success">18.2% increase since last month</p> -->
                         </div>
                    </div>
               </div>
               <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                    <div class="card">
                         <h5 class="card-header">
                              <i class="fas fa-gamepad"></i>
                              Diễn đàn
                         </h5>
                         <div class="card-body">
                              <h5 class="card-title">{{ count($forums) }} posts</h5>
                              <p class="card-text">Comments: <?= $comments ?></p>
                              <!-- <p class="card-text text-success">4.6% increase since last month</p> -->
                         </div>
                    </div>
               </div>
               <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                    <div class="card">
                         <h5 class="card-header">
                              <i class="fas fa-store"></i>
                              Đơn hàng
                         </h5>
                         <div class="card-body">
                              <h5 class="card-title">{{ count($oders) }}</h5>
                              <p class="card-text">Tổng: {{ number_format($sum_total) }} <sup>đ</sup></p>
                              <!-- <p class="card-text text-danger">2.6% decrease since last month</p> -->
                         </div>
                    </div>
               </div>
               <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                    <div class="card">
                         <h5 class="card-header">
                              <i class="fas fa-chart-line"></i>
                              Traffic
                         </h5>
                         <div class="card-body">
                              <h5 class="card-title">{{ $traffics }}</h5>
                              <p class="card-text">Unique: {{ $traffics_unique }}</p>
                              <!-- <p class="card-text text-success">2.5% increase since last month</p> -->
                         </div>
                    </div>
               </div>
          </div>
          <div class="row">
               <div class="col-12 col-xl-6 mb-4 mb-lg-0 mt-3">
                    <div class="card">
                         <h5 class="card-header">Chức năng</h5>
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-12 col-md-6 mt-2">
                                        <div class="card">
                                             <h5 class="card-header bg-white">Quản lý người dùng</h5>
                                             <div class="card-body">
                                                  <ul>
                                                       <li><a href="/users" class="btn btn-link">Danh sách người dùng</a></li>
                                                       <li><a href="/users" class="btn btn-link">Đóng/mở đăng ký</a></li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-12 col-md-6 mt-2">
                                        <div class="card">
                                             <h5 class="card-header bg-white">Quản lý diễn đàn</h5>
                                             <div class="card-body">
                                                  <ul>
                                                       <li><a href="/forums" class="btn btn-link">Quản lý danh mục</a></li>
                                                       <li><a href="{{ route("dashboard.forum") }}" class="btn btn-link">Danh sách bài viết</a></li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-12 col-md-6 mt-2">
                                        <div class="card">
                                             <h5 class="card-header bg-white">Quản lý gian hàng</h5>
                                             <div class="card-body">
                                                  <ul>
                                                       <li><a href="/shops" class="btn btn-link">Danh mục cửa hàng</a></li>
                                                       <li><a href="/shops" class="btn btn-link">Danh sách sản phẩm</a></li>
                                                       <li><a href="/shops" class="btn btn-link">Các đơn hàng</a></li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-12 col-md-6 mt-2">
                                        <div class="card">
                                             <h5 class="card-header bg-white">Quản lý Trafic</h5>
                                             <div class="card-body">
                                                  <ul>
                                                       <li><a href="/traffics" class="btn btn-link">Chi tiết</a></li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="col-12 col-xl-6 mb-4 mb-lg-0 mt-3">
                    <div class="card">
                         <h5 class="card-header">Latest transactions</h5>
                         <div class="card-body">

                              <div class="table-responsive">
                                   <table class="table">
                                        <thead>
                                             <tr>
                                                  <th scope="col">Order</th>
                                                  <th scope="col">Product</th>
                                                  <th scope="col">Customer</th>
                                                  <th scope="col">Total</th>
                                                  <th scope="col">Date</th>
                                                  <th scope="col"></th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             @foreach ($oders as $oder)
                                                  <tr>
                                                       {{-- <pre>
                                                             {{ print_r($oder) }}
                                                       </pre> --}}
                                                      
                                                       <th scope="row"> {{ $oder->id }} </th>
                                                       <td> {{ $oder->product->name }} </td>
                                                       <td> {{ $oder->user->username }} </td>
                                                       <td> {{ number_format($oder->total) }} <sup>đ</sup> </td>
                                                       <td> {{ $oder->created_at }} </td>
                                                       <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                                  </tr>
                                             @endforeach
                                        </tbody>
                                   </table>
                              </div>
                              <a href="#" class="btn btn-block btn-light">View all</a>
                         </div>
                    </div>
               </div>

          </div>
     </main>
