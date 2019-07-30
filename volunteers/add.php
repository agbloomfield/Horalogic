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
  header('Location: ./index.php');
  return;
}

//Handle POST data
if ( isset($_POST['first_name']) && isset($_POST['last_name']) &&
     isset($_POST['email']) && isset($_POST['notes']) ) {

  if ( ( strlen($_POST['first_name']) < 1) ||
     ( strlen($_POST['last_name']) < 1) ||
     ( strlen($_POST['email']) < 1) ) {
    $_SESSION['error'] = "Name and email are required.";
    header("Location: ./add.php");
    return;
  }

  if ( strpos($_POST['email'], '@') === false ) {
    $_SESSION['error'] = "Email must contain at-sign (@)";
    header("Location: ./add.php");
    return;
  }

  $sql = "INSERT INTO volunteers
         (user_id, first_name, last_name, email, phone, notes)
         VALUES ( :uid, :fn, :ln, :em, :ph, :no)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':ph' => $_POST['phone'],
    ':no' => $_POST['notes'])
  );
  $volunteer_id = $pdo->lastInsertId();

  // insert the education entries
  insertEducations($pdo, $volunteer_id);

  $_SESSION['success'] = "Volunteer added";
  header('Location: ./index.php');
  return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic</title>
<?php require_once '../head.php'; ?>
</head>
<body>
<div class="container">
<h1>Adding Volunteer for <?php echo($_SESSION['name']); ?></h1>
<?php
// Flash Pattern
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
} ?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Phone:<br/>
<input type="text" name="phone" size="80"/></p>
<p>Notes:<br/>
<textarea name="notes" rows="8" cols="80"></textarea>
<p>
Event Types: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
</div>
</p>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<!-- HTML with Substitution -->
<script id="edu-template" type="text">
  <div id="edu@COUNT@">
    <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
    <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
    </p>
  </div>
</script>
</div>
</body>
</html>
