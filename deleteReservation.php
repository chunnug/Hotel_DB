<?php

$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservationID = $_POST['reservationID'];

    // Get room number
    $sql = "SELECT roomNum FROM reservations WHERE reservationID = '$reservationID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $roomNum = $row['roomNum'];

        // Delete reservation and update room availability
        if ($conn->query("DELETE FROM reservations WHERE reservationID = '$reservationID'") === TRUE) {
            $conn->query("UPDATE Room SET availability = 1 WHERE roomNum = '$roomNum'");
            echo "Reservation deleted and room availability updated.";
        } else {
            echo "Error deleting reservation.";
        }
    } else {
        echo "Reservation not found.";
    }
}

$conn->close();
?>

<!-- Back to Portal Button -->
<form action="EmployeePortal.html" method="get">
    <button type="submit">Back to Portal</button>
</form>