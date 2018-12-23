<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$sduid = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $sduid = mysqli_real_escape_string($db, $_POST['sduid']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $adminP = mysqli_real_escape_string($db, $_POST['admin_1']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($sduid)) { array_push($errors, "ID is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }
 

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' OR sduid = '$sduid' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
    if ($user['sduid'] === $sduid) {
      array_push($errors, "ID already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database
    
    if($adminP == "SDU"){
      $query = "INSERT INTO users (username, email, password, sduid, is_admin) 
                   VALUES('$username', '$email', '$password', '$sduid',1)";
    }
    else{
  	$query = "INSERT INTO users (username, email, password, sduid,is_admin) 
  			           VALUES('$username', '$email', '$password', '$sduid',0)";}
  	mysqli_query($db, $query);
    if($adminP=="SDU"){
      header('location: admin.html');
    }
    else{
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: indexmain.html');
  }
}
}

// ... 
// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      foreach ($results as $key => $value) {
        $is_admin = $value['is_admin'];  
      }
      if($is_admin == 1){
        header('location:admin.html');
      }
      else{
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: indexmain.html');}
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>