@extends('layouts.main')
@section('home-section')
@include('shared.message')

<div class="container mt-5">
 
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div id="searchandfilter"></div>
            <div class="blog-container"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        

        let filterForm = `
            <div>
                <form id="searchForm" method="GET">
                    <input class="me-2" type="search" id="searchInput" name="search" placeholder="Search">
                    <button type="button" class="btn btn-light ms-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-filter"></i>
                    </button>
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex justify-content-center align-items-start my-5">
                                        <div>
                                            <div><h4>Categories</h4></div>
                                            @foreach($categories as $category)
                                            <div class="form-check">
                                                <input class="form-check-input category-checkbox" type="checkbox" value="{{ $category->id }}" id="category-{{ $category->id }}" name="categories[]">
                                                <label class="form-check-label" for="category-{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="applyFiltersBtn">Apply Filters</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        `;
        const authToken = @json(session('auth_token'));
        console.log(authToken);
        function getBlogs(searchQuery = '', selectedCategories = []) {
            $.ajax({
                url: `http://127.0.0.1:8000/api/blogs/`,
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${authToken}`
                },
                data: {
                    search: searchQuery,
                    categories: selectedCategories
                },
                success: function(response) {
                    if (response.status === 'success') {
                        
                        if (!$('#searchandfilter').children().length) {
                            $('#searchandfilter').append(filterForm);
                        }

                        // Get blogs and display them
                        const blogs = response.data.blogs.data;
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
                    console.error('Error fetching blogs:', xhr);
                }
            });
        }

        getBlogs();


        $(document).on('keyup', '#searchInput', function() {
            const searchQuery = $(this).val();
            const selectedCategories = $('.category-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            getBlogs(searchQuery, selectedCategories);
        });

   
        $(document).on('click', '#applyFiltersBtn', function() {
            const searchQuery = $('#searchInput').val();
            const selectedCategories = $('.category-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            getBlogs(searchQuery, selectedCategories);
            $('#filterModal').modal('hide'); 
        });
    });
</script>
@endsection