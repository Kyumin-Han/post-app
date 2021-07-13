<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyPostsController extends Controller
{

    public function mycreate() {
        return view('/myposts/mycreate');
    }

    // service container가 자동으로 Request객체를 준다
    public function mystore(Request $request) {
        // $request->input['title'];
        // $request->input['content'];
        $title = $request->title;
        $content = $request->content;

        $request->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'imageFile' => 'image|max:2000'
        ]);

        // dd($request);

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
        return redirect('/myposts/mypost');

        // 직접 뷰를 반환해주는 방식보다는 redirect를 이용하여 다시 요청을 하는 것이 권장된다
        // $posts = Post::paginate(5);
        // return view('posts.index', ['posts'=>$posts]);
    }
    public function mylist() {
    $id = auth()->user()->id;
    $user = User::find($id);
    // dd($user);
    $mypost = $user->posts()->latest()->paginate(5);
    // $posts = Post::latest()->paginate(5);
    return view('myposts.mypost', ['mypost'=>$mypost]);
}

    public function myshow(Request $request, $id) {
    $page = $request->page;
    $post = Post::find($id);
    if(Auth::user() != null && $post->viewers->contains(Auth::user()) == false) {
        $post->viewers()->attach(Auth::user()->id);
    }

    return view('myposts.myshow', compact('post', 'page'));
}

public function myupdate(Request $request, $id) {
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
    return redirect()->route('posts.myshow', ['id'=>$id, 'page'=>$request->page]);
}

public function myedit(Request $request, Post $post) {

    // $post = Post::find($id);
    // 수정 폼 생성
        return view('myposts.myedit', ['post'=>$post, 'page'=>$request->page]);
}

public function mydestroy(Request $request, $id) {
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

    return redirect()->route('posts.mypost', ['page'=>$page]);
}
}
