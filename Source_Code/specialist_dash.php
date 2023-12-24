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

// Function to get the count of records from a table
function getRecordCount($table) {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Get counts
$problemCount = getRecordCount('Problems');
$personnelCount = getRecordCount('Personnel');
$specialistsCount = getRecordCount('Specialists');
$equipmentCount = getRecordCount('Equipment');
$softwareCount = getRecordCount('Software');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
    <title>Dashboard</title>
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
                        <a class="nav-link active" href="specialist_dash.php">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sp_view_problem.php">
                            View Problem
                        </a>
                    </li>
                    <!-- Add more business functions as needed -->
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <div class="row">
                <!-- Card for Problems Count -->
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Problems</h5>
                            <p class="card-text">Total: <?php echo $problemCount; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card for Personnel Count -->
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Personnel</h5>
                            <p class="card-text">Total: <?php echo $personnelCount; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card for Specialists Count -->
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Specialists</h5>
                            <p class="card-text">Total: <?php echo $specialistsCount; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card for Equipment Count -->
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Equipment</h5>
                            <p class="card-text">Total: <?php echo $equipmentCount; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Software</h5>
                            <p class="card-text">Total: <?php echo $softwareCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Your additional content goes here -->
            <!-- ... -->
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
