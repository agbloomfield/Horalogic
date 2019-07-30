<?php
require_once "../pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['volunteer_id']) ) {
    $sql = "DELETE FROM volunteers WHERE volunteer_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['volunteer_id']));
    $_SESSION['success'] = 'Volunteer deleted';
    header( 'Location: index.php' ) ;
    return;
}
if ( isset($_POST['cancel']) ){
  header( 'Location: index.php' ) ;
  return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['volunteer_id']) ) {
  $_SESSION['error'] = "Missing volunteer_id";
  header('Location: index.php');
  return;
}

if ( isset($_POST['cancel']) ){
  header( 'Location: index.php' ) ;
  return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, volunteer_id
                       FROM volunteers where volunteer_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['volunteer_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for volunteer_id';
    header( 'Location: index.php' );
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Horalogic Deleting...</title>
<?php require_once "../head.php"; ?>
</head>
<div class="container">
<h1>Confirm: Deleting Volunteer</h1>
  <form method="post" action="delete.php">
  <p>First Name: <?= htmlentities($row['first_name']) ?></p>
  <p>Last Name: <?= htmlentities($row['last_name']) ?></p>
  <input type="hidden" name="volunteer_id" value="<?= $row['volunteer_id'] ?>">
  <input type="submit" value="Delete" name="delete">
  <input type="submit" value="Cancel" name="cancel"</a>
</form>
</div>
</html>
