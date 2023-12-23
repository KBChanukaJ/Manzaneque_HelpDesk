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

// Initialize variables for form input and validation
$softwareID = "";
$softwareName = "";
$licenseStatus = "";
$errorMessage = "";
$successMessage = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $softwareID = filter_input(INPUT_POST, 'softwareID', FILTER_SANITIZE_NUMBER_INT);
    $softwareName = filter_input(INPUT_POST, 'softwareName', FILTER_SANITIZE_STRING);
    $licenseStatus = filter_input(INPUT_POST, 'licenseStatus', FILTER_SANITIZE_STRING);

    // Check if SoftwareID is already registered
    $checkExistenceSql = "SELECT * FROM Software WHERE SoftwareID = '$softwareID'";
    $existenceResult = $conn->query($checkExistenceSql);
    if ($existenceResult->num_rows > 0) {
        $errorMessage = "Software with ID $softwareID is already registered.";
    } else {
        // Insert data into the Software table
        $insertSql = "INSERT INTO Software (SoftwareID, SoftwareName, LicenseStatus) 
                      VALUES ('$softwareID', '$softwareName', '$licenseStatus')";
        
        // Execute the query
        if ($conn->query($insertSql) === TRUE) {
            $successMessage = "Software registration successful!";
        } else {
            $errorMessage = "Error: " . $insertSql . "<br>" . $conn->error;
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
    <title>Add Software</title>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Your Company Name</span>
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
                        <a class="nav-link" href="add_specialist.php">
                            Add Specialist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_specialists.php">
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
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add Software</h1>
            </div>

            <!-- Display error or success message if any -->
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Software Form -->
            <form action="add_software.php" method="post">
                <div class="form-group">
                    <label for="softwareID">Software ID:</label>
                    <input type="number" class="form-control" id="softwareID" name="softwareID" required>
                </div>
                <div class="form-group">
                    <label for="softwareName">Software Name:</label>
                    <input type="text" class="form-control" id="softwareName" name="softwareName" required>
                </div>
                <div class="form-group">
                    <label for="licenseStatus">License Status:</label>
                    <select class="form-control" id="licenseStatus" name="licenseStatus" required>
                        <option value="1">Valid</option>
                        <option value="0">Expired</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Software</button>
            </form>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
