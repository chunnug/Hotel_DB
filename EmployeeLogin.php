<?php
session_start(); // Start the session

// Connect to database 
$conn = mysqli_connect("localhost", "root", "", "hotel_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve form data
$eid = isset($_POST['empID']) ? $_POST['empID'] : '';
$pwd = isset($_POST['password']) ? $_POST['password'] : '';

// Query the database
$sql = "SELECT * FROM employee WHERE empID = '$eid' AND employeePassword = '$pwd'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Store employee ID in session
    $_SESSION['empID'] = $eid;

    // Redirect to EmployeePortal.html
    mysqli_close($conn);
    header("Location: EmployeePortal.html");
    exit(); // Stop script execution after redirect
} else {
    echo "Invalid Employee ID or Password. <a href='EmployeeLogin.html'>Try again</a>.";
    mysqli_close($conn);
}
?>

