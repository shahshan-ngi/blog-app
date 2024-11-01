@extends('layouts.main')
@section('home-section')
@include('shared.message')

<div class="container d-flex justify-content-center align-items-center " style="height:90vh;">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h4 class="text-center mb-4">Register</h4>
                <form id="RegisterForm"  method="POST" enctype="multipart/form-data">
                @csrf
               
                <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input name="name"  value="{{ old('name') }}" type="text" class="form-control" id="name" placeholder='example' required>
                        <div class="text-danger">
                
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input name="email" type="email"  value="{{ old('email') }}" class="form-control" id="email" placeholder='example@email.com' required>
                        <div class="text-danger">
                 
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password"  value="{{ old('password') }}" class="form-control" id="password" required>
                        <div class="text-danger">
            
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" id="profile_image" accept="image/*">
                        <div class="text-danger">
                    </div>
                    </div>
                    <p> Already have an account? <a href="{{route('login')}}">login</a></p>
                    <button  class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script>
  $(document).ready(function() {
    $("#RegisterForm").on('submit', function(e) {
        e.preventDefault(); 

  
        var formData = new FormData(this);

        $.ajax({
            url: "http://127.0.0.1:8000/api/register",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if (response.data.auth_token) {
                    localStorage.setItem('auth_token', response.data.auth_token);
                    localStorage.setItem('user',response.data.user.id);
                    console.log("Token stored in local storage:", response.data.auth_token);
                    window.location.href = "http://127.0.0.1:8000/blogs";
                } else {
                    console.log("No token found in response");
                }
             
            },
            error: function(xhr) {
                if (xhr.status === 422) {
  
                    var errors = xhr.responseJSON.errors;
          
                    $('.text-danger').text('');
                   
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                           
                            var errorMessage = errors[field][0]; // Get the first error message for the field
                            $('#' + field).next('.text-danger').text(errorMessage); // Set error message next to the field
                        }
                    }
                 } 
                 else if (xhr.status === 401) {
                    $('#general-error').text(xhr.responseJSON.message || 'Unauthorized access.').show();
      
                } 
                else {
                    console.log("Error logging in:", xhr);
                }
            }
        });
    });
});

    </script>
@endsection           