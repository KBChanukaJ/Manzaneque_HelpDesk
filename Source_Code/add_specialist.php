<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['operatorID'])) {
    header("Location: login.php");
    exit();
}

// Get operator ID from session
$operatorID = $_SESSION['operatorID'];

// Include the database connection file
include('Connection.php');

// Initialize variables for form data and validation
$specialistID = $specialistName = $specialty = "";
$specialistIDError = $specialistNameError = $specialtyError = "";
$successMessage = $errorMessage = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form data
    $specialistID = filter_input(INPUT_POST, 'specialistID', FILTER_SANITIZE_NUMBER_INT);
    $specialistName = filter_input(INPUT_POST, 'specialistName', FILTER_SANITIZE_STRING);
    $specialty = filter_input(INPUT_POST, 'specialty', FILTER_SANITIZE_STRING);

    // Validate Specialist ID
    if (empty($specialistID)) {
        $specialistIDError = "Specialist ID is required.";
    }

    // Check if Specialist ID is already registered
    $checkQuery = "SELECT * FROM Specialists WHERE SpecialistID = '$specialistID'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        $errorMessage = "Specialist ID '$specialistID' is already registered.";
    } else {
        // Validate Specialist Name
        if (empty($specialistName)) {
            $specialistNameError = "Specialist Name is required.";
        }

        // Validate Specialty
        if (empty($specialty)) {
            $specialtyError = "Specialty is required.";
        }

        // If no errors, proceed with database operation
        if (empty($specialistIDError) && empty($specialistNameError) && empty($specialtyError)) {
            // Insert data into the Specialists table
            $sql = "INSERT INTO Specialists (SpecialistID, SpecialistName, Specialty) VALUES ('$specialistID', '$specialistName', '$specialty')";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                $successMessage = "Specialist added successfully!";
            } else {
                $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
    <title>Add Specialist</title>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Manzaneque Limited</span>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text mr-3">Welcome, <?php echo $operatorID; ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_problem.php">
                            Add Problem
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_problem.php">
                            View Problem
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_personnel.php">
                            Add Personnel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_personnel.php">
                            View Personnel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="add_specialist.php">
                            Add Specialist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_specialists.php">
                            View Specialists
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="add_equipment.php">
                            Add Equipment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_equipment.php">
                            View Equipment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="add_software.php">
                            Add Software
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_software.php">
                            View Software
                        </a>
                    </li>
                    <!-- Add more business functions as needed -->
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add Specialist</h1>
            </div>

            <!-- Display success or error messages if any -->
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Form for adding specialists -->
            <form action="add_specialist.php" method="post">
                <div class="form-group">
                    <label for="specialistID">Specialist ID:</label>
                    <input type="number" class="form-control" id="specialistID" name="specialistID" required>
                    <small class="text-danger"><?php echo $specialistIDError; ?></small>
                </div>
                <div class="form-group">
                    <label for="specialistName">Specialist Name:</label>
                    <input type="text" class="form-control" id="specialistName" name="specialistName" required>
                    <small class="text-danger"><?php echo $specialistNameError; ?></small>
                </div>
                <div class="form-group">
                    <label for="specialty">Specialty:</label>
                    <input type="text" class="form-control" id="specialty" name="specialty" required>
                    <small class="text-danger"><?php echo $specialtyError; ?></small>
                </div>
                <button type="submit" class="btn btn-primary">Add Specialist</button>
            </form>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
