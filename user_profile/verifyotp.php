<?php

@include 'db.php';
session_start();

$email = $_SESSION['email'];

$con = mysqli_connect($servername, $username, $password, $database);
$otp_err = '';

if(isset($_POST['validate-otp'])){
    
    $otp = $_POST['otp'];
    $sql2 = "SELECT * FROM `user` WHERE otp = '$otp'";
    $result = mysqli_query($con,$sql2);
    $row = mysqli_num_rows($result);
    
    if($row > 0){
        $sql = "UPDATE `user` SET `date_time` = now() ,`otp_status` = 1, `otp_attempts` = 0 ,`mail_status` = 1  WHERE email = '$email'";
        mysqli_query($con,$sql);
        // header('location: profile.php');
        ?>
            <script>
                window.addEventListener('load', function(){
                    Swal.fire({
                        icon: 'Success',
                        title: 'Account Verified',
                        text: 'Your account has been successfully verified.'
                    })
                });
                setTimeout(function() {
                    window.location.href = "profile.php";
                });
            </script>
        <?php
    }else{
        $sql1 = "SELECT otp_attempts FROM `user` WHERE email = '$email'";
        $result = mysqli_query($con,$sql1);
        $row1 = $result->fetch_assoc();
        $otp_attempts = $row1['otp_attempts'];

        $otp_attempts ++ ;
        $otp_err = 'Invalid OTP. ' . 4-$otp_attempts . ' attempts left';

        $sql3 = "UPDATE `user` SET `otp_attempts` = $otp_attempts WHERE email = '$email'";
        mysqli_query($con,$sql3);

        if($otp_attempts > 3){
            $sql4 = "DELETE FROM `user` WHERE email = '$email'";
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
                        window.location.href = "register.php";
                    }, 3000);
                </script>
            <?php
        }
    }
}

?>
<!DOCTYPE html>
<head>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body class=''>
    <div class="otpcontainer">
        <div class="container banner">
            <div class="row">
                <div class="col">
                    <form class="nav border-0 rounded-3" action="" method="post">
                        <div class="container height-100 d-flex justify-content-center align-items-center">
                            <div class="position-relative ">
                                <div class="name p-2 text-center p-5">
                                    <p class="h5">Please enter the one time password <br> to verify your account</p>
                                    <div>
                                        <span>A code has been sent to your Email</span>
                                    </div>
                                    <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                                        <input class="m-2 text-center form-control rounded input_field" name='otp' type="text" placeholder='One Time Password'/>
                                    </div>
                                    <span class="text-danger"><?php echo $otp_err ?></span>
                                    <div class="mt-4">
                                        <button class="send fw-bold validate" name='validate-otp'>Validate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>