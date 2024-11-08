<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('head')
    <title>Document</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons (choose one version) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Sidebar styling */
        .sidebar {
            position: fixed;
            top: 30; /* Adjust to match the navbar height */
            left: 0;
            width: 200px;
            height: calc(100vh - 70px); /* Full height minus navbar height */
            background-color: #343a40; /* Dark background */
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #ffffff; /* White link color */
        }
        .sidebar .nav-link:hover {
            background-color: #495057; /* Light gray hover color */
        }
        .content {
            margin-left: 200px; /* Space for sidebar */
            padding: 20px;
        }
    </style>
</head>
    

<body>
 
    <div class="bg-secondary navbar-fixed-top"> 
    <div class="container py-3 d-flex justify-content-between align-items-center">
        <a style="text-decoration:none;" href="">
            <div class="h1 text-white">{{ __('messages.title') }}</div>
            <p>@json(session('locale'))</p>
        </a>
        <div id="Auth" class="d-flex align-items-center">
           
        
        <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => 'en'])) }}">EN</a>

        <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => 'fr'])) }}">FR</a>

        </div>
    </div>
    </div>

    <!-- Sidebar -->
     <div id="sidebar">

     </div>
  

    <!-- Main Content Area -->
    <div class="content">
        @yield('home-section')
    </div>

    <script>
   
        const user = {
            id: '',
            profile_image: ''
        };
        const locale = "{{app()->getLocale()}}";
        
        
    
        function getCookie(name) {
      
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
        const authtoken= getCookie('auth_token');
        


            if(authtoken){
                $.ajax({
                url: `http://127.0.0.1:8000/api/user/`,
                type: 'GET',
               
                success: function(response) {
                   
                    user.id = response.data.user.id;
                    user.profile_image = `{{ asset('storage/images/profile/') }}/${response.data.user.id}/${response.data.user.profile_image}`;

                    renderAuthSection();
                },
                error: function(xhr) {
                    console.error("User fetch error:", xhr);
                }
            });
            }
         
           
        

        function renderAuthSection() {
            let sideBar = ` 
            <div class="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="http://127.0.0.1:8000/${locale}/blogs" class="nav-link">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="http://127.0.0.1:8000/${locale}/myblogs" class="nav-link">My Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a href="http://127.0.0.1:8000/${locale}/blogs/create" class="nav-link">Create Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" id="logoutLink" class="nav-link">Logout</a>
                    </li>
                </ul>
            </div>`;
            let authHtml = `
                <div class="profile-avatar mx-3">
                    <img src="${user.profile_image}" class="rounded-circle" width="50" height="50">
                </div>
                <button id="logoutButton" class="btn btn-danger">Logout</button>`;
            $('#sidebar').html(sideBar);
            $('#Auth').html(authHtml); 

            $('#logoutButton').on('click', function() {
                $.ajax({
                    url: "http://127.0.0.1:8000/api/logout", 
                    type: 'POST',
                    success: function(response) {
                        document.cookie = "auth_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "user_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        console.log("Logout successful:", response);

                        window.location.href = "http://127.0.0.1:8000/login"; 
                    },
                    error: function(xhr) {
                        console.error("Logout error:", xhr);
                    }
                });
            });
        }

</script>


</body>
</html>
