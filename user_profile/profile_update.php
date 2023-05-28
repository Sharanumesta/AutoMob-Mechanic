<?php
    @include 'db.php';
    
    session_start();
    
    if(!$_SESSION['logged']){
        header('location : login.php');
    }
    
    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);


    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
        $sql = "SELECT * FROM `user` WHERE email ='$email'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $phone = $row['phone'];
        $password = $row['password'];
        $noChangeError = $nameError = $numError = $phoneError = $emailError = '';
    }
 
    if(isset($_POST['update'])){
        $updated_name = $_POST['name'];
        $updated_email = $_POST['email'];
        $updated_phone = $_POST['phone'];
        $updated_password = $_POST['password'];

        //No modification validation
        if($updated_name == $name && $updated_email == $email && $updated_phone == $phone ){
            $noChangeError = "No Changes Made!";
        }else{
            $noChangeError = "";
        }

        //Name field validation
        if($updated_name == ''){
            $nameError = "Name is required!";
        }else{
            $nameError = "";
        }

        //Phone field validation
        if($updated_phone == ''){
            $phoneError = "Phone number is required!";
        }else{
            $phoneError = "";
        }

        //Email field validation
        if($updated_email == ''){
            $emailError = "Email address is required!";
        }else if(!filter_var($updated_email, FILTER_VALIDATE_EMAIL)){
            $emailError = "Invalid Email address!";
        }else if($updated_email != $email){
            $sql3 = "SELECT * FROM user WHERE email = '$updated_email'";
            $result3 = mysqli_query($con, $sql3);
            $num = mysqli_num_rows($result3);
            if($num > 0){
                $emailError = "Email address already exist!";
            }else{    
                $emailError = "";
            }
        }else{    
            $emailError = "";
        }

        //Phone number field validation
        if(strlen($updated_phone)>0 && strlen($updated_phone)<10 || strlen($updated_phone)>10){
            $numError = "Please enter 10 digit phone number!";
        }else{
            $numError = "";
        }
        
        //Current field value updation
        if($nameError != '' || $numError != '' || $phoneError != '' || $emailError != ''){
            $name = $updated_name;
            $email = $updated_email;
            $phone = $updated_phone;
        }

        //db query execution 
        if($nameError == '' && $numError == '' && $phoneError == '' && $emailError == '' && $noChangeError == ''){

            $sql2 = "UPDATE user SET email = '$updated_email', name = '$updated_name', phone = '$updated_phone' WHERE email = '$email'";
            $result2 = mysqli_query($con, $sql2);

            if($result2){
                $_SESSION['email'] = $updated_email;
                $_SESSION['status'] = true;
                header('location: profile.php');
            }else{
                $_SESSION['email'] = $email;
            }
        }
    }
?>

<!DOCTYPE html>
<head>
    <title>Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile.css">

</head>
<body class="bg-light">
    <!-- navbar start -->
    <nav class="navbar navbar-expand navbar-dark nav pt-4 pb-4">
    <div class="container">
    <i><a href="profile.php" class="navbar-brand space fw-bold fs-3 rounded p-1" style="color:#05445E">Profile</a></i>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="mail.php" class="nav-link active ms-2 me-2 fw-bold" id='hover'>Mail</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link  ms-2 me-2 fw-bold dropdown-toggle" style="color:#05445E;transform:scale(1.1);" id='hover' href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Update
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item fw-bold" href="profile_update.php" style="color:#05445E;background:#BDFFF3" name="profile">Profile</a></li>
            <li><a class="dropdown-item" href="password_update.php"  name="password">Password</a></li>
          </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active ms-2 me-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modal" id='hover'>Logout</a>
            <div class="modal fade" id="modal">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:#beeae2;">
                        <div class="modal-header">
                           <p class="fw-bold">Confirm Logout</p> 
                        </div>
                        <div class="modal-body mt-2 mb-2 ps-5 ">
                           <p class="fs-5">Are you sure! you want to logout?</p> 
                        </div>
                        <div class="modal-footer">
                            <a href="logout.php"><button class="btn btn-success">Yes</button></a>
                            <button class="btn btn-danger" data-bs-dismiss="modal" data-bs-target="#modal">No</button> 
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    </div>
    </nav>
    <!-- navbar End -->

    <!-- Form area -->
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5 mt-5 pt-3 border-0 rounded-3 shadow-lg">
                <form class='p-4' action="#" method="POST">
                    <span id="error" class="text-danger fw-semibold d-flex justify-content-center"><?php echo $noChangeError?></span>
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control input_field" name="name" value="<?php echo $name ?>">
                        <span id="error fw-semibold"><?php echo $nameError?></span>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="text" class="form-control input_field" name="email" value="<?php echo $email ?>">
                        <span id="error fw-semibold"><?php echo $emailError?></span>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Phone</label>
                        <input type="text" class="form-control input_field" name="phone" value="<?php echo $phone ?>">
                        <span id="error fw-semibold"><?php echo $phoneError?></span>
                        <span id="error fw-semibold"><?php echo $numError?></span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control input_field " name="password" readonly value="........">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="send mb-2 fw-bold" name="update">Update</button>               
                        </div>
                    </div>
                </from>
            </div>  
        </div>
    </div>
    <!-- Form area end-->

    <!-- prevents form resubmission -->
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
    crossorigin="anonymous"></script>
</body>
</html>