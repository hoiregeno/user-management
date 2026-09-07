<?php
  session_start();

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
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if(!empty($err_msg)): ?>
    <p class="err-display"><?= htmlspecialchars($err_msg) ?></p>  
  <?php endif; ?>

  <div class="form-wrapper">
    <form action="actions.php" method="post">
      <h2>Login</h2>

      <div class="input-wrapper">
        <input type="text" name="username" id="username" placeholder=" ">
        <label for="username">Username</label>
      </div>

      <div class="input-wrapper">
        <input type="password" name="password" id="password" placeholder=" ">
        <label for="password">Password</label>
      </div>

      <button type="submit" class="btn btn-submit" name="btn-login">Submit</button>
    </form>
  </div>
</body>
</html>