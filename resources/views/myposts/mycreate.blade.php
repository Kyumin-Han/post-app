<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>게시글 작성</title>
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
        <script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>

        <style>
            .submitbtn {
                display: block;
                text-align: center;
            }
            .main {
                display: block;
                padding: 2% 5% 5%;
            }
        </style>
    </head>
    <body>
        <div class="main">
            <form action="/myposts/mystore" method="post" enctype="multipart/form-data">
                @csrf
                <legend style="text-align: center">글쓰기</legend>
                <div class="mb-3">
                    <label for="title" class="form-label">제목</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        id="title"
                        placeholder="Title"
                        value="{{ old('title') }}">
                    @error('title')
                    <div class="text-danger fw-bold">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">내용</label>
                    <textarea class="form-control" name="content" id="content" rows="15">{{ old('content') }}</textarea>
                    @error('content')
                    <div class="text-danger fw-bold">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input type="file" class="form-control" id="file" name="imageFile">
                    <label class="input-group-text" for="file">Upload</label>
                    @error('imageFile')
                    <div class="text-danger fw-bold">{{ $message }}</div>
                    @enderror
                </div>
                <div class="submitbtn">
                    <button type="submit" class="btn btn-outline-primary">등록하기</button>
                </div>
            </form>
        </div>

        <script>
            ClassicEditor
                    .create( document.querySelector( '#content' ) )
                    .then( editor => {
                            console.log( editor );
                    } )
                    .catch( error => {
                            console.error( error );
                    } );
    </script>
    </body>
</html>