<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
        <div class="main mt-5">
            <a href="{{ route('dashboard') }}">Dashborard</a>
            <h1>게시글 리스트</h1>
            <ul class="list-group list-group-flush mt-3">
                @foreach ($posts as $post)
                <li class="list-group-item">
                    <span>
                        <a
                            class="text-decoration-none fw-bold"
                            href="{{ route('posts.show', ['id' => $post->id, 'page'=>$posts->currentPage()]) }}">Title:
                            {{ $post->title }}</a>
                    </span>
                    {{-- <div>
                        {{ $post->content }}
                </div>
                --}}
                <span>written on
                    {{ $post->created_at }}
                    {{ $post->viewers->count() }}
                    {{ $post->viewers->count() > 0 ? Str::plural('view', $post->count) : 'view' }}
                </span>

                <span>
                    {{ $post->likes->count() }}
                    {{ $post->likes->count() > 0 ? Str::plural('like', $post->likes->count()) : 'like' }}
                </span>
                {{-- <span>written by
                    {{ $user->name }}
            </span>
            --}}
            <hr>
        </li>

        @endforeach
    </ul>
    @auth
    <a href="/posts/create" class="btn btn-primary">게시글 작성</a>
    @endauth
    <div class="mt-5">
        {{ $posts->links() }}
    </div>
</div>
</x-app-layout>