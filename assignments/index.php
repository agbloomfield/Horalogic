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
<h1>Volunteer Assignments</h1>

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
    echo('<p><a href="add.php">Add Assignment</a>');
    echo(' | ');
    echo('<a href="volunteers/index.php">Volunteers</a>');
    echo(' | ');
    echo('<a href="../events/index.php">Events</a>');
    echo(' | ');
    echo('<a href="../roles/index.php">Roles</a>');
    echo(' | ');
    echo('<a href="../index.php">Home</a>');
    echo(' | ');
    echo('<a href="../logout.php">Logout</a></p>');
}
$stmt = $pdo->query("SELECT * FROM assignments
    INNER JOIN volunteers on assignments.volunteer_id = volunteers.volunteer_id
    INNER JOIN events on assignments.event_id = events.event_id
    INNER JOIN roles on assignments.role_id = roles.role_id
    ORDER BY event_date, event_time");
if ( $stmt->rowCount() > 0 ) {
    echo('<table class="table">'."\n");
    echo '<tr><th>Volunteer</th><th>Event</th><th>Date</th><th>Time</th><th>Role</th>';
    if ( isset ($_SESSION['name']) ) {
      echo '<th>Action</th>';
    }
    echo '</tr>';
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo '<a href="../volunteers/view.php?volunteer_id='.$row['volunteer_id'].'">',
              htmlentities($row['first_name'].' '.$row['last_name']), '</a>';
        echo("</td><td>");
        echo htmlentities($row['event_name']);
        echo("</td><td>");
        echo htmlentities($row['event_date']);
        echo("</td><td>");
        echo htmlentities($row['event_time']);
        echo("</td><td>");
        echo htmlentities($row['role_name']);
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
    echo('No assignments found');
  }

?>
</div>
</body>
