<?php
session_start();

// Include the database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // SQL to check if the email exists in the patients table
    $sql = "SELECT * FROM patients WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the email is found, redirect to the next screen (e.g., view_appointments.php)
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Fetch the user data
        
        // Store the user's data in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
      // Return success message with optional redirect
      echo "<p style='color: green;'>Email is valid! Redirecting to the next page...</p>";
      echo "<script>setTimeout(function(){ window.location.href = '../view_appointments.php'; }, 500);</script>";

        exit();  // Make sure no further code is executed
    } else {
        // Email not found, show an error message
        echo "<p style='color: red;'>Email id not registered in system.</p>";
       
    }
}
?>
