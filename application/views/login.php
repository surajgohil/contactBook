<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.0.4/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .signup-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar-expanded {
            width: 250px;
        }
        .sidebar-collapsed {
            width: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-form">
            <h2 class="text-center">Sign In</h2>
            <form id="loginInForm">
                <!-- <div class="mb-3">
                    <label for="name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="name" name="firstName" placeholder="Enter your first name" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="name" name="lastName" placeholder="Enter your last name" required>
                </div> -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <!-- <div class="mb-3">
                    <label for="email" class="form-label">Mobile Number</label>
                    <input type="number" class="form-control" id="mobileNumber" name="number" placeholder="Enter your number" required>
                </div> -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                </div>
                <!-- <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div> -->
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
                Have an account?
                <a href="signUp" class="text-decoration-none">
                    Sign Up.
                    <i class="fa fa-external-link" aria-hidden="true"></i>
                </a>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#loginInForm').on('submit', function(e) {
                e.preventDefault();

                let form = new FormData(this);
                let password = $('#password').val();

                $('.displayError').remove();

                $.ajax({
                    url  : '<?= base_url("UserAction/login") ?>',
                    type : 'POST',
                    data : form,
                    contentType: false,
                    processData: false,
                    success : function(response){

                        response = JSON.parse(response);
                        console.log(response);

                        if(response.status === 3){
                            $.each(response.data, function(key, value) {
                                console.log('key : ',key);
                                console.log('value : ',value);
                                $(`[name="${key}"]`).after(`<span class="displayError text-danger">${value}</span>`);
                            });
                        }

                        if(response.status === 1){
                            window.location.href = "<?= base_url('Dashboard'); ?>";
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>