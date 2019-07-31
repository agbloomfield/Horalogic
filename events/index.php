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
  echo('<p><a href="add.php">Add New Event</a>');
  echo(' | ');
  echo('<a href="../volunteers/index.php">Volunteers</a>');
  echo(' | ');
  echo('<a href="../roles/index.php">Roles</a>');
  echo(' | ');
  echo('<a href="../index.php">Home</a>');
  echo(' | ');
  echo('<a href="../logout.php">Logout</a></p>');
}

$stmt = $pdo->query("SELECT event_name, event_date, event_time, event_id FROM events");
if ( $stmt->rowCount() > 0 ) {
    echo('<table class="table">'."\n");
    echo '<tr><th>Event Name</th><th>Event Date</th><th>Event Time</th>';
    if ( isset ($_SESSION['name']) ) {
      echo '<th>Action</th>';
    }
    echo '</tr>';
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo '<a href="view.php?event_id='.$row['event_id'].'">',
              htmlentities($row['event_name']), '</a>';
        echo("</td><td>");
        echo(htmlentities($row['event_date']));
        echo("</td><td>");
        echo(htmlentities($row['event_time']));
        echo("</td>");
        if ( isset ($_SESSION['name']) ) {
          echo "<td>";
          echo('<a href="edit.php?event_id='.$row['event_id'].'">Edit</a> / ');
          echo('<a href="delete.php?event_id='.$row['event_id'].'">Delete</a>');
          echo "</td>";
        }
        echo "</tr>\n";
    }
    echo('</table>');
    echo('<p></p>');
  } else {
    echo('No events found');
  }

?>
</div>
</body>
