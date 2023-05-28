<?php

    @include 'db.php';

    session_start();
    
    if(!$_SESSION['logged']){
        header('location : login.php');
    }

    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);

    $email = $_SESSION['email'];

    $currentPassword = $newPassword = $confirmNewPassword = '';
    $currentPassErr = $newpassErr = $confnewpassErr = '';
    $otperror = '';
    $otpPassErr = $otpPassErr2 = '' ;
    
    
    if(isset($_POST['update'])){
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmNewPassword = $_POST['confirmNewPassword'];
        
        $currentPassErr = $newpassErr = $confnewpassErr = '';

        if(empty($currentPassword) && empty($newPassword) && empty($confirmNewPassword)){
            $currentPassErr ="Enter current password";
            $newpassErr ='Enter new password';
            $confnewpassErr = "Confirm new password";
        }

        if(empty($currentPassword)){
            $currentPassErr = "Enter current password";
        }else{
            $sql = "SELECT * FROM `user` WHERE `email`= '$email' ";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);

            if($row > 0){
                $hashpass = $row['password'];
    
                if(password_verify($currentPassword,$hashpass)){
                    if(empty($newPassword)){
                        $newpassErr ='Enter new password';
                    }else if (strlen($newPassword) < 8) {
                        $newpassErr="Password must contain at least 8 character";
                    }else if(!preg_match("/[A-Z]+/", $newPassword)){
                        $newpassErr = "Password must contain at least one uppercase letter!";
                    }else if(!preg_match("/[a-z]+/", $newPassword)){
                        $newpassErr = "Password must contain at least one lowercase letter!";
                    }else if(!preg_match("/[^\w\s]+/", $newPassword)){
                        $newpassErr = "Password must contain at least one special character!";
                    }else if(empty($confirmNewPassword)){
                        $confnewpassErr="Confirm new password";
                    }else{
                        if($newPassword == $confirmNewPassword){
                            if($currentPassErr == '' && $newpassErr == '' && $confnewpassErr == ''){
                                $hashNewPassword = password_hash($newPassword,PASSWORD_DEFAULT);
                                $sql1 = "UPDATE `user` SET `password` = '$hashNewPassword' WHERE `email` = '$email'";
                                $result2 = mysqli_query($con, $sql1);

                                if($result2){
                                    ?>
                                        <script>
                                            window.addEventListener('load', function(){
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Update Successful',
                                                    text: 'Password has been successfully changed'
                                                })
                                            }).then(function() {
                                                window.location.href = 'profile.php';
                                            });
                                        </script>
                                    <?php
                                }else{
                                    $_SESSION['status'] = false;
                                    header('location: password_update.php');
                                }
                            }
                        }else{
                            $confnewpassErr = "Password does not match!";
                        }
                    }
                }else{
                    $currentPassErr = 'Incorrect Password';
                    if(empty($newPassword)){
                        $newpassErr ='Enter new password';
                    }else if (strlen($newPassword) < 8) {
                        $newpassErr="Password must contain at least 8 character";
                    }else if(!preg_match("/[A-Z]+/", $newPassword)){
                        $newpassErr = "Password must contain at least one uppercase letter!";
                    }else if(!preg_match("/[a-z]+/", $newPassword)){
                        $newpassErr = "Password must contain at least one lowercase letter!";
                    }else if(!preg_match("/[^\w\s]+/", $newPassword)){
                        $newpassErr = "Password must contain at least one special character!";
                    }
                    if(empty($confirmNewPassword)){
                        $confnewpassErr="Confirm new password";
                    }else{
                        if($newPassword != $confirmNewPassword){
                            $confnewpassErr = "Password does not match!";
                        }
                    }
                }
            }
        }
    }

?>

<!DOCTYPE html>
<head>
    <title>Password Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body class="">
    <!-- navbar start -->
    <nav class="navbar navbar-expand navbar-dark nav pt-4 pb-4">
    <div class="container">
    <i><a href="profile.php" class="navbar-brand space fw-bold fs-3 rounded p-1" style="color:#05445E">Profile</a></i>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="mail.php" class="nav-link active ms-2 me-2 fw-bold" id='hover'>Mail</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link  ms-2 me-2 fw-bold dropdown-toggle" style="color:#05445E;transform: scale(1.1);" id='hover' href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Update
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="profile_update.php"  name="profile">Profile</a></li>
            <li><a class="dropdown-item fw-bold" href="password_update.php" style="color:#05445E;background:#BDFFF3" name="password">Password</a></li>
          </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active ms-2 me-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modal" id='hover'>Logout</a>
            <div class="modal fade" id="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                           <p class="fw-bold">Confirm Logout</p> 
                        </div>
                        <div class="modal-body mt-2 mb-2 ps-5 ">
                           <p class="fs-5">Are you sure! you want to logout?</p> 
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-bs-dismiss="modal" data-bs-target="#modal">No</button> 
                            <a href="logout.php"><button class="btn btn-success">Yes</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div>
</nav>
<!-- navbar End -->
<div class="container p-5 ">
    <div class="row d-flex justify-content-center">
        <div class="col-5 p-5 pb-3 border-0 rounded-3 shadow-lg">
            <form action="#" method="POST">
                <div class="space text-uppercase text-center fw-bold name h5">
                    <p>Password Update</p>
                    <hr class="mb-4">
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control input_field" id="currentPassword" placeholder='Current Password' name="currentPassword" value="<?php echo $currentPassword?>">
                    <span id="error" style="font-size: 0.75rem;" class="text-danger "><?php echo $currentPassErr ?></span>
                </div>
                <div class="mb-4">
                        <input type="password" class="form-control input_field" id="newPassword" placeholder='New Password' name="newPassword" value="<?php echo $newPassword?>">
                        <span id="error"  style="font-size: 0.75rem;" class="text-danger"><?php echo $newpassErr ?></span>
                    </div>
                    <div class="mb-2">
                        <input type="password" class="form-control input_field" id="confirmPassword" placeholder='Confirm New Password' name="confirmNewPassword"  value="<?php echo $confirmNewPassword?>">
                        <span id="error" style="font-size: 0.75rem;" class="text-danger"><?php echo $confnewpassErr ?></span>
                    </div>
                    <div class="row ">
                        <div class="col"></div>
                        <div class="col-6 d-flex justify-content-end">
                            <div class="checkBox">
                                <input type="checkbox" id="check" onclick="showPassword()">
                                <label for="check" class=" name">Show Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="send mt-2 fw-bold" name="update">Update</button>               
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <p class='name' style="font-size: 0.75rem;">
                            Can't remember current password ?
                            <a href="forgot_password.php" name='updatepass' class='fw-bold name cursor-pointer' style="font-size: 0.80rem">Forgot your password ?</a>
                        </p>
                    </div>
                </div>
            </from>
        </div>
    </div>
</div>

<!-- prevents form resubmission -->
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    //Display password 
    function showPassword(){
        var currentPassword = document.getElementById("currentPassword")
        var newPassword = document.getElementById("newPassword");
        var confirmPassword = document.getElementById("confirmPassword");

        if (currentPassword.type == 'password'){
            currentPassword.type = 'text';
            newPassword.type = 'text';
            confirmPassword.type = 'text';
        }else{
            currentPassword.type = 'password';
            newPassword.type = 'password';
            confirmPassword.type = 'password';
        }
    }
</script>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
    crossorigin="anonymous"></script>
</body>
</html>