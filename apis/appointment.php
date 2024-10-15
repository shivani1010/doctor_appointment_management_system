<?php
include 'db.php';
header('Content-Type: application/json'); // Set the response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date_time = $_POST['appointment_date_time'];

    // Validate Patient
    $sql = "SELECT id FROM patients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'No such patient found. Please register first.']);
    } else {
        $patient = $result->fetch_assoc();
        $patient_id = $patient['id'];

        // Insert Appointment
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $patient_id, $doctor_id, $appointment_date_time);

       
    if ($stmt->execute()) {
        // Email found, send a success message in JSON format
        echo json_encode(['status' => 'success', 'message' => 'success']);
    } else {
        // Email not found, send an error message in JSON format
        echo json_encode(['status' => 'error', 'message' => 'failed']);
    }
    }
}
?>
