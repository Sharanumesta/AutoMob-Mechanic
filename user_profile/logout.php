<?php 

    @include 'db.php';

    session_start();
    $email = $_SESSION['email'];
    $con = mysqli_connect($servername, $username, $password, $database);
    $status = "UPDATE user SET status = 0 WHERE email = '$email'";
    $result2 = mysqli_query($con, $status);
    $_SESSION['logged'] = false;

    session_unset();
    session_destroy();

    header('location: login1.php');
    exit;
?>