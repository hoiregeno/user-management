<?php
  session_start();
  include("db.php");

  // Validate if user logged in first
  if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit;
  }

  // Select necessary user details
  $sql = "SELECT  user_id,
                  username,
                  email,
                  phone,
                  address,
                  role
          FROM users";

  $result = mysqli_query($conn, $sql);

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
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <div class="wrapper_heading">
      <h1>User List</h1>
      <a href="add.php" class="btn btn-add">Add User</a>
    </div>

    <?php if(!empty($err_msg)): ?>
      <p class="err-display"><?= htmlspecialchars($err_msg) ?></p>
    <?php endif; ?>

    <div class="table-wrapper">
      <table class="table-contents">
        <thead>
          <tr>
            <th>No.</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $num = 1;
            while($row = mysqli_fetch_assoc($result)):
          ?>
            <tr>
              <td><?= $num++ ?></td>
              <td><?= htmlspecialchars($row["username"]) ?></td>
              <td><?= htmlspecialchars($row["email"]) ?></td>
              <td><?= htmlspecialchars($row["phone"]) ?></td>
              <td><?= htmlspecialchars($row["address"]) ?></td>
              <td><?= htmlspecialchars($row["role"]) ?></td>
              <td class="action-wrapper">
                <a href="edit.php?id=<?= $row['user_id'] ?>" class="btn btn-edit">Edit</a>
                <form action="actions.php?id=<?= $row['user_id'] ?>" method="post" onsubmit="return confirm('Delete this user?')">
                  <button type="submit" name="btn-delete" class="btn btn-delete">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="action-wrapper logout">
      <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>
  </div>
</body>
</html>