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

// Initialize variables for search and display
$searchKeyword = "";
$errorMessage = "";

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize search keyword
    $searchKeyword = filter_input(INPUT_POST, 'searchKeyword', FILTER_SANITIZE_STRING);
}

// Retrieve specialists based on the search keyword
$sql = "SELECT * FROM Specialists WHERE SpecialistName LIKE '%$searchKeyword%' OR Specialty LIKE '%$searchKeyword%'";
$result = $conn->query($sql);

// Check for errors
if ($result === FALSE) {
    $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
    <title>View Specialists</title>
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
                <h1 class="h2">View Specialists</h1>
            </div>

            <!-- Display error message if any -->
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Search Bar -->

            <form class="mb-3" method="post" action="view_specialists.php">
            <div class="input-group">
                <input type="text" class="form-control" name="searchKeyword" placeholder="Search by Name or Specialty" value="<?php echo $searchKeyword; ?>">
                <div class="input-group-append">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
                </div>
            </div>
            </form>

            <!-- Display specialists -->
            <table class="table">
                <thead>
                <tr>
                    <th>Specialist ID</th>
                    <th>Specialist Name</th>
                    <th>Specialty</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['SpecialistID']; ?></td>
                        <td><?php echo $row['SpecialistName']; ?></td>
                        <td><?php echo $row['Specialty']; ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
