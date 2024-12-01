<!doctype html>
<html>
<head>
    <title>Logging In</title>
</head>
<h1> Logging In </h1>
<body>
    <center>
        <?php
        session_start(); // Start the session

        // Connect to database 
        $conn = mysqli_connect("localhost", "root", "", "hotel_db");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $CID = $_REQUEST['custID']; // Customer ID
        $CPWD = $_REQUEST['CustPassword']; // Customer Password

        // Get custID and password from database
        $sql = "SELECT * FROM customer WHERE custID = '$CID'";
        $custLog = mysqli_query($conn, $sql);
        $checkcustID = mysqli_num_rows($custLog);
        $pwd = mysqli_fetch_array($custLog);

        // Checks if customer ID and password are correct
        if (($checkcustID == 0) || ($CPWD != $pwd['CustPassword'])) {
            echo ('Incorrect username or password -
            <a href="CustomerLogin.html">Please try again</a>.');
            mysqli_close($conn);
        } else {
            // Store customer info in the session
            $_SESSION['custID'] = $pwd['custID'];
            $_SESSION['CustPassword'] = $pwd['CustPassword'];

            mysqli_close($conn);
            header("Location: customerPage.html");
            exit(); // Stop script execution after redirection
        }
        ?>
    </center>
</body>
</html>

