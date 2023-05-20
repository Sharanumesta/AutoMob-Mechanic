<?php

    @include 'db.php';
    
    session_start();

    $con = mysqli_connect($servername, $username, $password, $database);

	function testinput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $nameErr = $phErr = $emailErr = $passErr = $passmatchErr = "" ;
    $name_value = $phone_value = $email_value = $pass_value = $pass2_value="";

    //get form data
    if(isset($_POST['register'])){
        $name=$_POST['name'];
        $phone=$_POST['phone'];
        $email=$_POST['email'];
        $password=$_POST['pass'];
        $confirmpass=$_POST['pass2'];

        //name validation
        if (empty($name)) {
		    $nameErr = "Name is required";
            $phone_value=$phone;
            $email_value=$email;
            $pass_value=$password;
            $pass2_value=$confirmpass;
	    } 
        else {
            $username = testinput($name);
	        // check if name only contains letters and whitespace
	        if (!preg_match("/^[a-z A-Z ]*$/",$username)) {
	            $nameErr = "Only letters and white space allowed";
                $phone_value=$phone;
                $email_value=$email;
                $pass_value=$password;
                $pass2_value=$confirmpass;
	        }
        }
        
        //Phone number validation
        if(empty($phone)){
            $phErr="Phone number is required";
            $name_value=$name;
            $email_value=$email;
            $pass_value=$password;
            $pass2_value=$confirmpass;
        }else if(!preg_match("/^[1-9]{10}$/",$phone)) {
	            $phErr = "Invalid phone number";
                $name_value=$name;
                $email_value=$email;
                $pass_value=$password;
                $pass2_value=$confirmpass;
	        }

        // Validate email
        if(empty($email)){
            $emailErr="Email is required";
            $name_value=$name;
            $phone_value=$phone;
            $pass_value=$password;
            $pass2_value=$confirmpass;
        }else {
            $email=testinput($email);
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $emailErr="Invalid email address";
                $name_value=$name;
                $phone_value=$phone;
                $pass_value=$password;
                $pass2_value=$confirmpass;
            }else{
        
                $sql= "SELECT * FROM `user` WHERE email = '$email'";
                $result=mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0 ){
                    $emailErr = "This Email id already exists";
                    $name_value=$name;
                    $phone_value=$phone;
                    $pass_value=$password;
                    $pass2_value=$confirmpass;
                }
            }
        }

        //validate password
        if(empty($password)){
            $passErr="Password cannot be empty";
            $name_value=$name;
            $phone_value=$phone;
            $email_value=$email;
        }else {
            if (strlen($password)<8) {
                $passErr="Password must contain at least 8 character";
                $name_value=$name;
                $phone_value=$phone;
                $email_value=$email;
                $pass_value=$password;
                $pass2_value=$confirmpass;
            }elseif(!preg_match("/[A-Z]+/", $password)){
                $passErr = "Password must contain at least one uppercase letter";
                $name_value=$name;
                $phone_value=$phone;
                $email_value=$email;
                $pass_value=$password;
                $pass2_value=$confirmpass;
            }elseif(!preg_match("/[a-z]+/", $password)){
                $passErr = "Password must contain at least one lowercase letter";
                $name_value=$name;
                $phone_value=$phone;
                $email_value=$email;
                $pass_value=$password;
                $pass2_value=$confirmpass;
            }elseif(!preg_match("/[^\w\s]+/", $password)){
                $passErr = "Password must contain at least one special character";
                $name_value=$name;
                $phone_value=$phone;
                $email_value=$email;
                $pass_value=$password;
                $pass2_value=$confirmpass;
            }
        }
        if(empty($confirmpass)){
            $passmatchErr = "Enter password again";
            $name_value=$name;
            $phone_value=$phone;
            $email_value=$email;
            $pass_value=$password;
        }else if($password != $confirmpass){
            $passmatchErr = "Password does not match";
            $name_value=$name;
            $phone_value=$phone;
            $email_value=$email;
            $pass_value=$password;
            $pass2_value=$confirmpass;
        }

        if (empty($nameErr) && empty($phErr) && empty($emailErr) && empty($passErr) && empty($passmatchErr)){
            $_SESSION['email'] = $email;
            $_SESSION['status'] = false;
            $_SESSION['logged'] = true;

                // insert data into DB
                $hashed_pass = password_hash($password,PASSWORD_DEFAULT);
                $sql = "INSERT INTO user (name,phone,email,password) VALUES ('$username','$phone','$email','$hashed_pass')";
                mysqli_query($con,$sql);

                //Sent mail to user
                $otp = rand(100000,999999);
                $to = $email;
                $subject = 'Email verification code';
                $message = 'your 6 digit registration otp is '. $otp . '.';
                $headers = 'From: webmaster@example.com' . '\r\n';

                if(mail($to, $subject, $message, $headers)){
                    $sql2 = "UPDATE `user` SET `otp`= $otp, `date_time` = now() WHERE email = '$email'";
                    mysqli_query($con,$sql2);
                    header('Location: verifyotp.php');
                }
            }
        }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Registrtion</title>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <link rel="stylesheet" href="css/profile.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
   
        <style>
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            img {
                width: 90%;
                height: auto;
            }
        </style>
