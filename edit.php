<?php
  session_start();
  include("db.php");

  // Validate the incoming id before using it in a query.
  if(!isset($_GET["id"]) || !ctype_digit((string)$_GET["id"])){
    $_SESSION["err_msg"] = "Invalid user.";
    header("Location: index.php");
    exit;
  }

  $user_id = (int)$_GET["id"];
  $row = null;

  try{
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
  }
  catch(mysqli_sql_exception $e){
    error_log($e -> getMessage());
    $_SESSION["err_msg"] = "Something went wrong. Try again.";
    header("Location: index.php");
    exit;
  }

  // No matching row for this id — don't try to render the form with null data.
  if($row === null){
    $_SESSION["err_msg"] = "User not found.";
    header("Location: index.php");
    exit;
  }

  if(isset($_SESSION["err_msg"])){
    $err_msg = $_SESSION["err_msg"];
    unset($_SESSION["err_msg"]);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if(!empty($err_msg)): ?>
    <p class="err-display"><?= htmlspecialchars($err_msg) ?></p>  
  <?php endif; ?>

  <div class="form-wrapper">
    <form action="actions.php?id=<?= $user_id ?>" method="post">
      <h2>Edit User</h2>
      
      <div class="input-wrapper">
        <input type="text" name="username" id="username" placeholder=" " value="<?= htmlspecialchars($row['username']) ?>">
        <label for="username">Username</label>
      </div>

      <div class="input-wrapper">
        <input type="email" name="email" id="email" placeholder=" " value="<?= htmlspecialchars($row['email']) ?>">
        <label for="email">Email</label>
      </div>

      <div class="input-wrapper">
        <input type="text" name="phone" id="phone" placeholder=" " value="<?= htmlspecialchars($row['phone']) ?>">
        <label for="phone">Phone</label>
      </div>

      <div class="input-wrapper">
        <textarea name="address" id="address" placeholder=" "><?= htmlspecialchars($row["address"]) ?></textarea>
        <label for="address">Address</label>
      </div>

      <div class="input-wrapper">
        <input type="text" name="role" id="role" placeholder=" " value="<?= htmlspecialchars($row['role']) ?>">
        <label for="role">Role</label>
      </div>

      <div class="action-wrapper">
        <button type="submit" class="btn btn-submit" name="btn-edit">Edit</button>
        <a href="index.php" class="btn btn-cancel">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>