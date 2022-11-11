<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['user_name'])){
   header('location:login_form.php');
}

$t=$_SESSION['user_name'];

$select = " SELECT id FROM user_form WHERE name = '$t' ";
$result = mysqli_query($conn, $select);
$row = mysqli_fetch_array($result);
$_SESSION['id'] = $row['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>user page</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<div class="container">

   <div class="content">
      <h3>hi, <span>user</span></h3>
      <h1>welcome <span><?php echo $t ?></span></h1>
      <p>this is an user page</p>
      <a href="login_form.php" class="btn">login</a>
      <a href="register_form.php" class="btn">register</a>
      <a href="logout.php" class="btn">logout</a>
   </div>

</div>
<div class="form-container">

   <form action="" method="post">
      <h3>Profile Details</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <h1>Your ID is <span><?php echo $_SESSION['id'] ?></span></h1>
      <input type="text" name="age" required placeholder="Enter your Age">
      <input type="text" name="contact" required placeholder="Enter your Phone Number">
      <input type="text" name="address" required placeholder="Enter your Address">
      <input type="submit" name="submit" value="update now" class="form-btn">
   </form>

</div>

</body>
</html>