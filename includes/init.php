<?php

// execute an SQL query and return the results.
function exec_sql_query($db, $sql, $params = array()) {
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
}


// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

// An array to deliver messages to the user.
$messages = array();

// Record a message to display to the user.
function record_message($message) {
  global $messages;
  array_push($messages, $message);
}

// Write out any messages to the user.
function print_messages() {
  global $messages;
  foreach ($messages as $message) {
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
  }
}

// open connection to database
$db = open_or_init_sqlite_db("gallery.sqlite", "init/init.sql");
$redirect = FALSE;
function check_login() {
  global $db;
  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];

    $sql = "SELECT * FROM users WHERE session = :session";
    $params = array(
      ':session' => $session
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      $users = $records[0];
      $info = array('username' => $users['username'],
                    'first_name' => $users['first_name'],
                    'last_name' => $users['last_name'],
                    'userid' => $users['userid'],
                    'session' => $session);
      return $info;
    }
  }
  return NULL;
}

function log_in($username, $password) {
  global $db;
  global $redirect;

  if ($username && $password) {
    $sql = "SELECT * FROM users WHERE username = :username;";
    $params = array(
      ':username' => $username
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      $users = $records[0];
      if (password_verify($password, $users['password']) ) {
        $session = uniqid();
        $sql = "UPDATE users SET session = :session WHERE userid = :user_id;";
        $params = array(
          ':user_id' => $users['userid'],
          ':session' => $session
        );
        $result = exec_sql_query($db, $sql, $params);
        if ($result) {
          setcookie("session", $session, time()+3600);
          record_message("Logged in as $username.");
          $redirect = TRUE;
          return TRUE;
        } else {
          record_message("Log in failed.");
        }
      } else {
        record_message("Invalid username or password.");
      }
    } else {
      record_message("Invalid username or password.");
    }
  } else {
    record_message("Please enter Username and Passord.");
  }
  return FALSE;
}

function log_out() {
  global $current_user;
  global $db;

  if ($current_user) {
    $sql = "UPDATE users SET session = :session WHERE username = :username;";
    $params = array(
      ':username' => $current_user,
      ':session' => NULL
    );
    if (!exec_sql_query($db, $sql, $params)) {
      record_message("Log out failed.");
    }
  }
  setcookie("session", "", time()-3600);
  $current_user = NULL;
}
if (isset($_POST['login'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $username = trim($username);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  log_in($username, $password);
}
if(check_login() != NULL){
  $info = check_login();
  $current_user = $info['username'];
  $first_n = $info['first_name'];
  $last_n =  $info['last_name'];
  $userid = $info['userid'];
  $session = $info['session'];
}else {
  $current_user = NULL;
  $first_n = NULL;
  $last_n = NULL;
  $userid = NULL;
  $session = NULL;
}

?>
