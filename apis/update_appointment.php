<?php
include 'db.php';
header('Content-Type: application/json'); // Set the response type to JSON


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $new_date_time = $_POST['appointment_date_time'];
    $doctor_id = $_POST['doctor_id'];

    $sql = "UPDATE appointments SET doctor_id=?, appointment_date_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isi',$doctor_id, $new_date_time, $appointment_id);

    if ($stmt->execute()) {
        // Email found, send a success message in JSON format
        echo json_encode(['status' => 'success', 'message' => 'success']);
    } else {
        // Email not found, send an error message in JSON format
        echo json_encode(['status' => 'error', 'message' => 'failed']);
    }
}
?>
