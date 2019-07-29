<?php

function insertPositions($pdo, $profile_id) {
$rank = 1;
for ($i=1; $i<=9; $i++){
  if ( ! isset($_POST['year'.$i]) ) continue;
  if ( ! isset($_POST['desc'.$i]) ) continue;
  $year = $_POST['year'.$i];
  $desc = $_POST['desc'.$i];
  $sql = "INSERT INTO Position
          (profile_id, rank, year, description)
          VALUES ( :pid, :rank, :year, :desc)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':pid' => $profile_id,
    ':rank' => $rank,
    ':year' => $year,
    ':desc' => $desc)
  );
  $rank++;
  }
}

function insertEducations($pdo, $profile_id) {
  $rank = 1;
  for ($i=1; $i<=9; $i++){
    if ( ! isset($_POST['edu_year'.$i])) continue;
    if ( ! isset($_POST['edu_school'.$i])) continue;
    $year = $_POST['edu_year'.$i];
    $school = $_POST['edu_school'.$i];

    // Lookup the School if it exists
    $institution_id = false;
    $stmt = $pdo->prepare('SELECT institution_id FROM
              Institution WHERE name = :name');
    $stmt->execute(array(':name' => $school));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row !== false ) $institution_id = $row['institution_id'];

    // If the school doesn't exist, insert it
    if ( $institution_id === false ) {
      $stmt = $pdo->prepare('INSERT INTO Institution
               (name) VALUES (:name)');
      $stmt->execute(array(':name' => $school));
      $institution_id = $pdo->lastInsertId();
    }

    $sql = "INSERT INTO Education (profile_id, rank, year, institution_id)
            VALUES ( :pid, :rank, :year, :iid)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':pid' => $profile_id,
      ':rank' => $rank,
      ':year' => $year,
      ':iid' => $institution_id)
    );
    $rank++;
  }
}

function validatePos() {
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    if ( strlen($year) == 0 || strlen($desc) == 0 ) {
      return "All fields are required";
    }

    if ( ! is_numeric($year) ) {
      return "Position year must be numeric";
    }
  }
  return true;
}

function validateEdu() {
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['edu_year'.$i]) ) continue;
    if ( ! isset($_POST['edu_school'.$i]) ) continue;

    $year = $_POST['edu_year'.$i];
    $school = $_POST['edu_school'.$i];

    if ( strlen($year) == 0 || strlen($school) == 0 ) {
      return "All fields are required";
    }

    if ( ! is_numeric($year) ) {
      return "Education year must be numeric";
    }
  }
  return true;
}

function loadPos($pdo, $profile_id) {
  $stmt = $pdo->prepare('SELECT * FROM Position
           WHERE profile_id = :prof ORDER BY rank');
  $stmt->execute(array( ':prof' => $profile_id)) ;
  $positions = array();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $positions[] = $row;
  }
  return $positions;
}

function loadEdu($pdo, $profile_id) {
  $stmt = $pdo->prepare('SELECT * FROM Education INNER JOIN Institution
           ON Education.institution_id = Institution.institution_id
           WHERE profile_id = :prof ORDER BY rank');
  $stmt->execute(array( ':prof' => $profile_id)) ;
  $educations = array();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $educations[] = $row;
  }
  return $educations;
}
