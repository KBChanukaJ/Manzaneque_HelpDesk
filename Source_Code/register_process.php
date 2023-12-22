<?php
include('Connection.php');

// Function to validate if a string is a number
function isNumber($string) {
    return is_numeric($string);
}

// Function to display an alert with redirection
function showAlert($message, $redirectUrl) {
    echo "<script>alert('$message'); window.location.href = '$redirectUrl';</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $operatorID = $_POST['operatorID'];
    $operatorName = $_POST['operatorName'];
    $password = $_POST['password'];

    // Validate Operator ID is a number
    if (!isNumber($operatorID)) {
        showAlert("Operator ID must be a number.", 'register.php');
    }
    // Validate password length
    elseif (strlen($password) < 8) {
        showAlert("Password must be at least 8 characters.", 'register.php');
    } else {
        // Check if OperatorID is already registered
        $checkQuery = "SELECT OperatorID FROM HelpdeskOperators WHERE OperatorID = '$operatorID'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            showAlert("Operator ID is already registered.", 'register.php');
        } else {
            // Password hash
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert data into the HelpdeskOperators table
            $insertQuery = "INSERT INTO HelpdeskOperators (OperatorID, OperatorName, Password) VALUES ('$operatorID', '$operatorName', '$hashedPassword')";

            // Execute the query
            if ($conn->query($insertQuery) === TRUE) {
                showAlert("Registration successful!", 'login.php');
            } else {
                showAlert("Error: Registration failed. " . $conn->error, 'register.php');
            }
        }

        // Close the database connection
        $conn->close();
    }
}
?>
