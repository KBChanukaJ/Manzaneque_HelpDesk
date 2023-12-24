<?php
session_start();

// Include the database connection file
include('Connection.php');

// Function to display an alert with redirection
function showAlert($message, $redirectUrl) {
    echo "<script>alert('$message'); window.location.href = '$redirectUrl';</script>";
    exit();
}

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

// Fetch problems for the logged-in operator
$problemsQuery = "SELECT * FROM Problems WHERE SpecialistID = '$operatorID'";
$problemsResult = $conn->query($problemsQuery);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $problemNumber = $_POST['problemNumber'];
    $resolutionDetails = $_POST['resolutionDetails'];
    $timeResolved = date("Y-m-d H:i:s"); // Get current timestamp

    $call = $conn->prepare("CALL UpdateProblemStatus(?, ?)");
    $call->bind_param("is", $problemNumber, $resolutionDetails);
    $call->execute();

    // Prepare the SQL query
    $updateSql = "UPDATE Problems SET ResolutionDetails = ?, TimeResolved = ? WHERE ProblemNumber = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($updateSql);
    if ($stmt === false) {
        showAlert("Error preparing statement: " . $conn->error, 'view_problems.php');
    }

    // Bind the form data to the prepared statement
    $stmt->bind_param('ssi', $resolutionDetails, $timeResolved, $problemNumber);

    // Execute the query
    if ($stmt->execute()) {
        showAlert("Problem updated successfully!", 'sp_view_problem.php');
    } else {
        showAlert("Error: " . $stmt->error, 'view_problems.php');
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
                        <a class="nav-link" href="specialist_dash.php">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sp_view_problem.php">
                            View Problem
                        </a>
                    </li>
                    <!-- Add other sidebar items as needed -->
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="container mt-4">
                <div class="pb-2 mb-3 border-bottom">
                    <h1 class="h2">View Problems</h1>
                </div>

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
                        <th>Status</th>
                        <th>Action</th>
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
                            <td style="color: <?php echo getStatusColor($row['Status']); ?>"><?php echo $row['Status']; ?></td>
                            <td>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#resolutionModal<?php echo $row['ProblemNumber']; ?>" <?php echo ($row['Status'] === 'Closed') ? 'disabled' : ''; ?>>
                                    Get Action
                                </button>
                            </td>

                        </tr>

                        <!-- Resolution Modal -->
                        <div class="modal fade" id="resolutionModal<?php echo $row['ProblemNumber']; ?>" tabindex="-1" role="dialog" aria-labelledby="resolutionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resolutionModalLabel">Add Resolution Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label for="resolutionDetails">Resolution Details:</label>
                                                <textarea class="form-control" id="resolutionDetails" name="resolutionDetails" rows="3" required></textarea>
                                            </div>
                                            <input type="hidden" name="problemNumber" value="<?php echo $row['ProblemNumber']; ?>">
                                            <button type="submit" class="btn btn-primary">OK</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
