<?php
session_start();

// Include the database connection file
include('Connection.php');

// Function to get status color
function getStatusColor($status)
{
    return ($status === 'Active') ? 'green' : 'red';
}

// Check if the user is logged in
if (!isset($_SESSION['operatorID'])) {
    header("Location: login.php");
    exit();
}

// Get operator ID from session
$operatorID = $_SESSION['operatorID'];

$operatorQuery = "SELECT OperatorName FROM HelpdeskOperators WHERE OperatorID = '$operatorID'";
$operatorResult = $conn->query($operatorQuery);
$operatorData = $operatorResult->fetch_assoc();
$operatorName = ($operatorData) ? $operatorData['OperatorName'] : '';

// Fetch all problem details
$problemsQuery = "SELECT * FROM Problems";
$problemsResult = $conn->query($problemsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
    <title>View Problems</title>
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
                    <!-- Add other sidebar items as needed -->
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">View Problems</h1>
            </div>

            <div class="container mt-4">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Problem Number</th>
                        <th>Caller ID</th>
                        <th>Operator ID</th>
                        <th>Problem Type ID</th>
                        <th>Problem Description</th>
                        <th>Time Reported</th>
                        <th>Time Resolved</th>
                        <th>Resolution Details</th>
                        <th>Specialist ID</th>
                        <th>Equipment ID</th>
                        <th>Software ID</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $problemsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['ProblemNumber']; ?></td>
                            <td><?php echo $row['CallerID'] ?? 'None'; ?></td>
                            <td><?php echo $row['OperatorID'] ?? 'None'; ?></td>
                            <td><?php echo $row['ProblemTypeID'] ?? 'None'; ?></td>
                            <td><?php echo $row['ProblemDescription']; ?></td>
                            <td><?php echo $row['TimeReported']; ?></td>
                            <td><?php echo $row['TimeResolved'] ?? 'None'; ?></td>
                            <td><?php echo $row['ResolutionDetails'] ?? 'None'; ?></td>
                            <td><?php echo $row['SpecialistID'] ?? 'None'; ?></td>
                            <td><?php echo $row['EquipmentID'] ?? 'None'; ?></td>
                            <td><?php echo $row['SoftwareID'] ?? 'None'; ?></td>
                            <td style="color: <?php echo getStatusColor($row['Status']); ?>"><?php echo $row['Status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
