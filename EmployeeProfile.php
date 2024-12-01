<?php
session_start(); // Start the session

// Check if the employee is logged in
if (!isset($_SESSION['empID'])) {
    die("Error: You must be logged in to view your profile. <a href='EmployeeLogin.html'>Login</a>");
}

// Get the employee's ID from the session
$empID = $_SESSION['empID'];

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "hotel_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to get employee details
$sql = "SELECT * FROM employee WHERE empID = '$empID'";
$result = mysqli_query($conn, $sql);

// Fetch employee data
if ($result && $result->num_rows > 0) {
    $employee = $result->fetch_assoc();
} else {
    die("Error: Employee not found.");
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Employee Profile</title>
</head>
<body>
  <div id="employeeProfile">
    <h1 style="font-size:10vw">Employee Profile</h1>
    <p>Employee ID: <?php echo htmlspecialchars($employee['empID']); ?></p>
    <p>First Name: <?php echo htmlspecialchars($employee['eFName']); ?></p>
    <p>Last Name: <?php echo htmlspecialchars($employee['eLName']); ?></p>
    <p>Department: <?php echo htmlspecialchars($employee['eDept']); ?></p>
    <p>Job Title: <?php echo htmlspecialchars($employee['jobTitle']); ?></p>
    <p>Join Date: <?php echo htmlspecialchars($employee['joinDate']); ?></p>
  </div>

  <!-- Return home button -->
  <button onclick="window.location.href='EmployeePortal.html'">Back to Portal</button>
</body>
</html>
