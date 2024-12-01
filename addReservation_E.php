<?php

$conn = new mysqli("localhost", "root", "", "hotel_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $custID = $_POST['custID'];
    $roomNum = $_POST['roomNum'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];

    // Check availability
    $availabilitySql = "SELECT * FROM reservations
                        WHERE roomNum = '$roomNum'
                        AND (
                             (checkInDate <= '$checkOutDate' AND checkOutDate >= '$checkInDate')
                        )";
    $availabilityResult = $conn->query($availabilitySql);

    if ($availabilityResult->num_rows > 0) {
        echo "The room is not available for the selected dates. Please choose another room or modify the dates.";
    } else {
        // Get room tier and rate
        $rateSql = "SELECT rt.rate 
                    FROM RoomTier rt 
                    JOIN Room r ON r.roomTier = rt.roomTier 
                    WHERE r.roomNum = '$roomNum'";
        $billrow = $conn->query($rateSql);
        $rate = $billrow->fetch_array()[0] ?? null;

        if ($rate !== null) {
            // Calculate bill
            $diff = strtotime($checkOutDate) - strtotime($checkInDate);
            $nights = ceil($diff / (60 * 60 * 24)); // Calculate number of nights
            $bill = $nights * $rate; // Calculate total bill based on rate and nights

            // Generate unique reservation ID
            do {
                $reservationID = "RES" . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                $checkIDSql = "SELECT * FROM reservations WHERE reservationID = '$reservationID'";
                $idResult = $conn->query($checkIDSql);
            } while ($idResult->num_rows > 0);

            // Add reservation
            $reservationSql = "INSERT INTO reservations (reservationID, custID, roomNum, bill, checkInDate, checkOutDate)
                               VALUES ('$reservationID', '$custID', '$roomNum', '$bill', '$checkInDate', '$checkOutDate')";

            if ($conn->query($reservationSql) === TRUE) {
                // Update room availability to unavailable
                $updateAvailabilitySql = "UPDATE Room SET availability = 0 WHERE roomNum = '$roomNum'";
                if ($conn->query($updateAvailabilitySql) === TRUE) {
                    echo "Reservation added successfully! Reservation ID: $reservationID<br>";
                    echo "Room availability updated.";
                } else {
                    echo "Reservation added, but failed to update room availability: " . $conn->error;
                }
            } else {
                echo "Error: " . $reservationSql . "<br>" . $conn->error;
            }
        } else {
            echo "Rate for the room tier not found.";
        }
    }
}

$conn->close();
?>

<!-- Back to Portal Button -->
<form action="EmployeePortal.html" method="get">
    <button type="submit">Back to Portal</button>
</form>
