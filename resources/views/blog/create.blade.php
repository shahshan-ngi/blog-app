@extends('layouts.main')
@section('home-section')
@include('shared.message')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-5"> <!-- Margin 5-->
        <div class="h2">Add Blog</div>
        <a href="" class="btn btn-primary btn-lg">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="CreateBlog" method="post">
                @csrf
                <label for="" class="form-label mt-4">Title</label><!-- mt-4 = margin 4 -->
                <input type="text" name="title"  value="{{ old('title') }}" class ="form-control" id="">
                    <div class="text-danger">
                        @error('title')
                            {{$message}}
                        @enderror
                    </div>
                <label for="" class="form-label mt-4">Content</label>
                <input type="text"  value="{{ old('content') }}" name="content" class = "form-control" id="">
                <div class="text-danger">
                        @error('description')
                            {{$message}}
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Blog Image</label>
                        <input type="file" name="thumbnail" class="form-control" id="thumbnail" accept="image/*">
                        <div class="text-danger">
                        @error('thumbnail')
                            {{$message}}
                        @enderror
                    </div>
                    </div>

             
                <button class="btn btn-primary btn-lg mt-4">Add Blog</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $token=localStorage.getItem('auth_token');
        if($token){
            console.log($token);
        }else{
            console.log('no token');
        }
        $("#CreateBlog").on('submit',function(e){
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax(
                {
                url: "http://127.0.0.1:8000/api/blogs", 
                type: 'POST',
                data: formData,
                headers:{
                    'Authorization': `Bearer ${$token}`
                },
                contentType: false, 
                processData: false, 
                success: function(response) {
                    window.location.href = "http://127.0.0.1:8000/blogs";
                },
                error: function(xhr) {
                   console.log(xhr);
               
                }
                }
            );
        });
    })
</script>
@endsection