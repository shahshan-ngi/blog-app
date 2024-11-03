@extends('layouts.main')
@section('home-section')
<div class="container mt-5">
    <h2>My Blogs</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Thumbnail</th>
                <th>Posted on</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="blog-table-body">
            <!-- Blog rows will be injected here -->
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div id="paginationContainer" class="mt-4">
        <!-- Pagination links will be injected here -->
    </div>
</div>
<script>
    $(document).ready(function () {
     
        // function getCookie(name) {
        //     const value = `; ${document.cookie}`;
        //     const parts = value.split(`; ${name}=`);
        //     if (parts.length === 2) return parts.pop().split(';').shift();
        // }

        // const userId = getCookie('user_id'); 

        const blogsUrl = `http://127.0.0.1:8000/api/user/blogs`;

        //if (userId) {
            fetchBlogs(1); 
        // } else {
        //     console.error("User ID missing.");
        // }

        function fetchBlogs(page = 1) {
            axios.get(`${blogsUrl}?page=${page}`)
            .then(response => {
                const blogs = response.data.data.blogs.data;
                const currentPage = response.data.current_page; 
                const totalPages = response.data.total_pages; 

                console.log(blogs);
                $('.blog-table-body').empty(); 

                blogs.forEach(blog => {
                    const thumbnailHtml = blog.thumbnail 
                        ? `<img src="{{ asset('storage/images/blogs/') }}/${blog.id}/${blog.thumbnail}" alt="Thumbnail" width="50" height="50">` 
                        : 'No Image';

                    let rowHtml = `
                        <tr>
                            <td>${blog.title}</td>
                            <td>${blog.content}</td>
                            <td>${thumbnailHtml}</td>
                            <td>${new Date(blog.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="btn btn-primary btn-sm update-blog" data-id="${blog.id}">Update</button>
                                <button class="btn btn-danger btn-sm delete-blog" data-id="${blog.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('.blog-table-body').append(rowHtml);
                });

                renderPagination(currentPage, totalPages);

                $('.update-blog').on('click', function() {
                    const blogId = $(this).data('id');
                    updateBlog(blogId);
                });

                $('.delete-blog').on('click', function() {
                    const blogId = $(this).data('id');
                    deleteBlog(blogId);
                });
            })
            .catch(error => {
                console.log("Error fetching blogs:", error);
            });
        }

        function renderPagination(currentPage, totalPages) {
            $('#paginationContainer').empty(); 
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = (i === currentPage) ? 'active' : '';
                $('#paginationContainer').append(`
                    <button class="btn btn-link pagination-link ${activeClass}" data-page="${i}">${i}</button>
                `);
            }
        }

        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            fetchBlogs(page); 
        });

        function updateBlog(blogId) {
            window.location.href = `http://127.0.0.1:8000/blogs/${blogId}/edit`;
        }

        function deleteBlog(blogId) {
            axios.delete(`http://127.0.0.1:8000/api/blogs/${blogId}`)
            .then(response => {
                console.log("Blog deleted successfully:", response);
                fetchBlogs(); 
            })
            .catch(error => {
                console.error("Error deleting blog:", error);
            });
        }
    });
</script>

@endsection
