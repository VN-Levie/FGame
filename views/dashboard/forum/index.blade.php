<div class="container">
     <main class="col-md-12 ml-sm-auto col-lg-12 px-md-4 py-4 p-3">
          <h1 class="h2">Dashboard </h1>
          <div class="row">
               <div class="col-12 col-xl-12 mb-4 mb-lg-0 mt-3">
                    <div class="card">
                         <h5 class="card-header">Latest transactions</h5>
                         <div class="card-body">

                              <div class="table-responsive">
                                   <table class="table">
                                        <thead>
                                             <tr>
                                                  <th scope="col">#</th>
                                                  <th scope="col">Title</th>
                                                  <th scope="col">Category</th>
                                                  <th scope="col">User</th>
                                                  <th scope="col">View</th>
                                                  <th scope="col">Date</th>
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
                                                       <td> {{ $post->content }} </td>
                                                       <td> {{ $post?->category?->name }} </td>
                                                       <td> {{ $post?->user?->username }} </td>
                                                       <td> {{ number_format($post->views) }}</td>
                                                       <td> {{ $post->created_at }} </td>
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
