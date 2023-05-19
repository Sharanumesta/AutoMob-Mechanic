<?php

    @include 'db.php';

    session_start();

    if(!$_SESSION['logged']){
        header('location: login1.php');
    }

    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // $email = 'test@gmail.com';
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM `user` WHERE email ='$email'";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);

    if($num < 1){
        header('location: login1.php');
    }

    if($_SESSION['status']){
    ?>
        <script>
           window.addEventListener('load', function(){
            swal({
                title: "Updated Successfully!",
                icon: "success",
                timer: 1000
            }).then((result) =>{
                    location.reload();
                }
            );
           });
        </script>
    <?php
    $_SESSION['status'] = false;
    }
?>

<!DOCTYPE html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body class="space">
    
    <!-- navbar start -->
    <nav class="navbar navbar-expand navbar-dark nav pt-4 pb-4">
        <div class="container">
            <i><a href="profile.php" class="navbar-brand fw-bold fs-3 rounded p-1" style="color:#05445E">Profile</a></i>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="mail1.php" class="nav-link active ms-2 me-2 fw-bold" id='hover'>Mail</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link  ms-2 me-2 fw-bold dropdown-toggle" id='hover' href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Update
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="profile_update.php"  name="profile">Profile</a></li>
                    <li><a class="dropdown-item" href="password_update.php"  name="password">Password</a></li>
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

    <!-- Information Start-->
    <div class="">
        <?php 
            while($row = mysqli_fetch_assoc($result)){
        ?>
            <div class="row d-flex align-item-center justify-content-center m-5 cursor-pointer">
                <div class="col-7 board ">
                    <div class="row cardbg d-flex justify-content-center align-items-center container-fixed-height rounded-3 p-2">
                        <div class="col profilecard rounded-3 h-100">
                            <div class=" box text-center" style="color:#05445E">
                                <i class="fa fa-user fa-7x m-5" style="font-size: 100px;"></i>
                                <h2 class="fw-bold text-uppercase"><?php echo $row['name']?></h2>
                            </div>
                        </div>
                        
                        <div class="col ">
                            <div class="box center">
                                <div class="board w-75" style="color:#05445E">
                                    <div class="row">
                                        <p class="fw-bold">Email : </p>
                                        <p class="ps-5"><?php echo $row['email']?></p>
                                    </div>
                                    <div class="row">
                                        <p class="fw-bold">Phone : </p>
                                        <p class="ps-5"><?php echo $row['phone']?></p>
                                    </div>
                                    <div class="row">
                                        <p class="fw-bold">Role : </p>
                                        <p class="ps-5"><?php echo $row['role']?></p>
                                    </div>
                                    <div class="row">
                                        <p class="fw-bold pb-0">Password : </p>
                                        <div class="row ps-5">
                                            <div class="col-5">
                                                <p class="align-item-center justify-content-center" id="password" style="display:none"><?php echo $row['password']?></p>
                                                <p class="h4 text-center align-item-center justify-content-center" id="hidepass">********</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>
    </div>
    <!-- Information End-->

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
    crossorigin="anonymous"></script>

</body>
</html>