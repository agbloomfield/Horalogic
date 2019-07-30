<?php
require_once "../pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "../head.php"; ?>
<title>Horalogic</title>
</head>
<body>
<div class="container">
<h1>Welcome to Horalogic</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
if ( ! isset ($_SESSION['name']) ) {
  echo "<p>";
  echo('<a href="login.php">Please log in</a>');
  echo("</p>");
} else {
    echo('<p><a href="add.php">Add New Volunteer</a>');
    echo(' | ');
    echo('<a href="../events/index.php">Events</a>');
    echo(' | ');
    echo('<a href="../index.php">Home</a>');
    echo(' | ');
    echo('<a href="logout.php">Logout</a></p>');
}
$stmt = $pdo->query("SELECT first_name, last_name, volunteer_id, user_id, email, phone FROM volunteers");
if ( $stmt->rowCount() > 0 ) {
    echo('<table border="1">'."\n");
    echo '<tr><th>Name</th><th>Email</th><th>Phone</th>';
    if ( isset ($_SESSION['name']) ) {
      echo '<th>Action</th>';
    }
    echo '</tr>';
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo '<a href="view.php?vounteer_id='.$row['volunteer_id'].'">',
              htmlentities($row['first_name'].' '.$row['last_name']), '</a>';
        echo("</td><td>");
        echo '<a href="mailto:', htmlentities($row['email']), '">', htmlentities($row['email']), '</a>';
        echo("</td><td>");
        echo htmlentities($row['phone']);
        echo("</td>");
        if ( isset ($_SESSION['name']) ) {
          echo "<td>";
          echo('<a href="edit.php?volunteer_id='.$row['volunteer_id'].'">Edit</a> / ');
          echo('<a href="delete.php?volunteer_id='.$row['volunteer_id'].'">Delete</a>');
          echo "</td>";
        }
        echo "</tr>\n";
    }
    echo('</table>');
    echo('<p></p>');
  } else {
    echo('No volunteers found');
  }

?>
</div>
</body>
