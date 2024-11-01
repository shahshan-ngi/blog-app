@extends('layouts.main')
@section('home-section')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-5"> <!-- Margin 5-->
        <div class="h2">Update Blog</div>
        <a href="" class="btn btn-primary btn-lg">Back</a>
    </div>
    <div id="general-error" class="alert alert-danger" style="display: none;"></div>
    <div class="card">
        <div class="card-body">
        <form id="UpdateBlog" method="post">
    @csrf
    <label for="title" class="form-label mt-4">Title</label>
    <input type="text" name="title" class="form-control" id="title">
    <div id="error-title" class="text-danger"></div>
    
    <label for="content" class="form-label mt-4">Content</label>
    <input type="text" name="content" class="form-control" id="content">
    <div id="error-content" class="text-danger"></div>

    <div id="thumbnail-preview" style="margin-top:10px;">
        <img src="" alt="Thumbnail Preview" style="width: 180px; height: 180px; display: none;" />
    </div>
    <div class="mb-3">
        <label for="thumbnail" class="form-label">Blog Image</label>
        <input type="file" name="thumbnail" class="form-control" id="thumbnail" accept="image/*">
        <div id="error-thumbnail" class="text-danger"></div>
    </div>

    <button class="btn btn-primary btn-lg mt-4">Update Blog</button>
</form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const urlParts = window.location.pathname.split('/'); 
        const blogId = urlParts[urlParts.length - 2]; 
        if (blogId) {
            
            $.ajax({
                url: `http://127.0.0.1:8000/api/blogs/${blogId}`, 
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const blog = response.data; 
                        $('input[name="title"]').val(blog.title);
                        $('input[name="content"]').val(blog.content);


                        if (blog.thumbnail) {
                            const imageUrl = `{{ asset('storage/images/blogs/') }}/${blog.id}/${blog.thumbnail}`;
                            $('#thumbnail-preview img').attr('src', imageUrl).show();
                        }
                    } else {
                        console.error('Blog not found.');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching blog:', xhr);
                }
            });
        }

        $("#UpdateBlog").on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url:`http://127.0.0.1:8000/api/blogs/${blogId}`, 
                type: 'POST',
                data: formData,
                headers:{
                    'X-HTTP-Method-Override': 'PUT',
                },
                contentType: false, 
                processData: false, 
                success: function(response) {
             
                    console.log('updated');
                },
                error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $('.text-danger').text(''); 
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            var errorMessage = errors[field][0];
                            $('#error-' + field).text(errorMessage); 
                        }
                    }
                } else if (xhr.status === 401) {
                    $('#general-error').text(xhr.responseJSON.message || 'Unauthorized access.').show();
                } else {
                    console.log("Error logging in:", xhr);
                }
            }
            });
        });
    });
</script>

@endsection