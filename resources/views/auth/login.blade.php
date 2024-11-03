@extends('layouts.main')
@section('home-section')
<div id="error" class="danger">

</div>
<div class="container d-flex justify-content-center align-items-center " style="height:75vh;">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h4 class="text-center mb-4">Login</h4>
                <div id="general-error" class="alert alert-danger" style="display: none;"></div>
                <form id="LoginForm" method="POST">
                @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input name="email" type="email" value="{{ old('email')}}" class="form-control" id="email" 
                        placeholder='example@email.com'>
                        <div class="text-danger">
         
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control" id="password">
                        <div class="text-danger">
                    </div>
                    </div>
                    <p> do not have an account? <a href="{{route('auth.registerform')}}">register</a></p>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function() {
        $('#LoginForm').on('submit', function(e) {
            e.preventDefault(); 
         
            var formData = new FormData(this);

            $.ajax({
                url: "http://127.0.0.1:8000/api/login", 
                type: 'POST',
                data: formData,
                contentType: false, 
                processData: false, 
                success: function(response) {

                
                    if (response.status === 'success') {
                        console.log("Login successful. Redirecting to blogs...");
                        window.location.href = "http://127.0.0.1:8000/blogs";
                    } else {
                        console.log("Login failed:", response.message || "No message provided");
                    }
                },
                error: function(xhr) {
                if (xhr.status === 422) {
  
                    var errors = xhr.responseJSON.errors;
          
                    $('.text-danger').text('');
                   
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            // If you have a custom error message format, adjust accordingly
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