</head>
<body class="">
<div class="container ">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col d-flex justify-content-center align-items-center" style="height:100vh">
            <div class="row  border-0 rounded-3 shadow-lg">
                <div class="col border-0 rounded-3 shadow-lg">
                    <div class="h-100 p-3 d-flex profilecard d-flex justify-content-center align-items-center rounded text-center">
                        <img class='img-fluid' src="assets/images/image1.png" alt="Sample image">
                    </div>
                </div>
                <div class="col">
                    <form id="form" class="p-5 py-3" method="post">
                        <div class="row">
                            <p class="space text-uppercase text-center mb-4 fw-bold h3" style="color:#05445E;">Create Account</p>
                        </div>
                        <div class="row mb-3">
                            <input class="form-control form-control-md  fw-semibold input_field" type="text" name="name"  placeholder="Name" value="<?php echo $name_value; ?>">
                            <span class="text-danger fw-semibold " style="font-size:small"><?php echo $nameErr; ?></span>
                        </div>
                        <div class="row mb-3">
                            <input class="form-control form-control-md  fw-semibold input_field" type="tel" name="phone"  placeholder="Phone" value="<?php echo $phone_value; ?>">
                            <span class="text-danger fw-semibold " style="font-size:small"><?php echo $phErr; ?></span>
                        </div>
                        <div class="row mb-3">
                            <input class="form-control form-control-md fw-semibold input_field" type="email" name="email"  placeholder="Email" value="<?php echo $email_value; ?>">
                            <span class="text-danger fw-semibold " style="font-size:small"><?php echo $emailErr; ?></span>
                        </div>
                        <div class="row mb-3">
                            <input class="form-control form-control-md fw-semibold input_field" type="password" name="pass" id="password" placeholder="Password" value="<?php echo $pass_value; ?>">
                            <span class="text-danger fw-semibold " style="font-size:small"><?php echo $passErr; ?></span>
                        </div>
                        <div class="row mb-3">
                            <input class="form-control form-control-md fw-semibold input_field" type="password" name="pass2" id="confirm_password" placeholder="Confirm password" value="<?php echo $pass2_value; ?>">
                            <span class="text-danger fw-semibold " style="font-size:small"><?php echo $passmatchErr; ?></span>
                        </div>
                        <div class="row mb-3">
                            <div class=" fw-semibold">
                                <input id="showpass" class="check" type="checkbox" onclick="myFunction();">
                                <label for="showpass">&nbsp;&nbsp;Show Password</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto mt-0 pt-0">
                            <input type="submit" class="send space text-uppercase fw-bold" name="register" value="Register">
                        </div>
                        <div>
                            <p class="text-center text-dark mt-1 mb-0">Have already an account? 
                                <a href="login1.php" class="fw-semibold text-body"><u>Login</u></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
            }
    </script>
    <script>
        function myFunction() {
            var password = document.getElementById("password");
            var confirm_password = document.getElementById("confirm_password");

            if(password.type === 'password'){
                password.type = 'text'; 
                confirm_password.type= 'text';
            }else{
                password.type = 'password'; 
                confirm_password.type = 'password';
            }
        }
    </script>
</body>
</html>