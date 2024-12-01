<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomNum = $_POST['roomNum'];

    // Establish database connection directly in this file
    $conn = new mysqli("localhost", "root", "", "db_hotel");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch room details and join with roomTier table to get the rate
    $sql = "SELECT r.RoomNum, r.Floor, r.Availability, r.NumBeds, r.RoomTier, r.RoomView, rt.Rate
            FROM rooms r
            LEFT JOIN roomTier rt ON r.RoomTier = rt.TierID
            WHERE r.RoomNum = '$roomNum'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output room details if the room is found
        while ($row = $result->fetch_assoc()) {
            echo "Room Number: " . $row['RoomNum'] . "<br>";
            echo "Floor: " . $row['Floor'] . "<br>";
            echo "Availability: " . ($row['Availability'] ? "Yes" : "No") . "<br>";
            echo "Rate: $" . ($row['Rate'] !== NULL ? $row['Rate'] : 'Not Available') . "<br>";
            echo "Number of Beds: " . $row['NumBeds'] . "<br>";
            echo "Room Tier: " . $row['RoomTier'] . "<br>";
            echo "Room View: " . $row['RoomView'] . "<br><hr>";
        }
    } else {
        // If no room found, show an error message
        echo "No room found.";
    }

    // Close the database connection
    $conn->close();
}
?>
