<?php
$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservationID = $_POST['reservationID'];
    $roomNum = $_POST['roomNum'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];

    // Get room rate using the billrow approach
    $rateSql = "SELECT rt.rate FROM RoomTier rt 
                JOIN Room r ON r.roomTier = rt.roomTier 
                WHERE r.roomNum = '$roomNum'";
    $billrow = $conn->query($rateSql);
    $rate = $billrow->fetch_array()[0] ?? null;

    if ($rate) {
        // Calculate bill
        $diff = strtotime($checkOutDate) - strtotime($checkInDate);
        $nights = ceil($diff / (60 * 60 * 24)); // Calculate nights
        $bill = $nights * $rate; // Calculate total bill

        // Update reservation
        $updateSql = "UPDATE reservations 
                      SET roomNum = '$roomNum', checkInDate = '$checkInDate', 
                          checkOutDate = '$checkOutDate', bill = '$bill' 
                      WHERE reservationID = '$reservationID'";
        if ($conn->query($updateSql) === TRUE) {
            // Update room availability
            $availabilitySql = "UPDATE Room SET availability = false WHERE roomNum = '$roomNum'";
            if ($conn->query($availabilitySql) === TRUE) {
                echo "Reservation updated successfully!<br>";
                echo "Room availability updated.";
            } else {
                echo "Reservation updated, but failed to update room availability: " . $conn->error;
            }
        } else {
            echo "Error updating reservation: " . $conn->error;
        }
    } else {
        echo "Room or rate not found.";
    }
}

$conn->close();
?>

<!-- Back to Portal Button -->
<form action="EmployeePortal.html" method="get">
    <button type="submit">Back to Portal</button>
</form>