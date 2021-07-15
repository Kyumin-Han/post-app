<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    // create 메소드 -> 게시글 생성
    // store 메소드 -> 게시글 생성 후 DB에 저장
    // edit 메소드 -> 게시글 수정
    // update 메소드 -> 게시글 수정 후 DB에 저장
    // destory 메소드 -> 게시글 삭제
    // show 메소드 -> 게시글 상세보기
    // index 메소드 -> 게시글 리스트보기

    public function create() {
        return view('/posts/create');
    }

    // service container가 자동으로 Request객체를 준다
    public function store(Request $request) {
        // $request->input['title'];
        // $request->input['content'];
        $title = $request->title;
        $content = $request->content;

        $request->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'imageFile' => 'image|max:2000'
        ]);

        dd($request);

        // DB에 저장
        $post = new Post();
        $post->title = $title;
        $post->content = $content;
        // File 처리
        // 원하는 파일시스템 상의 위치에 원하는 이름으로 파일을 저장하고
        // 그 파일 이름을 
        // $post->image = $fileName; 을 통해서 저장한다
        if($request->file('imageFile')) {
            $post->image=$this->uploadPostImage($request);
        }
        // dd($fileName);
        // 로그인 한 사용자에서 user ID를 받아온다
        $post->user_id = Auth::user()->id; // user 객체를 반환해주었기 때문에 id에 바로 접근 할 수 있다
        $post->save();
        // 결과 뷰를 반환
        return redirect('/posts/index');

        // 직접 뷰를 반환해주는 방식보다는 redirect를 이용하여 다시 요청을 하는 것이 권장된다
        // $posts = Post::paginate(5);
        // return view('posts.index', ['posts'=>$posts]);
    }

    protected function uploadPostImage($request) {
        $name = $request->file('imageFile')->getClientOriginalName();
        $extension = $request->file('imageFile')->extension();
        $nameWithoutExtension = Str::of($name)->basename('.'.$extension);
        $fileName = $nameWithoutExtension . '_' . time() . '.' . $extension;
        $request->file('imageFile')->storeAs('public/images', $fileName);
        return $fileName;
    }

    public function index() {
        // $posts = Post::orderBy('created_at', 'desc')->get();
        // $posts = Post::latest()->get();
        // dd($posts[0]->created_at);
        $posts = Post::latest()->paginate(5);
        return view('posts.index', ['posts'=>$posts]);
    }

    public function __construct() {
        $this->middleware(['auth'])->except(['index', 'show']);
    }

    public function show(Request $request, $id) {
        // dd($request->page);
        $page = $request->page;
        $post = Post::find($id);
        //$post->count++; // 요청이 올 때마다 1씩 증가시킴
        //$post->save();
        // dd($name);

        /*
            글을 조회한 사용자들 중에 현재 로그인 한 사용자가 포함되어 있는지를 체크
            포함되어 있지 않으면 추가하기
            포함되어 있으면 다음 단계로 넘어가기
        */
        // post_user 테이블을 이용해서 한 사용자당 조회수는 한번만 올릴 수 있도록 한다
        if(Auth::user() != null && $post->viewers->contains(Auth::user()) == false) {
            $post->viewers()->attach(Auth::user()->id);
        }
        
        return view('posts.show', compact('post', 'page'));
    }

    public function like(Request $request, $id) {
        $page = $request->page;
        $post = Post::find($id);

        if(Auth::user() != null && $post->likes->contains(Auth::user()) == false) {
            $post->likes()->attach(Auth::user()->id);
        }
        
        return redirect()->route('posts.show', compact('post', 'page'));
    }

    public function edit(Request $request, Post $post) {

        // $post = Post::find($id);
        // 수정 폼 생성
            return view('posts.edit', ['post'=>$post, 'page'=>$request->page]);
    }

    // service container에서 injection 받을 request 객체는 route paramater 보다 앞쪽에 인자로 받아와야 한다
    public function update(Request $request, $id) {
        // 파일 시스템에서 이미지 파일을 수정한다
        // 게시글을 데이터 베이스에서 수정
        // validation
        $request->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'imageFile' => 'image|max:2000'
        ]);

        $post = Post::find($id);
        // Authorization: 수정 권한이 있는지 검사
        // 로그인한 사용자와 게시글의 작성자가 같은지 체크한다
        // if(auth()->user()->id != $post->user_id) {
        //     return abort(403);
        // }

        if($request->user()->cannot('update', $post)) {
            abort(403);
        }
        
        if($request->file('imageFile')) {
            $imagePath = 'public/images/'.$post->image;
            Storage::delete($imagePath);
            $post->image=$this->uploadPostImage($request);
        }
        $post->title=$request->title;
        $post->content=$request->content;
        $post->save();
        return redirect()->route('posts.show', ['id'=>$id, 'page'=>$request->page]);
    }

    public function destroy(Request $request, $id) {
        // 파일 시스템에서 이미지 파일을 삭제
        // 게시글을 데이터 베이스에서 삭제 
        $post = Post::findOrFail($id);

        // if(auth()->user()->id != $post->user_id) {
        //     return abort(403);
        // }

        if($request->user()->cannot('delete', $post)) {
            abort(403);
        }

        $page = $request->page;
        if($post->image) {
            $imagePath = 'public/images/'.$post->image;
            Storage::delete($imagePath);
        }
        $post->delete();

        return redirect()->route('posts.index', ['page'=>$page]);
    }

    
}
