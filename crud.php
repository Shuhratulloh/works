<?php
// Set up database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cruddb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set up REST API endpoints
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Get all records
  $sql = "SELECT * FROM item";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $records = array();
    while ($row = $result->fetch_assoc()) {
      $records[] = $row;
    }
    echo json_encode($records);
  } else {
    echo "No records found";
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Authenticate user
  $token = $_POST['token'];
  if ($token !== 'your_token_here') {
    http_response_code(401);
    echo "Unauthorized";
    exit();
  }

  // Create a new record
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $key = $_POST['key'];
  $created_at = date('Y-m-d H:i:s');
  $updated_at = date('Y-m-d H:i:s');
  $login = $_POST['username'];
  $passcode = $_POST['password'];

 

  $sql = "INSERT INTO item (name, phone, `key`, created_at, updated_at) VALUES ('$name', '$phone', '$key', '$created_at', '$updated_at')";
  if ($conn->query($sql) === TRUE) {
    echo "Record created successfully";
  } else {
    echo "Error creating record: " . $conn->error;
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  // Authenticate user
  $token = $_SERVER['HTTP_AUTHORIZATION'];
  if ($token !== 'Bearer your_token_here') {
    http_response_code(401);
    echo "Unauthorized";
    exit();
  }

  // Update an existing record
  parse_str(file_get_contents("php://input"), $put_vars);
  $id = $put_vars['id'];
  $name = $put_vars['name'];
  $phone = $put_vars['phone'];
  $key = $put_vars['key'];
  $updated_at = date('Y-m-d H:i:s');

  $sql = "UPDATE item SET name='$name', phone='$phone', `key`='$key', updated_at='$updated_at' WHERE id=$id";
  if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  // Authenticate user
  $token = $_SERVER['HTTP_AUTHORIZATION'];
  if ($token !== 'Bearer your_token_here') {
    http_response_code(401);
    echo "Unauthorized";
    exit();
  }

  // Delete an existing record
  parse_str(file_get_contents("php://input"), $delete_vars);
  $id = $delete_vars['id'];

  $sql = "DELETE FROM item WHERE id=$id";
  if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  }
}

// Close database connection
$conn->close();
?>