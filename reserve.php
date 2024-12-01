<?php
session_start(); // Start the session

// Connect to database 
$con = mysqli_connect("localhost", "root", "", "hotel_db");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hlocation = $_POST['hlocation'];
    $checkIn = $_POST['CheckIn'];
    $checkOut = $_POST['CheckOut'];
    $guests = $_POST['Guests'];
    $roomTier = $_POST['roomTier'];
    $roomFloor = $_POST['roomFloor'];
    $firstName = $_POST['First_Name'];
    $lastName = $_POST['Last_Name'];

    // Check if the user is logged in and fetch custID from session
    if (isset($_SESSION['custID'])) {
        $custID = $_SESSION['custID'];
    } else {
        // If custID is not in the session, fetch it from the database using the name
        $customerSql = "SELECT custID FROM customer WHERE CFName = '$firstName' AND cLName = '$lastName'";
        $customerResult = mysqli_query($con, $customerSql);
        if ($customerResult && mysqli_num_rows($customerResult) > 0) {
            $customerRow = mysqli_fetch_assoc($customerResult);
            $custID = $customerRow['custID'];
            $_SESSION['custID'] = $custID; // Save custID in session for future use
        } else {
            die("Error: Customer not found. Please log in or register first.");
        }
    }

    // Generate unique reservation ID
    do {
        $reservationID = "RES" . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $checkIDSql = "SELECT * FROM reservations WHERE reservationID = '$reservationID'";
        $idResult = mysqli_query($con, $checkIDSql);
    } while (mysqli_num_rows($idResult) > 0);

    // Find an available room matching the criteria
    $roomSql = "SELECT roomNum FROM Room 
                WHERE roomTier = '$roomTier' 
                AND floor = '$roomFloor' 
                AND availability = 1 
                LIMIT 1";
    $roomResult = mysqli_query($con, $roomSql);

    if ($roomResult && mysqli_num_rows($roomResult) > 0) {
        $room = mysqli_fetch_assoc($roomResult);
        $roomNum = $room['roomNum'];

        // Calculate bill using correct logic
        $rateSql = "SELECT rate FROM RoomTier WHERE roomTier = '$roomTier'";
        $rateResult = mysqli_query($con, $rateSql);
        if ($rateResult && mysqli_num_rows($rateResult) > 0) {
            $rateRow = mysqli_fetch_assoc($rateResult);
            $rate = $rateRow['rate'];

            $diff = strtotime($checkOut) - strtotime($checkIn);
            $nights = ceil($diff / (60 * 60 * 24)); // Calculate nights
            $bill = $nights * $rate; // Calculate total bill
        } else {
            die("Error: Room rate not found.");
        }

        // Insert reservation into the database
        $insertSql = "INSERT INTO reservations (reservationID, custID, roomNum, bill, checkInDate, checkOutDate) 
                      VALUES ('$reservationID', '$custID', '$roomNum', '$bill', '$checkIn', '$checkOut')";

        if (mysqli_query($con, $insertSql)) {
            // Update room availability to unavailable (0)
            $updateRoomSql = "UPDATE Room SET availability = 0 WHERE roomNum = '$roomNum'";
            if (mysqli_query($con, $updateRoomSql)) {
                echo "Reservation added successfully! Room $roomNum is now unavailable.";
            } else {
                echo "Reservation added, but failed to update room availability: " . mysqli_error($con);
            }
        } else {
            echo "Error adding reservation: " . mysqli_error($con);
        }
    } else {
        echo "No available rooms match your criteria. Please choose different options.";
    }
}

mysqli_close($con);
?>

<!-- Back to Customer Page Button -->
<form action="customerPage.html" method="get">
    <button type="submit">Back to Customer Page</button>
</form>



