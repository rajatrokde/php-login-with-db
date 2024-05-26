<?php
    ob_start();
    session_start();
    include_once 'config.php';

    // Connect to server and select database.
    $conn = mysqli_connect($host, $username, $password, $db_name);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Define $myusername, $mypassword, and $myemail
    $myusername = $_POST['myusername'];
    $mypassword = $_POST['mypassword'];
    $myemail = $_POST['myemail'];

    // To protect MySQL injection
    $myusername = stripslashes($myusername);
    $mypassword = stripslashes($mypassword);
    $myemail = stripslashes($myemail);
    $myusername = mysqli_real_escape_string($conn, $myusername);
    $mypassword = mysqli_real_escape_string($conn, $mypassword);
    $myemail = mysqli_real_escape_string($conn, $myemail);

    // Hashing the password using SHA1 and salt="batman is here"
    $salt = "batman is here"; // Assuming $salt is defined in your config.php
    $hashed_password = sha1($mypassword . $salt);

    $sql = "SELECT * FROM $tbl_name WHERE email='$myemail'";
    $result = mysqli_query($conn, $sql);

    $count = mysqli_num_rows($result);
    if ($count != 0) {
        echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Email ID already exists</div>";
    } else {
        $sql = "INSERT INTO $tbl_name (`id`, `username`, `password`,`email`) VALUES (NULL,'$myusername', '$hashed_password', '$myemail')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['username'] = $myusername;
            $_SESSION['password'] = $hashed_password;
            echo "true";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
    ob_end_flush();
?>
