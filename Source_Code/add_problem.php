<?php
session_start();

include('Connection.php');

function showAlert($message, $redirectUrl) {
    echo "<script>alert('$message'); window.location.href = '$redirectUrl';</script>";
    exit();
}

if (!isset($_SESSION['operatorID'])) {
    header("Location: login.php");
    exit();
}

$operatorID = $_SESSION['operatorID'];

$personnelQuery = "SELECT PersonnelID, Name FROM Personnel";
$personnelResult = $conn->query($personnelQuery);

// Fetch problem types for the dropdown
$problemTypesQuery = "SELECT ProblemTypeID, ProblemTypeName FROM ProblemTypes"; // Changed ProblemTypeDescription to ProblemTypeName
$problemTypesResult = $conn->query($problemTypesQuery);


$specialistsQuery = "SELECT SpecialistID, SpecialistName FROM Specialists";
$specialistsResult = $conn->query($specialistsQuery);

$equipmentQuery = "SELECT EquipmentID, EquipmentMake FROM Equipment";
$equipmentResult = $conn->query($equipmentQuery);

$softwareQuery = "SELECT SoftwareID, SoftwareName FROM Software";
$softwareResult = $conn->query($softwareQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $problemNumber = $_POST['problemNumber'];
    $callerID = $_POST['callerID'];
    $problemTypeID = $_POST['problemTypeID'];
    $problemDescription = $_POST['problemDescription'];
    $timeReported = date("Y-m-d H:i:s");

    if (!preg_match('/^[0-9]{10}$/', $problemNumber)) {
        showAlert("Invalid phone number format for Problem Number. Please enter a 10-digit phone number.", 'add_problem.php');
    }

    $assignedSpecialist = isset($_POST['assignedSpecialist']) ? $_POST['assignedSpecialist'] : null;

    $equipmentOrSoftware = isset($_POST['equipmentOrSoftware']) ? $_POST['equipmentOrSoftware'] : null;
    $selectedID = null;

    $equipmentID = null;
$softwareID = null;

if ($equipmentOrSoftware === 'Equipment') {
    $equipmentID = isset($_POST['equipmentID']) ? $_POST['equipmentID'] : null;
} elseif ($equipmentOrSoftware === 'Software') {
    $softwareID = isset($_POST['softwareID']) ? $_POST['softwareID'] : null;
}

$insertSql = "INSERT INTO Problems (ProblemNumber, CallerID, OperatorID, ProblemTypeID, ProblemDescription, TimeReported, SpecialistID, EquipmentID, SoftwareID, Status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";

$stmt = $conn->prepare($insertSql);
if ($stmt === false) {
    showAlert("Error preparing statement: " . $conn->error, 'add_problem.php');
}

$stmt->bind_param('siiissiii', $problemNumber, $callerID, $operatorID, $problemTypeID, $problemDescription, $timeReported, $assignedSpecialist, $equipmentID, $softwareID);

if ($stmt->execute()) {
    showAlert("Problem added successfully!", 'dashboard.php');
} else {
    showAlert("Error: " . $stmt->error, 'add_problem.php');
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
    <title>Add Problem</title>
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
                    <!-- Add other sidebar items as needed -->
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add Problem</h1>
            </div>

            <!-- Display your form here -->
            <form action="" method="post">

                <!-- ProblemNumber field is entered by the user -->
                <div class="form-group">
                    <label for="problemNumber">Problem Number:</label>
                    <input type="tel" class="form-control" id="problemNumber" name="problemNumber" pattern="[0-9]{10}" placeholder="Enter a 10-digit phone number" required>
                    <small class="form-text text-muted">Please enter a 10-digit phone number.</small>
                </div>

                <!-- CallerID is selected from the dropdown -->
                <div class="form-group">
                    <label for="callerID">Caller ID:</label>
                    <select class="form-control" id="callerID" name="callerID" required>
                        <option value="">Choose...</option>
                        <?php while ($row = $personnelResult->fetch_assoc()): ?>
                            <option value="<?php echo $row['PersonnelID']; ?>"><?php echo $row['Name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- OperatorID is filled automatically with the logged-in operator's ID -->
                <input type="hidden" name="operatorID" value="<?php echo $operatorID; ?>">

                <!-- ProblemTypeID is selected from the dropdown -->
                <div class="form-group">
                    <label for="problemTypeID">Problem Type:</label>
                    <select class="form-control" id="problemTypeID" name="problemTypeID" required>
                     <option value="">Choose...</option>
                     <?php while ($row = $problemTypesResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['ProblemTypeID']; ?>"><?php echo $row['ProblemTypeName']; ?></option> <!-- Changed ProblemTypeDescription to ProblemTypeName -->
                        <?php endwhile; ?>
                    </select>

                </div>

                <!-- ProblemDescription is entered by the user -->
                <div class="form-group">
                    <label for="problemDescription">Problem Description:</label>
                    <textarea class="form-control" id="problemDescription" name="problemDescription" rows="3" required></textarea>
                </div>

                <!-- Assigned Specialist is selected from the dropdown -->
                <div class="form-group">
                    <label for="assignedSpecialist">Assigned Specialist:</label>
                    <select class="form-control" id="assignedSpecialist" name="assignedSpecialist">
                        <option value="">Choose...</option>
                        <?php while ($row = $specialistsResult->fetch_assoc()): ?>
                            <option value="<?php echo $row['SpecialistID']; ?>"><?php echo $row['SpecialistName']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Choose Equipment or Software -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="equipmentOrSoftware" id="equipmentRadio" value="Equipment">
                    <label class="form-check-label" for="equipmentRadio">
                        Equipment
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="equipmentOrSoftware" id="softwareRadio" value="Software">
                    <label class="form-check-label" for="softwareRadio">
                        Software
                    </label>
                </div>

                <!-- Select Equipment from the dropdown (visible when Equipment is chosen) -->
                <div class="form-group" id="equipmentDropdown" style="display: none;">
                    <label for="equipmentID">Select Equipment:</label>
                    <select class="form-control" id="equipmentID" name="equipmentID">
                        <option value="">Choose...</option>
                        <?php
                        // Reset the result pointer to the beginning
                        $equipmentResult->data_seek(0);
                        while ($row = $equipmentResult->fetch_assoc()):
                        ?>
                            <option value="<?php echo $row['EquipmentID']; ?>"><?php echo $row['EquipmentMake']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Select Software from the dropdown (visible when Software is chosen) -->
                <div class="form-group" id="softwareDropdown" style="display: none;">
                    <label for="softwareID">Select Software:</label>
                    <select class="form-control" id="softwareID" name="softwareID">
                        <option value="">Choose...</option>
                        <?php
                        // Reset the result pointer to the beginning
                        $softwareResult->data_seek(0);
                        while ($row = $softwareResult->fetch_assoc()):
                        ?>
                            <option value="<?php echo $row['SoftwareID']; ?>"><?php echo $row['SoftwareName']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Status is pre-filled with 'Active' -->
                <input type="hidden" name="status" value="Active">

                <!-- Add a submit button -->
                <button type="submit" class="btn btn-primary">Submit</button>

            </form>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Show/hide equipment/software dropdowns based on radio button selection
    $('input[name="equipmentOrSoftware"]').change(function() {
        if (this.value === 'Equipment') {
            $('#equipmentDropdown').show();
            $('#softwareDropdown').hide();
        } else if (this.value === 'Software') {
            $('#softwareDropdown').show();
            $('#equipmentDropdown').hide();
        }
    });
</script>

</body>
</html>
