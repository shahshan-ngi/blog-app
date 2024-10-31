@extends('layouts.main')
@section('home-section')
@include('shared.message')

<div class="container mt-5">
 
    <div class="row">
        <div class="col-md-8 offset-md-2">
          
            <div class="blog-container"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        const token=localStorage.getItem('auth_token');
        $.ajax({
            url: `http://127.0.0.1:8000/api/blogs/`, 
            type: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            success: function(response) {
                if (response.status === 'success') {
                  
                    const blogs = response.data.blogs;  
                    $('.blog-container').empty();

                    blogs.forEach(function(blog) {
                
                        let blogHtml = `
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ asset('storage/images/profile/') }}/${blog.user.id}/${blog.user.profile_image}" alt="Author Avatar" class="blog-avatar mr-3 rounded-circle" width="50" height="50">
                                        <div class="mx-2">
                                            <h5 class="mb-0">${blog.user.name}</h5>
                                            <small class="text-muted">Posted on ${new Date(blog.created_at).toLocaleDateString()}</small>
                                        </div>
                                    </div>
                                    <h3 class="card-title">${blog.title}</h3>
                                    <p class="card-text">${blog.content}</p>
                                     ${blog.thumbnail ? `<img src="{{ asset('storage/images/blogs/') }}/${blog.id}/${blog.thumbnail}" alt="Blog Thumbnail" class="img-fluid mt-3">` : ''}
                                </div>
                            </div>
                        `;

                        
                        $('.blog-container').append(blogHtml);
                    });
                } else {
                    console.error('Blogs not found.');
                }
            },
            error: function(xhr) {
                console.error('Error fetching blog:', xhr);
            }
        });
    });
</script>
@endsection