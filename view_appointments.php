<?php
session_start();  // Start the session

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$patient_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");  // Redirect to the login page if not logged in
    exit();
}

// Include the database connection
include './apis/db.php';

// Fetch appointments from the database
$sql = "SELECT appointments.id,doctors.id As doctor_id, patients.name AS patient_name, doctors.name AS doctor_name, appointments.appointment_date_time 
        FROM appointments 
        JOIN patients ON appointments.patient_id = patients.id 
        JOIN doctors ON appointments.doctor_id = doctors.id ORDER BY appointments.appointment_date_time DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Management</title>
    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="./images/logo-transparent-png.png" alt="BrandLogo" class="img-fluid" style="max-height: 100px;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>

                        <li class="nav-item"> <a class="nav-link" href="#"><i class="fas fa-user" style="margin-right:5px"></i><?php echo $user_name; ?></a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="./apis/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Centered User Name -->

        </nav>
    </header>

    <!-- Content Area -->
    <div class="container content my-5">
        <div class="header-container mb-3">
            <h4 class="d-inline-block">Your Appointments</h4>

            <!-- Button to Add New Appointment -->
            <button class="btn btn-outline-success btn-sm float-right" style="width: 250px;" data-toggle="modal" data-target="#addAppointmentModal">
                <i class="fas fa-plus"></i> Add New Appointment
            </button>
        </div>



        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Doctor Name</th>
                    <th>Appointment Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['doctor_name']; ?></td>

                            <td><?php echo date('d-M-y h:i:s A', strtotime($row['appointment_date_time'])); ?></td>
                            <td>
                                <!-- Update Appointment -->
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateAppointmentModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-edit"></i> Update
                                </button>
                                <!-- Cancel Appointment -->
                                <form id="cancelForm" onsubmit="confirmCancellation(event)" method="POST" style="display:inline; ">

                                    <input type="hidden" id="patientId" name="patient_id" value=<?php echo $patient_id; ?>>
                                    <input type="hidden" id="appointmentId" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Update Appointment Modal -->
                        <div class="modal fade" id="updateAppointmentModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Appointment</h5>

                                        <i class="fas fa-times" data-dismiss="modal" style="font-size: 20px;"></i> <!-- Font Awesome close icon -->
                                    </div>
                                    <div class="modal-body">

                                        <form id="updateForm" method="POST">
                                            <div id="messageContainer" style="display: none;"> <!-- Initially hidden " -->
                                                <span id="successBadge" class="badge badge-success">Appointment successfully booked!</span>
                                            </div>
                                            <div id="errorMessage" style="display: none; color: red;text-align:center;"></div> <!-- Error message div -->


                                            <div class="form-group">

                                                <!-- Hidden input for patient ID -->
                                                <input type="hidden" id="patientId" name="patient_id" value="<?php echo $patient_id; ?>">
                                                <h6>Appointment ID : <?php echo $row['id']; ?></h6>
                                                <input type="hidden" id="appointmentId" name="appointment_id" value="<?php echo $row['id']; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="doctorIds">Select Doctor:</label>
                                                <select id="doctorIds" name="doctor_id" class="form-control" required>

                                                    <!-- Fetch doctors from the database -->
                                                    <?php
                                                    $doctorResult = $conn->query("SELECT * FROM doctors");

                                                    while ($doctor = $doctorResult->fetch_assoc()) {
                                                        $selected = ($doctor['id'] == $row['doctor_id']) ? 'selected' : '';
                                                        echo "<option value='{$doctor['id']}' $selected>{$doctor['name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">

                                                <label for="appointmentDateTime">New Date & Time:</label>
                                                <?php
                                                // Ensure the row data contains a valid datetime string
                                                $appointment_datetime = $row['appointment_date_time'];

                                                // Check if the datetime is valid before trying to format it
                                                if ($appointment_datetime) {
                                                    // Format it as required for 'datetime-local' input: Y-m-d\TH:i
                                                    $formatted_datetime = date('Y-m-d\TH:i', strtotime($appointment_datetime));
                                                } else {
                                                    // In case of an invalid datetime, set it to empty
                                                    $formatted_datetime = '';
                                                }
                                                ?>
                                                <input type="datetime-local" class="form-control" id="appointmentDateTime" name="appointment_date_time" value="<?php echo $formatted_datetime; ?>" required />
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Appointment Modal -->
    <div class="modal fade" id="addAppointmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Appointment</h5>

                    <i class="fas fa-times" data-dismiss="modal" style="font-size: 20px;"></i> <!-- Font Awesome close icon -->
                </div>
                <div class="modal-body">
                    <form id="appointmentForm" method="POST">
                        <div id="messageContainerAdd" style="display: none;"> <!-- Initially hidden -->
                            <span id="successBadgeAdd" class="badge badge-success">Appointment successfully booked!</span>
                        </div>
                        <div id="errorMessageAdd" style="display: none; color: red;text-align:center;"></div> <!-- Error message div -->
                        <div class="form-group">

                            <!-- Hidden input for patient ID -->
                            <input type="hidden" id="patientId" name="patient_id" value=<?php echo $patient_id; ?>>
                        </div>
                        <div class="form-group">
                            <label for="doctorIds">Select Doctor:</label>
                            <select id="doctorIds" name="doctor_id" class="form-control" required>
                                <option value="">Select Doctor</option>
                                <!-- Fetch doctors from the database -->
                                <?php
                                $doctorResult = $conn->query("SELECT * FROM doctors");

                                while ($doctor = $doctorResult->fetch_assoc()) {
                                    echo "<option value='{$doctor['id']}'>{$doctor['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="appointmentDateTimes">Date & Time:</label>
                            <input id="appointmentDateTimes" type="datetime-local" class="form-control" name="appointment_date_time" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-lg-start">
        <div class="text-center p-3 bg-dark text-white">
            © 2024 Doctor's Appointment Management System:
            <a class="text-white" href="#">www.doctorsappointmentmanagement.com</a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>

<script>
    function confirmCancellation(event) {

        // Show confirmation dialog
        const confirmation = confirm("Are you sure you want to cancel this appointment?");

        if (confirmCancellation) {
            event.target.submit();
        } else {
            event.preventDefault();
        }
    }



    $(document).ready(function() {

        $('#cancelForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            let patient_id = $('#patientId').val();
            let appointment_id = $('#appointmentId').val();
               

            $.ajax({
                url: './apis/cancel_appointment.php', // Your backend script for handling appointments
                type: 'POST',
                data: {
                    patient_id: patient_id,
                    appointment_id: appointment_id
                },
                success: function(response) {
                    alert("Appointment Cancelled Successfully.");

                    setTimeout(function() {
                        location.reload(); // Refresh the entire page
                    }, 2000); // Adjust the time as needed

                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);

                }
            });
        });

        $('#appointmentForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            let patient_id = $('#patientId').val();
            let doctor_id = $('#doctorIds').val();
            let dateTime = $('#appointmentDateTimes').val();


            // Future date validation for the appointment
            if (new Date(dateTime) < new Date()) {

                $('#errorMessageAdd').html("Please select a future date and time").show(); // Show error in modal
                return; // Stop execution if validation fails
            } else {
                $('#errorMessageAdd').hide(); // Hide any previous error messages
            }

            $.ajax({
                url: './apis/appointment.php', // Your backend script for handling appointments
                type: 'POST',
                data: {
                    patient_id: patient_id,
                    doctor_id: doctor_id,
                    appointment_date_time: dateTime
                },
                success: function(response) {

                    $('#appointmentForm')[0].reset(); // Reset the form

                    // Show the success badge or message
                    alert("Appointment booked successfully.");
                    // Wait for 1 seconds before reloading the page
                    setTimeout(function() {
                        location.reload();
                    }, 1000); // Adjust time as needed

                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    // Show the error message in the modal
                    console.log(xhr.responseText)
                    $('#errorMessage').html("failed to book an appointment.").show(); // Show error in modal

                }
            });
        });


        $('#updateForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            let patient_id = $('#patientId').val();
            let appointment_id = $('#appointmentId').val();
            let doctor_id = $('#doctorIds').val();
            let dateTime = $('#appointmentDateTime').val();


            // Future date validation for the appointment
            if (new Date(dateTime) < new Date()) {
                $('#errorMessage').html("Please select a future date and time").show(); // Show error in modal
                return; // Stop execution if validation fails
            } else {
                $('#errorMessage').hide(); // Hide any previous error messages
            }

            $.ajax({
                url: './apis/update_appointment.php', // Your backend script for handling appointments
                type: 'POST',
                data: {
                    patient_id: patient_id,
                    appointment_id: appointment_id,
                    doctor_id: doctor_id,
                    appointment_date_time: dateTime
                },
                success: function(response) {

                    $('#updateForm')[0].reset(); // Reset the form

                    alert("Appointment updated successfully.");
                    // Wait for 1 seconds before reloading the page
                    setTimeout(function() {
                        location.reload();
                    }, 1000); // Adjust time as needed
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    console.log(xhr.responseText)
                    // Show the error message in the modal
                    $('#errorMessage').html("Failed to update appointment").show(); // Show error in modal

                }
            });
        });
    });
</script>

</html>