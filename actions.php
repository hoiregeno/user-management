<?php
  session_start();
  include("db.php");

  // --- ADD USER ---
  if(isset($_POST["btn-add"])){
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");

    if($username !== "" && $email !== "" && $phone !== "" && $address !== ""){
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
    else{
      $_SESSION["err_msg"] = "Please fill in all fields.";
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

    $user_id = (int)$_GET["id"];
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $role = trim($_POST["role"] ?? "");
    $address = trim($_POST["address"] ?? "");

    if($username !== "" && $email !== "" && $phone !== "" && $address !== "" && $role !== ""){
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
    else{
      $_SESSION["err_msg"] = "Please fill in all fields.";
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