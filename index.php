<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Appointment Management</title>  
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  
   <link rel="stylesheet" href="./css/style.css">
 
</head>
<body>

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                
                <a class="navbar-brand" href="#"><img src="./images/logo-transparent-png.png" alt="BrandLogo" class="img-fluid" style="max-height: 100px;"></a>
            </div>
        </nav>
    </header>

    <!-- Content Area -->
    <div class="container content my-5">
    <h2>Book An Appointment with Doctor</h2>
    <form id="emailForm" method="POST">
        <div class="form-group">
            <label>Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <!-- Placeholder for displaying the message -->
        <div id="message"></div>

        <button type="submit">Proceed</button>
    </form>
    </div>

    <!-- Footer -->
    <footer class="text-center text-lg-start">
      

        <div class="text-center p-3 bg-dark text-white">
            © 2024 Doctor's Appointment Management System:
            <a class="text-white" href="#">www.doctorsappointmentmanagement.com</a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

<script>
    
    $(document).ready(function() {
        $('#emailForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the default way
            
            var email = $('#email').val();
            
            $.ajax({
                url: './apis/check_email.php',
                type: 'POST',
                data: { email: email },
                success: function(response) {
                    // Display the returned message in the message div
                    $('#message').html(response);
                },
                error: function() {
                    $('#message').html('<p style="color: red;">An error occurred while checking the email.</p>');
                }
            });
        });
    });
</script>
</html>
