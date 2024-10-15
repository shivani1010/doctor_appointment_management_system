<?php
include 'db.php';
header('Content-Type: application/json'); // Set the response type to JSON


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $appointment_id = $_POST['appointment_id'];

     // Validate Patient
     $sql = "SELECT id FROM patients WHERE id = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param('s', $patient_id);
     $stmt->execute();
     $result = $stmt->get_result();
 
     if ($result->num_rows == 0) {
         echo json_encode(['status' => 'error', 'message' => 'No such patient found. Please register first.']);
     } else {


    $sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $appointment_id);

    if ($stmt->execute()) {
        echo "Appointment canceled successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
}
?>
