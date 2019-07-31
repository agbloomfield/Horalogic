<?php
require_once '../pdo.php';
require_once '../util.php';

session_start();

//If not logged in, redirect with error
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
  return;
}

//If cancel clicked redirect to index.php
if ( isset($_POST['cancel']) ) {
  header('Location: index.php');
  return;
}

//Handle POST data
if ( isset($_POST['role_name']) ) {

  if ( strlen($_POST['role_name']) < 1 ) {
    $_SESSION['error'] = "Role name is required.";
    header("Location: add.php");
    return;
  }

  $sql = "INSERT INTO roles
         (role_name, notes)
         VALUES (:en, :no)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':rn' => $_POST['role_name'],
    ':no' => $_POST['notes'])
  );


  $_SESSION['success'] = "Role added";
  header('Location: index.php');
  return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic | Scheduling Science</title>
<?php require_once '../head.php'; ?>
</head>
<body>
<div class="container">
<h1>Adding Roles for <?php echo($_SESSION['name']); ?></h1>
<?php
// Flash Pattern
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
} ?>
<form method="post">
<p>Role Name:
<input type="text" name="role_name" size="60"/></p>
<p>Notes:<br/>
<textarea name="notes" rows="8" cols="80"></textarea>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>
</body>
</html>
