<?php
  session_start();

  if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <h2>Hello, <?php echo htmlspecialchars($_SESSION["username"]) ?></h2>
    <p>Welcome to your dashboard</p>

    <div class="action-wrapper logout">
      <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>
  </div>
</body>
</html>