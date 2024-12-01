<?php
// Connect to database 
$con = mysqli_connect("localhost", "root", "", "hotel_db");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all from hotel table
$sql = "SELECT * FROM `hotel`";
$all_locs = mysqli_query($con, $sql);

// Get data from roomtier table
$sql = "SELECT * FROM `roomtier`";
$all_roomtier = mysqli_query($con, $sql);
?>

<!doctype html>
<html>
<head>
    <title>Add a Reservation</title>
</head>
<body>
    <h1>Add a Reservation</h1>

    <!-- Single Form -->
    <form action="reserve.php" method="POST">
        <!-- Plan Your Visit -->
        <details>
            <summary>Plan Your Visit</summary>
            <p>
                <label>Select a Location:</label>
                <select name="hlocation">
                    <?php 
                        while ($locs = mysqli_fetch_array($all_locs, MYSQLI_ASSOC)):; 
                    ?>
                        <option value="<?php echo $locs["hlocation"]; ?>">
                            <?php echo $locs["hlocation"]; ?>
                        </option>
                    <?php 
                        endwhile; 
                    ?>
                </select>
                <br><br>

                <label for="CheckIn">Check-In Date:</label>
                <input type="text" name="CheckIn" placeholder="YYYY-MM-DD">
                <br><br>

                <label for="CheckOut">Check-Out Date:</label>
                <input type="text" name="CheckOut" placeholder="YYYY-MM-DD">
                <br><br>

                <label for="Guests">Guests:</label>
                <input type="text" name="Guests">
                <br><br>

                <label>Room Type:</label>
                <select name="roomTier">
                    <?php 
                        while ($roomtier = mysqli_fetch_array($all_roomtier, MYSQLI_ASSOC)):; 
                    ?>
                        <option value="<?php echo $roomtier["roomTier"]; ?>">
                            <?php echo $roomtier["roomTier"]; ?>
                        </option>
                    <?php 
                        endwhile; 
                    ?>
                </select>
                <label>Room Floor:</label>
                <input type="text" name="roomFloor">
                <br><br>
            </p>
        </details>

        <!-- Guest Information and Payment -->
        <details>
            <summary>Guest Information and Payment</summary>
            <p>
                <label for="First_Name">First Name:</label>
                <input type="text" name="First_Name" placeholder="Guest's First Name">
                <br><br>

                <label for="Last_Name">Last Name:</label>
                <input type="text" name="Last_Name" placeholder="Guest's Last Name">
                <br><br>

                <label for="Street">Address:</label>
                <input type="text" name="Street" placeholder="Guest's Address">
                <br><br>

                <label for="City">City:</label>
                <input type="text" name="City" placeholder="Guest's City">
                <br><br>

                <label for="State">State:</label>
                <input type="text" name="State" placeholder="Guest's State">
                <br><br>

                <label for="Country">Country:</label>
                <input type="text" name="Country" placeholder="Guest's Country">
                <br><br>

                <label for="Zip_Code">Zip Code:</label>
                <input type="text" name="Zip_Code" placeholder="Guest's Zip Code">
                <br><br>

                <label for="Card_Number">Card Number:</label>
                <input type="text" name="Card_Number" placeholder="Guest's Saved Card Number">
                <br><br>

                <label for="CVV">CVV:</label>
                <input type="text" name="CVV" placeholder="Guest's Saved Card CVV">
                <br><br>

                <label for="Expiration_Date">Expiration Date:</label>
                <input type="text" name="Expiration_Date" placeholder="YYYY-MM">
                <br><br>
            </p>
        </details>

        <!-- Submit and Cancel Buttons -->
        <button type="submit" id="addReservationSubmitButton">Submit Reservation</button>
    </form>

    <button onclick="location.href='customerPage.html'" id="addReservationBackButton">Cancel</button>
</body>
</html>
