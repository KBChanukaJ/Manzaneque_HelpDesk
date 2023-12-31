<?php
include('Connection.php');

// Function to display an alert with redirection
function showAlert($message, $redirectUrl) {
    echo "<script>alert('$message'); window.location.href = '$redirectUrl';</script>";
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $operatorID = $_POST['operatorID'];
    $password = $_POST['password'];

    // Validate Operator ID is a number
    if (!is_numeric($operatorID)) {
        showAlert("Invalid Operator ID.", 'login.php');
    } else {
        // Check if OperatorID and password match
        $checkQuery = "SELECT * FROM HelpdeskOperators WHERE OperatorID = '$operatorID'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                // Login successful
                $_SESSION['operatorID'] = $operatorID;

                // Redirect based on role
                $role = $row['Role'];
                if ($role === 'HelpDeskOperator') {
                    showAlert("Login successful! Redirecting to Dashboard.", 'dashboard.php');
                } elseif ($role === 'Specialist') {
                    showAlert("Login successful! Redirecting to Specialist Dashboard.", 'specialist_dash.php');
                } else {
                    showAlert("Invalid role.", 'login.php');
                }
            } else {
                showAlert("Invalid password.", 'login.php');
            }
        } else {
            // Redirect to registration page if Operator ID not found
            showAlert("Operator ID not found. Redirecting to registration.", 'register.php');
        }
    }
}
?>
