<?php

@include 'db.php';

session_start();

if($_SESSION['logged'] = true){
   header('location: profile.php');
}

$email_value = $email_error = '';
//For sweet alert and alert message
$_SESSION['status'] = false;
$_SESSION['flag'] = false;

$block = 0 ;
$email_phone_err = $passErr ="" ;
$email_phone_value = $pass_value = "";

$con = mysqli_connect($servername, $username, $password, $database);

if(isset($_POST['login'])){
   $email_phone = $_POST['email_phone'];
   $pass = $_POST['password'];

   if(empty($email_phone) and empty($pass)){
      $email_phone_err = 'Enter Email or Phone number';
      $passErr = 'Enter the password';
   }elseif(empty($email_phone)) {
      $email_phone_err = 'Enter Email or Phone number';
      $pass_value = $pass ;
   }elseif(empty($pass)){
      $passErr = 'Enter the password';
      $email_phone_value = $email_phone;
   }else{
      $select = "SELECT * FROM user WHERE email = '$email_phone' OR phone = '$email_phone'";
      $result = mysqli_query($con, $select);
      $row = mysqli_fetch_assoc($result);
      
      if($row > 0){
         $hashpass = $row['password'];
         $status = $row['status'];
         
         if ( $status == 1 ){
            if(password_verify($pass,$hashpass)){

               $status = " UPDATE user SET `status` = 1, `login_attempts` = 0 WHERE email = '$email_phone' OR phone = '$email_phone' AND password = '$pass' ";
               $result2 = mysqli_query($con, $status);
               
               if($row['role'] == 'user' && $result2){
                  $_SESSION['logged'] = true;
                  $_SESSION['email'] = $row['email'];
                  $_SESSION['phone'] = $row['phone'];
                  
                  ?>
                     <script>
                        window.addEventListener('load', function(){
                           Swal.fire({
                                 icon: 'success',
                                 title: 'Login Successful',
                              }).then(function() {
                              window.location.href = "profile.php";
                           });
                        });
                     </script>
                  <?php
               }
            }else{
               $select = "SELECT * FROM user WHERE email = '$email_phone' OR phone = '$email_phone'";
               $result = mysqli_query($con, $select);
               $row = mysqli_fetch_assoc($result);
               $login_attempts = $row['login_attempts'];

               $email_phone_value = $email_phone;
               $pass_value = $pass ;
               
               $login_attempts ++ ;
               $passErr  = 'Incorrect Password ' . 4-$login_attempts . ' attempts left';
              
               $sql3 = "UPDATE `user` SET `login_attempts` = $login_attempts WHERE email = '$email_phone' OR phone = '$email_phone'";
               mysqli_query($con,$sql3);
   
               if($login_attempts > 3){
                  $sql4 = "UPDATE `user` SET `status` = 0, `login_attempts` = 0 WHERE email = '$email_phone' OR phone = '$email_phone'";
                  mysqli_query($con,$sql4);
                  ?>
                     <script>
                        window.addEventListener('load', function(){
                              Swal.fire({
                                 icon: 'warning',
                                 title: 'Account Blocked',
                                 text: 'Maximum login attempts reached. Your account is Blocked'
                              }).then(function() {
                                 location.reload();
                              });
                        });
                     </script>
                  <?php
               }
            }
         }else{
            ?>
               <script>
                  window.addEventListener('load', function(){
                        Swal.fire({
                           icon: 'warning',
                           title: 'Account Blocked',
                           text: 'Your account has been blocked. Please contact the administrator'
                        })
                  });
               </script>
            <?php
         }
      }else {
         $email_phone_err = 'User not exist please register';
      }
   }
}

if(isset($_POST['forgot_pass'])){
   $block = 1  ;
}

if (isset($_POST['get_otp'])) {
   $email = $_POST['email'];
   if (empty($email)) {
      $email_error = 'Please enter your email';
      $block = 1  ;
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_error = "Invalid email address";
      $email_value = $email;
      $block = 1  ;
   } else {
      $con = mysqli_connect($servername, $username, $password, $database);
      $select = "SELECT * FROM user WHERE email = '$email'";
      $result = mysqli_query($con, $select);
      $row = mysqli_fetch_assoc($result);
      if ($row > 0) {
         $_SESSION['email'] = $email;
         header('location:forgot_password.php');
      }else{
         $email_error = "This email address does not exist";
         $email_value = $email;
         $block = 1  ;
      }
   }
}

