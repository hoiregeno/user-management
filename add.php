<?php
  session_start();

  if(isset($_SESSION["err_msg"])){
    $err_msg = $_SESSION["err_msg"];
    unset($_SESSION["err_msg"]);
  }

  $old = $_SESSION["old_input"] ?? [];
  unset($_SESSION["old_input"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if(!empty($err_msg)): ?>
    <p class="err-display"><?= htmlspecialchars($err_msg) ?></p>  
  <?php endif; ?>

  <div class="form-wrapper">

    <form action="actions.php" method="post">
      <h2>Add User</h2>
      
      <div class="input-wrapper">
        <input type="text" name="username" id="username" placeholder=" " value="<?= htmlspecialchars($old['username'] ?? '') ?>">
        <label for="username">Username</label>
      </div>

      <div class="input-wrapper">
        <input type="email" name="email" id="email" placeholder=" " value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        <label for="email">Email</label>
      </div>

      <div class="input-wrapper">
        <input type="text" name="phone" id="phone" placeholder=" " value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
        <label for="phone">Phone</label>
      </div>

      <div class="input-wrapper">
        <textarea name="address" id="address" placeholder=" "><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
        <label for="address">Address</label>
      </div>

      <div class="action-wrapper">
        <button type="submit" class="btn btn-submit" name="btn-add">Add</button>
        <a href="index.php" class="btn btn-cancel">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>