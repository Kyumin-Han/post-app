<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $post->title }}</title>

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
            crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
        <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
        <style>
            .flex {
                display: block;
                text-align: center;
            }
            .like {
                display: block;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="mt-5">
                <a href="{{ route('posts.index',['page'=>$page]) }}">목록보기</a>
            </div>
            <div class="mt-5">
                <div class="mb-3">
                    <label for="title" class="form-label">제목</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        id="title"
                        placeholder="Title"
                        readonly="readonly"
                        value="{{ $post->title }}">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">내용</label>
                    <div
                        class="form-control"
                        name="content"
                        id="content"
                        rows="15"
                        readonly="readonly">{!! $post->content !!}</div>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">이미지</label>
                    {{-- <img src="/storage/images/{{ $post->image ?? 'no_image_available.png'}}" class="img-thumbnail" width="200" height="200"/> --}}
                    @if ($post->imagePath() != 'no_image_available.png')
                    <a href="{{ $post->imagePath() }}"><img src="{{ $post->imagePath() }}" class="img-thumbnail" width="20%"/></a>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label">등록일</label>
                    <input
                        type="text"
                        class="form-control"
                        readonly="readonly"
                        value="{{ $post->created_at->diffForHumans() }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">수정일</label>
                    <input
                        type="text"
                        class="form-control"
                        readonly="readonly"
                        value="{{ $post->updated_at }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">작성자</label>
                    <input
                        type="text"
                        class="form-control"
                        readonly="readonly"
                        value="{{ $post->user->name }}">
                </div>
                <div class="mt-3 like">

                    {{-- <a href="{{ route('posts.like') }}"> --}}
                        <button
                            type="button"
                            id="like"
                            class="btn btn-outline-danger btn-sm"
                            onclick="location.href='{{ route('posts.like', ['id'=>$post->id, 'page'=>$page]) }}'">좋아요</button>
                    {{-- </a> --}}

                    <script>
                        function change() {
                            var x = document.getElementById('like');
                            x.style.color = "white";
                            // x.style.font.color = "white";
                            x.style.backgroundColor = "red";
                        }
                    </script>
                </div>
                @auth
                {{-- @if (auth()->user()->id == $post->user_id) --}}
                @can('update', $post)
                <div class="flex">
                    <button
                        class="btn btn-warning"
                        onclick="location.href='{{ route('posts.edit', ['post'=>$post->id, 'page'=>$page]) }}'">수정</button>

                    <form
                        class="deletebtn"
                        action="{{ route('posts.delete', ['id'=>$post->id, 'page'=>$page]) }}"
                        method="post">
                        @csrf @method("delete")
                        <button type="submit" class="btn btn-danger">삭제</button>
                    </form>
                    {{-- <button class="btn btn-primary" onclick="location.href={{ route('posts.index',['page'=> $page]) }}">목록</button> --}}
                </div>
                @endcan
                {{-- @endif --}}
                @endauth

            </div>
        </div>
    </body>
</html>