?>
<!DOCTYPE html>
<head>
   <title>login form</title>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet"  type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
   <link rel="stylesheet" href="css/profile.css">
</head>
<body class="">
   <div class="container vh-100 d-flex" style="width:75%;">
      <?php
         if($block == 0){
      ?>
         <div class="row justify-content-center align-items-center">
            <div class="col d-flex justify-content-center align-items-center h-75" >
               <div class="row  border-0 rounded-3 shadow-lg">
                  <div class="col border-0 rounded-3 shadow-lg">
                     <div class=" p-3 d-flex profilecard h-100 d-flex justify-content-center align-items-center rounded text-center">
                        <img src="assets/images/login.png" class="img-fluid" alt="Sample image">
                     </div>
                  </div>
                  <div class="col">
                     <form id="form" class="p-5 py-3" method="post">
                        <div class="row ">
                           <div class="">
                              <p class="space d-flex justify-content-center h-75 align-items-center text-uppercase fw-bold h3 name headingshadow pb-0">Wellcome Back</p>
                              <hr>
                           </div>
                        </div>
                        <div class="row px-3 pt-3">
                           <div class="col">
                              <input class="form-control form-control-lg input-lg fw-semibold input_field" type="text" name="email_phone" value="<?php echo $email_phone_value; ?>" placeholder="Email or Phone">
                              <span class="text-danger text-start"><?php echo $email_phone_err; ?></span>
                           </div>
                        </div>
                        <div class="row px-3">
                           <div class="col">
                              <input class="form-control form-control-lg fw-semibold input_field" type="password" id="password" name="password" value="<?php echo $pass_value; ?>" placeholder="Password">
                              <span class="text-danger"><?php echo $passErr; ?></span>
                           </div>
                        </div>
                        <div class="row px-3">
                           <div class="col-11 mx-auto text-center">
                              <div class="row">
                                 <div class="col-6 d-flex justify-content-start">
                                    <div class=" fw-semibold">
                                       <input type="checkbox" id="check" onclick="myFunction()">
                                       <label for="check" class=" name">Show Password</label>
                                    </div>
                                 </div>
                                 <div class="col-6 d-flex justify-content-end pb-3">
                                    <div class=" fw-semibold text-decoration d-flex justify-content-end">
                                       <button class="text-success" style="font-size:0.85em; background: none; border: none; padding: 0; cursor: pointer; text-decoration: underline;" name='forgot_pass'>
                                          Forgot password ?
                                       </button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto mt-0 pt-0">
                           <input type="submit" class="send space text-uppercase fw-bold" name="login" value="login">
                        </div>
                        <div>
                           <p class="text-center text-dark mt-1 mb-0">don't have an account?  
                              <a href="registration.php" class="fw-semibold text-body"><u>Register here</u></a>
                           </p>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      <?php
         }
      ?>

      <?php
         if($block == 1){
      ?>
         <div class="container">
            <div class="row vh-100">
               <div class="col-6 m-auto">
                  <div class="row">
                     <div class="col border-0 rounded-3 shadow-lg">
                        <form method="post">
                           <div class="row px-2 py-4">
                              <div class="text-center col-10 mx-auto">
                                 <label class='name h5 pb-3' for="email">Enter your email to receive an OTP</label>
                                 <input id='email' class="text-center form-control rounded input_field" name='email' type="email" placeholder='Enter your Email' value="<?php echo $email_value ?>"/>
                                 <div class="pt-2">
                                    <span class="text-danger fw-semibold"><?php echo $email_error ?></span>
                                 </div>
                              </div>
                              <div class="text-center mt-4">
                                 <button class="send fw-semibold validate" name='get_otp'>Get otp</button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php
         }
      ?>
   </div>

   <script>
      if ( window.history.replaceState ) {
         window.history.replaceState( null, null, window.location.href );
      }
   </script>

   <script>
        function myFunction() {
            var password = document.getElementById("password");
           
            if(password.type === 'password'){
                password.type = 'text';
            }else{
                password.type = 'password';
            }
        }
   </script>
</body>
</html>