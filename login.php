<?php // Do not put any HTML above this line
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['name']) ) {
   //echo("<p>Session is empty</p>\n");
   $_SESSION['name'] = 0;
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$check = hash('md5', $salt.$_POST['pass']);
$stmt = $pdo->prepare('SELECT user_id, name FROM users
                       WHERE email = :em AND password = :pw');
$stmt->execute(array(':em'=>$_POST['email'], ':pw'=>$check));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        error_log("Login failure ".$_POST['email']);
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    } else {
      if ( !strpos($_POST['email'], "@") ) {
       error_log("Login failure ".$_POST['email']);
       $_SESSION['error'] = "Email must have an at-sign (@)";
       header("Location: login.php");
       return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $row !== false ) {
            // Record successful login to log
            error_log("Login success ".$_POST['email']." $check");
            // Set session data
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect to index
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail ".$_POST['email']." $check");
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
      }
    }
  }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "head.php"; ?>
<title>Horalogic login</title>
</head>
<body>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <meta name="google-signin-client_id" content="791705139138-f7ete57d1usao71jsl5ic3v6plefs3og.apps.googleusercontent.com">
<div class="container">
<h1>Horalogic Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<div class="g-signin2" data-onsuccess="onSignIn"></div>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</div>
</body>
</html>
