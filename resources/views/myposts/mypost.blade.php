<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>게시판</title>
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
    </head>
    <body>
        <div class="main mt-5">
            <a href="{{ route('dashboard') }}">Dashborard</a>
            <h1>게시글 리스트</h1>
            <ul class="list-group list-group-flush mt-3">
                @foreach ($mypost as $post)
                <li class="list-group-item"></li>
                <span>
                    <a
                        href="{{ route('posts.myshow', ['id'=>$post->id, 'page'=>$mypost->currentPage()]) }}">
                        Title :{{ $post->title }}
                    </a>
                </span>

                {{-- <div>
                    {{ $post->content }}
            </div>
            --}}
            <span>written on
                {{ $post->created_at }}</span>

            {{-- <span>written by
                {{ $user->name }}
            </span> --}}
            <hr>
            @endforeach
        </ul>
        @auth
        <a href="/myposts/mycreate" class="btn btn-primary">게시글 작성</a>
        @endauth
        <div class="mt-5">
            {{ $mypost->links() }}
        </div>
    </div>
</body>
</html>