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
        const userId = localStorage.getItem('user'); 
        const token = localStorage.getItem('auth_token');
        const blogsUrl = `http://127.0.0.1:8000/api/user/${userId}/blogs`;

        if (userId && token) {
            fetchBlogs(1); // Fetch first page by default
        } else {
            console.error("User ID or auth token missing.");
        }

        function fetchBlogs(page = 1) {
            axios.get(`${blogsUrl}?page=${page}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                const blogs = response.data.data.blogs;
                const currentPage = response.data.current_page; // Assuming this data comes from your backend
                const totalPages = response.data.total_pages; // Assuming this data comes from your backend

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

                // Render pagination links
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
            $('#paginationContainer').empty(); // Clear existing pagination links
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = (i === currentPage) ? 'active' : '';
                $('#paginationContainer').append(`
                    <button class="btn btn-link pagination-link ${activeClass}" data-page="${i}">${i}</button>
                `);
            }
        }

        // Handle pagination link clicks
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            fetchBlogs(page); // Fetch blogs for the selected page
        });

        function updateBlog(blogId) {
            window.location.href = `http://127.0.0.1:8000/blogs/${blogId}/edit`;
        }

        function deleteBlog(blogId) {
            axios.delete(`http://127.0.0.1:8000/api/blogs/${blogId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                console.log("Blog deleted successfully:", response);
                fetchBlogs(); // Fetch blogs again after deletion
            })
            .catch(error => {
                console.error("Error deleting blog:", error);
            });
        }
    });
</script>
@endsection
