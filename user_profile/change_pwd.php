<?php

@include 'db.php';
session_start();

$email = $_SESSION['email'];

$con = mysqli_connect($servername, $username, $password, $database);

$attempts_exhausted = 0;
$block = 1 ;
$confirmNewPassword = $newPassword = '' ;
$otp_err = $newpassErr = $confnewpassErr = '';

$otp = random_int(111111, 999999);
$to = $email;
$subject = "Email verification with One Time Password";
$msg = 'One Time Password : ' . $otp . '. This one time password will only valid for 60 seconds!';
$headers = "From: webmaster@example.com";

// $flag = mail($to,$subject,$msg,$headers);
$flag = '';
if($flag){
    $sql = "UPDATE `user` SET `otp`= $otp, `date_time` = now() WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    $block = 1 ;
}

// block 1
if(isset($_POST['verify'])){
    $otp = $_POST['otp'];
    $sql2 = "SELECT * FROM `user` WHERE email = '$email'";
    $result = mysqli_query($con,$sql2);
    $row = mysqli_fetch_assoc($result);

    if($row > 0){
        $rowotp = $row['otp'];
        if($otp === $rowotp){
            $sql = "UPDATE `user` SET `date_time` = now() ,`otp_status` = 1, `otp_attempts` = 0 ,`mail_status` = 1  WHERE email = '$email'";
            mysqli_query($con,$sql);
            $block = 2 ;
        }else{
            $sql1 = "SELECT * FROM `user` WHERE email = '$email'";
            $result = mysqli_query($con,$sql1);
            $row1 = mysqli_fetch_assoc($result) ;
            $otp_attempts = $row1['otp_attempts'];

            $otp_attempts ++ ;
            $otp_err = 'Invalid OTP. ' . 4-$otp_attempts . ' attempts left';

            $sql3 = "UPDATE `user` SET `otp_attempts` = $otp_attempts WHERE email = '$email'";
            mysqli_query($con,$sql3);

            if($otp_attempts > 3 ){
                $sql4 = "UPDATE `user` SET `otp_attempts` = 0 WHERE email = '$email'";
                mysqli_query($con,$sql4);
                ?>
                    <script>
                        window.addEventListener('load', function(){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Number of attempts exhausted.'
                            })
                        });
                        setTimeout(function() {
                            window.location.href = "profile.php";
                        }, 2000);
                    </script>
                <?php
            }
        }
    }
}

// block 2

if(isset($_POST['change'])){
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    if(empty($newPassword) && empty($confirmPassword)){
        $newPassErr = "Password cannot be empty!";
        $confirmNewPassErr = "Password cannot be empty!";
    }

    if(empty($confirmPassword)){
        $confirmNewPassErr = "Password cannot be empty!";
    }else if($newPassword != $confirmPassword){
        $confirmNewPassErr = "Confirm Password does not match!";   
    }

    if(empty($newPassword)){
        $newpassErr = "Password cannot be empty!";
    } else {
        if (strlen($newPassword)<8) {
            $newpassErr ="Password must contain at least 8 character";
        }else if(!preg_match("/[A-Z]+/", $newPassword)){
            $newpassErr = "Password must contain at least one uppercase letter!";
        }else if(!preg_match("/[a-z]+/", $newPassword)){
            $newpassErr = "Password must contain at least one lowercase letter!";
        }else if(!preg_match("/[^\w\s]+/", $newPassword)){
            $newpassErr = "Password must contain at least one special character!";
        }else{
            $sql3 = "UPDATE `user` SET password = '$newPassword' WHERE email = '$email' ";
            $result3 = mysqli_query($con, $sql3);
            if($result3){
                $_SESSION['status'] = true;
                header('location: profile.php');
            }
        }
    }
}

?>
<!DOCTYPE html>
<head>
    <title>Change password</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
    crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body class=''>
<div class="container">
    <div class="row d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="col-6">
            <div class="row d-flex justify-content-center align-items-center ">
                <form action="#" method="POST">
                    <!-- block 1 -->
                    <?php
                        if ($block == 1){
                    ?>
                        <div class="col-12 ">
                            <div class="row rounded-3 border-0 shadow-lg col-12">
                                <div class="col-1"></div>
                                <div class="col-10 p-5">
                                    <div class="">
                                        <div class=" text-center">
                                            <label for="phone" class="form-label name h5 text-center mb-4">Enter the OTP sent to your email</label>
                                            <input type="text" class="form-control name text-center input_field" name="otp" placeholder="Enter OTP">
                                            <div class="p-3">
                                                <span id="error" class="text-danger"><?php echo $otp_err ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button class="send mt-2 fw-bold " name="verify">Verify</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                    
                    <!-- block 2 -->
                    <?php
                        if ($block == 2){
                    ?>
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10 border-0 rounded-3 shadow-lg p-5">
                            <div class="space text-uppercase text-center fw-bold name h5">
                                <p>Password change</p>
                                <hr class="mb-5">
                            </div>
                            <div class="mb-4 px-3">
                                <input type="password" class="form-control fw-semibold input_field" id="newPassword" placeholder='New Password' name="newPassword" value="<?php echo $newPassword ?>">
                                <span id="error" class="text-danger"><?php echo $newpassErr ?></span>
                            </div>
                            <div class="mb-2  px-3">
                                <input type="password" class="form-control fw-semibold input_field" id="confirmPassword" placeholder='Confirm Password' name="confirmNewPassword" value="<?php echo $confirmNewPassword?>">
                                <span id="error" class="text-danger"><?php echo $confnewpassErr ?></span>
                            </div>
                            <div class="row ">
                                <div class="col d-flex justify-content-end">
                                    <div class="checkBox">
                                        <input type="checkbox" id="check" onclick="showPassword()">
                                        <label for="check" class=" name">Show Password</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button class="send my-2 mb-4 fw-bold" name="change">Change</button>               
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </from>
            </div>
        </div>
    </div>
</div>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
        }
</script>
<script>
    function showPassword(){
        var newPassword = document.getElementById('newPassword');
        var confirmPassword = document.getElementById('confirmPassword');

        if(newPassword.type == 'password'){
            newPassword.type = 'text';
            confirmPassword.type = 'text';
        }else{
            newPassword.type = 'password';
            confirmPassword.type = 'password';
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>