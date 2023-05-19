<?php

@include 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL); 

session_start();

//For sweet alert and alert message
$_SESSION['status'] = false;
$_SESSION['flag'] = false;

$block = 1 ;
$email_phone_err = $passErr ="" ;
$email_phone_value = $pass_value = "";

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
      $con = mysqli_connect($servername, $username, $password, $database);
      $select = "SELECT * FROM user WHERE email = '$email_phone' OR phone = '$email_phone'";
      $result = mysqli_query($con, $select);
      $row = mysqli_fetch_assoc($result);
      
      if($row > 0){
         $hashpass = $row['password'];
         if(password_verify($pass,$hashpass)){

            $status = "UPDATE user SET status = 1 WHERE email = '$email_phone' OR phone = '$email_phone' AND password = '$pass'";
            $result2 = mysqli_query($con, $status);
      
            if($row['role'] == 'user'){
               $_SESSION['logged'] = true;
               $_SESSION['email'] = $row['email'];
               $_SESSION['phone'] = $row['phone'];
               header('location:profile.php');
            }
         }else{
            $passErr = 'Incorrect password' ;
            $email_phone_value = $email_phone;
            $pass_value = $pass ;
         }
   }else {
      $email_phone_err = 'User not exist please register';
   }
}
}
if(isset($_POST['forgot_pass'])){
   $block = 1 ;
}
?>
<!DOCTYPE html>
<head>
   <title>login form</title>

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                  <div class="col border-0 rounded-3 shadow-lg ">
                     <div class=" p-3 d-flex profilecard d-flex justify-content-center align-items-center rounded text-center">
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
                                       <input class="check" type="checkbox" onclick="myFunction();">&nbsp;&nbsp;Show Password
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
                     <div class="col nav border-0 rounded-3">
                        <form class="" action="" method="post">
                           <div class="row p-5">
                              <div class="">
                                 <label class='name h5' for="email"></label>
                                 <input id='email' class=" text-center form-control rounded input_field" name='email' type="email" placeholder='Enter your Email'/>
                              </div>
                              <div class="mt-4">
                                 <button class="send fw-bold validate" name='validate-otp'>Validate</button>
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
