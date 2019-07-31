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
<h1>Horalogic</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<div class="alert alert-danger">'.$_SESSION['error']."</div>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<div class="alert alert-success">'.$_SESSION['success']."</div>\n";
    unset($_SESSION['success']);
}
if ( ! isset ($_SESSION['name']) ) {
  echo "<p>";
  echo('<a href="login.php">Please log in</a>');
  echo("</p>");
} else {
  echo('<p><a href="add.php">Add New Role</a>');
  echo(' | ');
  echo('<a href="../volunteers/index.php">Volunteers</a>');
  echo(' | ');
  echo('<a href="../events/index.php">Events</a>');
  echo(' | ');
  echo('<a href="../index.php">Home</a>');
  echo(' | ');
  echo('<a href="../logout.php">Logout</a></p>');
}

$stmt = $pdo->query("SELECT role_id, role_name, notes FROM roles");
if ( $stmt->rowCount() > 0 ) {
    echo('<table class="table">'."\n");
    echo '<tr><th>Role Name</th><th>Notes</th>';
    if ( isset ($_SESSION['name']) ) {
      echo '<th>Action</th>';
    }
    echo '</tr>';
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo '<a href="view.php?role_id='.$row['role_id'].'">',
              htmlentities($row['role_name']), '</a>';
        echo("</td><td>");
        echo(htmlentities($row['notes']));
        echo("</td>");
        if ( isset ($_SESSION['name']) ) {
          echo "<td>";
          echo('<a href="edit.php?role_id='.$row['role_id'].'">Edit</a> / ');
          echo('<a href="delete.php?role_id='.$row['role_id'].'">Delete</a>');
          echo "</td>";
        }
        echo "</tr>\n";
    }
    echo('</table>');
    echo('<p></p>');
  } else {
    echo('No roles found');
  }

?>
</div>
</body>
