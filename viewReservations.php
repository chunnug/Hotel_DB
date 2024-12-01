<?php 
// Start the session to access the stored custID
session_start();

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "hotel_db");

// Check if the customer is logged in and has a valid custID
if (!isset($_SESSION['custID'])) {
    die("Error: Please log in first to view your reservations.");
}

$custID = $_SESSION['custID'];

// Query to fetch reservations for the logged-in customer
$sql = "SELECT * FROM reservations WHERE custID = $custID";
$all_res = mysqli_query($conn, $sql);

// Check for errors in the query
if (!$all_res) {
    die("Error fetching reservations: " . mysqli_error($conn));
}
?>

<!doctype html>
<html>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<h1>Your Reservations</h1>
<button onclick="location.href = 'customerPage.html'" id="viewReservationsBackButton">Back</button>
<table style="width: 100%">
   <tr>
      <th>Reservation ID</th>
      <th>Bill</th>
      <th>Check-In Date</th>
      <th>Check-Out Date</th>
   </tr>

   <?php
   // Display reservations for the logged-in customer
   while ($res = mysqli_fetch_array($all_res, MYSQLI_ASSOC)):;
   ?>
   <tr>
      <td><?php echo htmlspecialchars($res['reservationID']); ?></td>
      <td><?php echo htmlspecialchars($res['bill']); ?></td>
      <td><?php echo htmlspecialchars($res['checkInDate']); ?></td>
      <td><?php echo htmlspecialchars($res['checkOutDate']); ?></td>
   </tr>
   <?php 
   endwhile; 
   ?>
</table>
</html>
