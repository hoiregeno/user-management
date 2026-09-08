<?php
  session_start();
  include("db.php");

  // --- LOGIN USER ---
  if(isset($_POST["btn-login"])){
    // Grab user details
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Validate user input before logging user
    if($username === "" || $password === ""){
      $_SESSION["err_msg"] = "Please fill in all fields.";
      header("Location: login.php");
      exit;
    }

    try{
      $sql = "SELECT user_id, username, hash_password, role
              FROM users
              WHERE username = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($result) === 0){
        $_SESSION["err_msg"] = "Wrong username or password.";
        header("Location: login.php");
        exit;
      }

      $row = mysqli_fetch_assoc($result);
      if(password_verify($password, $row["hash_password"])){
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["username"] = $row["username"];

        if($row["role"] === "user"){
          header("Location: user_dashboard.php");
          exit;
        }
        else{
          header("Location: index.php");
          exit;
        }
      }
      else{
        $_SESSION["err_msg"] = "Wrong username or password.";
        header("Location: login.php");
        exit;
      }
    }
    catch(mysqli_sql_exception $e){
      $_SESSION["err_msg"] = "Something went wrong.";
      error_log($e ->  getMessage());

      header("Location: login.php");
      exit;
    }
  }

  // --- ADD USER ---
  if(isset($_POST["btn-add"])){
    // Grab user data
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");

    // Validate user data before adding user
    if($username === "" || $email === "" || $phone === "" || $address === ""){
      $_SESSION["err_msg"] = "Please fill in all fields.";
      $_SESSION["old_input"] = compact("username", "email", "phone", "address");
      header("Location: add.php");
      exit;
    }

    // Prepare statement
    try{
      $sql = "INSERT INTO users (username, email, phone, address)
              VALUES (?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $phone, $address);
      mysqli_stmt_execute($stmt);

      header("Location: index.php");
      exit;
    }
    catch(mysqli_sql_exception $e){
      if($e -> getCode() == 1062){
        $_SESSION["err_msg"] = "User already exists. Try again.";
      }
      else{
        $_SESSION["err_msg"] = "Something went wrong. Try again.";
        error_log($e -> getMessage());
      }

      $_SESSION["old_input"] = compact("username", "email", "phone", "address");
      header("Location: add.php");
      exit;
    }
  }

  // --- EDIT USER ---
  if(isset($_POST["btn-edit"])){
    // Validate the id before it ever touches a query or a redirect.
    if(!isset($_GET["id"]) || !ctype_digit((string)$_GET["id"])){
      $_SESSION["err_msg"] = "Invalid user.";
      header("Location: index.php");
      exit;
    }

    // Grab the user data
    $user_id = (int)$_GET["id"];
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $role = trim($_POST["role"] ?? "");
    $address = trim($_POST["address"] ?? "");

    // Validate user data before updating anything
    if($username === "" || $email === "" || $phone === "" || $address === "" || $role === ""){
      $_SESSION["err_msg"] = "Please fill in all fields.";
      header("Location: edit.php?id={$user_id}");
      exit;
    }

    // Prepare statement
    try{
      $sql = "UPDATE users
              SET username = ?,
                  email = ?,
                  phone = ?,
                  address = ?,
                  role = ?
              WHERE user_id = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "sssssi", $username, $email, $phone, $address, $role, $user_id);
      mysqli_stmt_execute($stmt);

      header("Location: index.php");
      exit;
    }
    catch(mysqli_sql_exception $e){
      if($e -> getCode() == 1062){
        $_SESSION["err_msg"] = "User already exists. Try again.";
      }
      else{
        $_SESSION["err_msg"] = "Something went wrong. Try again.";
        error_log($e -> getMessage());
      }

      header("Location: edit.php?id={$user_id}");
      exit;
    }
  }

  // --- DELETE USER ---
  if(isset($_POST["btn-delete"])){
    // Validate the id before it ever touches a query or a redirect.
    if(!isset($_GET["id"]) || !ctype_digit((string)$_GET["id"])){
      $_SESSION["err_msg"] = "Invalid user.";
      header("Location: index.php");
      exit;
    }

    $user_id = (int)$_GET["id"];

    try{
      $sql = "DELETE FROM users
              WHERE user_id = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $user_id);
      mysqli_stmt_execute($stmt);

      header("Location: index.php");
      exit;
    }
    catch(mysqli_sql_exception $e){
      error_log($e -> getMessage());
      $_SESSION["err_msg"] = "Something went wrong. Try again.";
      header("Location: index.php");
      exit;
    }
  }
?>