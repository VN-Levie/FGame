@extends("layouts.app")

@section("content")
     <div class="container">


          <!-- Vòng lặp hiển thị danh mục diễn đàn -->
          {{-- {{ route() }} --}}
          {{-- {{ var_dump(route("home")) }} --}}
          <div class="mb-3">
               @php
                    $text_tyle = "dark";
                    $font_size = "4";
                    if ((route(null) == route("home") || route(null) == route("home.2")) && $id == null) {
                        $text_tyle = "info";
                        $font_size = "3";
                    }

               @endphp
               <span>
                    <a class="fs-{{ $font_size }} text-{{ $text_tyle }}"" href="{{ route("home") }}"> Home
               </span>
               @foreach ($forum_categories as $category)
                    <span>

                         <a class="fs-{{ $category->id == ($id ??= null) ? "3" : "4" }} text-{{ $category->id == ($id ??= null) ? "info" : "dark" }}" href="{{ route("home.2", ["id" => $category->id]) }}">
                              #{{ $category->name }}
                         </a>
                         {{-- {{ $category->name }} --}}
                    </span>
                    @if ($loop_index < count($forum_categories) - 1)
                         <span class="fs-3">,</span>
                    @endif
               @endforeach

               @if (isset($user))
                    <span>
                         <a class="fs-4 text-success" href="{{ route("forum.post") }}">
                              <i class="fas fa-plus"></i>
                              Đăng bài viết
                         </a>
                    </span>
               @endif


          </div>

          <style>
               .card-img-top {
                    width: 100%;
                    height: 225px;
                    object-fit: cover;
               }

               .card-img-topbai-viet {
                    width: 100%;
                    min-height: 345px;
                    object-fit: cover;
               }

               .card-title {
                    font-size: 1.25rem;
               }

               .card-body {
                    min-height: 120px;
               }

               .text-link {
                    color: rgb(218, 136, 136);
                    text-decoration: none;
               }

               .text-link:hover {
                    color: rgb(248, 9, 9);
                    text-decoration: none;
               }
          </style>
          <!-- Vòng lặp hiển thị các diễn đàn -->
          <div class="row">
               @foreach ($forums as $forum)
                    @if ($loop_index == 0)
                         <div class="col-md-6 bai-viet">
                              <div class="card mb-4 shadow-sm">
                                   <img src="{{ $forum->getThumbnail() }}" alt="{{ $forum->title }}" class="card-img-top rounded">
                                   <div class="card-body">
                                        <h2 class="card-title"><a class="text-link" href="{{ route("forum.detail", ["id" => $forum->id]) }}">{{ $forum->title }}</a></h2>
                                        <p class="card-text">{!! $forum->getShortContent() !!}</p>
                                   </div>
                              </div>
                         </div>
                    @else
                         @if ($loop_index == 1)
                              <div class="col-6 col-md-6 bai-viet">
                                   <div class="card mb-4 shadow-sm">
                                        <img src="{{ $forum->getThumbnail() }}" alt="{{ $forum->title }}" class="card-img-top">
                                        <div class="card-body">
                                             <h2 class="card-title"><a class="text-link" href="{{ route("forum.detail", ["id" => $forum->id]) }}">{{ $forum->title }}</a></h2>
                                             <p class="card-text">{!! $forum->getShortContent() !!}</p>
                                        </div>
                                   </div>
                              </div>
                         @else
                              @if ($loop_index <= 5)
                                   <div class="col-6 col-md-3  bai-viet">
                                        <div class="card mb-4 shadow-sm">
                                             <img src="{{ $forum->getThumbnail() }}" alt="{{ $forum->title }}" class="card-img-top">
                                             <div class="card-body">
                                                  <h2 class="card-title"><a class="text-link" href="{{ route("forum.detail", ["id" => $forum->id]) }}">{{ $forum->title }}</a></h2>
                                                  <p class="card-text">{!! $forum->getShortContent() !!}</p>
                                             </div>
                                        </div>
                                   </div>
                              @else
                                   <div class="col-12  bai-viet">
                                        <div class="card mb-4 shadow-sm row">
                                             <div class="row">
                                                  <div class="col-4">
                                                       <img src="{{ $forum->getThumbnail() }}" alt="{{ $forum->title }}" class="card-img-top rounded">
                                                  </div>
                                                  <div class="col-8 mt-3">
                                                       <h2 class="card-title"><a class="text-link" href="{{ route("forum.detail", ["id" => $forum->id]) }}">{{ $forum->title }}</a></h2>
                                                       <p class="card-text">{!! $forum->getShortContent(35) !!}</p>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              @endif
                         @endif
                    @endif
                    {{-- @if ($loop_index == 10)
                    @break
               @endif --}}
          @endforeach
     </div>
</div>
@endsection
