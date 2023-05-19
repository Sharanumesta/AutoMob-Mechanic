<?php
  session_start();

  if(!$_SESSION['logged']){
    header('location : login.php');
}

  $reply_to = $_SESSION['email'];

  function testinput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

    $email = $subject = $message = "";
    $email_err = $subject_err = $message_err ="";
    $email_value = $subject_value = $message_value = "";

    if(isset($_POST['send'])){
      $email=$_POST['email'];
      $subject=$_POST['subject'];
      $message=$_POST['message'];

      //email validation
      if(empty($email)){
        $email_err = "Enter the recipient email";
        $subject_value = $subject;
        $message_value = $message ;
        echo $message_value;
      }else{
        $email =testinput($email);
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
          $email_err="Enter valid Email address";
          $subject_value = $subject;
          $message_value = $message;
        }
      }

      if(empty($subject)){
        $subject_err = "Subject cannot be empty";
        $email_value = $email;
        $message_value = $message;
      }
      
      if(empty($message)){
        $message_err = "Message cannot be empty";
        $email_value = $email;
        $subject_value = $subject;
      }

      if (empty($email_err) && empty($subject_err) && empty($message_err)){
        $to = $email;
        $sub =$subject;
        $mes = $message;
        $headers = 'From: openmail12345678@gmail.com'. "\r\n" ;

        if(mail($to,$sub,$mes,$headers)){
          ?>   
            <script>
              window.addEventListener('load', function(){
                swal({
                  type:"success",
                  title: "Email Sent Successfully",
                })
              });
            </script>
          <?php
        }
    }
  }
  ?>
<!DOCTYPE html>
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">  
  <link rel="stylesheet" href="css/profile.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<title>Mail</title>
</head>

<body class="space">
<!-- navbar start -->
<nav class="navbar navbar-expand navbar-dark nav pt-4 pb-4">
    <div class="container">
        <i><a href="profile.php" class="navbar-brand fw-bold fs-3 rounded p-1" style="color:#05445E">Profile</a></i>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="mail1.php" class="nav-link active ms-2 me-2 fw-bold" id='hover' style="color:#D4F1F4">Mail</a>
            </li>
            <li class="nav-item dropdown">
            <a class="nav-link active ms-2 me-2 fw-bold dropdown-toggle" id='hover' href="#" role="button" style="color:#D4F1F4" data-bs-toggle="dropdown" aria-expanded="false">
                Update
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="profile_update.php"  name="profile">Profile</a></li>
                <li><a class="dropdown-item" href="password_update.php"  name="password">Password</a></li>
            </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active ms-2 me-2 fw-bold" data-bs-toggle="modal" style="color:#D4F1F4" data-bs-target="#modal" id='hover'>Logout</a>
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

<div class="container">
    <div class="row">
      <div class="col-1"></div>
        <div class="col-10">
            <div class="container my-3 p-4 w-50 mail border-0 rounded-3 shadow-lg">
                <p class="text-uppercase text-center fw-bold fs-2 name" >Send mail</p>
                <form class="" method="post" action="#">
                    <div class="mb-3 ">
                        <label for="email" class="form-label fw-bold name">To</label>
                        <input type="text" class="form-control input_field" id="email" name="email" value="<?php echo $email_value; ?>">
                        <span class="text-danger"><?php echo $email_err; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="Subject" class="form-label fw-bold name">Subject</label>
                        <input type="text" class="form-control input_field" id="Subject" name="subject" value="<?php echo $subject_value; ?>">
                        <span class="text-danger"> <?php echo $subject_err; ?></span>
                    </div>
                    <div class="mb-3">
                        <p class="form-label massage"><label for="message" class="form-label fw-bold name">Message</label></p>
                        <textarea class="form-control input_field" rows="3" name="message"><?php echo $message_value; ?></textarea>
                        <span class="text-danger"> <?php echo $message_err; ?></span>
                    </div >
                    <div class="row text-center mt-4">
                        <div class="align-item-center">
                            <button class="send w-25 fw-bold" type="submit" name="send">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>