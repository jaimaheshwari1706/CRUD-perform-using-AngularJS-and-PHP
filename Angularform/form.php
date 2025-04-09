<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
$conn = mysqli_connect("localhost", "root", "", "formdb");
if (mysqli_connect_errno()) {
  echo "" . mysqli_connect_error();
}

function create()
{
  global $conn;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CREATE = isset($_POST['Arrar']) ? json_decode($_POST['Arrar'], true) : [];

    $name = $CREATE['name'] ?? '';
    $email = $CREATE['email'] ?? '';
    $phone = $CREATE['phone'] ?? '';
    $address = $CREATE['address'] ?? '';
    $gender = $CREATE['gender'] ?? [];
    $hobbies = $CREATE['hobbies'] ?? [];
    $language = $CREATE['language'] ?? '';
    $code = $CREATE['designation'] ?? '';
    $filename = $CREATE['file'] ?? '';

    // Convert arrays to comma-separated strings
    $gender = is_array($gender) ? implode(',', $gender) : $gender;
    $hobbies = is_array($hobbies) ? implode(',', $hobbies) : $hobbies;

    $sql = "INSERT INTO form (name, email, phone, address, gender, hobbies, language, code, filename) 
            VALUES ('$name', '$email', '$phone', '$address', '$gender', '$hobbies', '$language', '$code', '$filename')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
      echo json_encode(['message' => 'Form submitted successfully']);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
  }
}


$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
  create();
} elseif ($action === 'getdesignation') {
  getdesignation();
} elseif ($action === 'getalldata') {
  getalldata();
} else {
  echo json_encode(['error' => 'Invalid action']);
}


function getdesignation()
{
  global $conn;

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql2 = "SELECT name as designation, code FROM designationmaster";
    $result = $conn->query($sql2);
    $data = array();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    echo json_encode($data);
  }
}
// getdata();

function getalldata()
{
  global $conn;

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $sql2 = "SELECT * FROM form WHERE id = $id";
    $result = $conn->query($sql2);
    $data = array();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        if (isset($row['hobbies'])) {
          $row['hobbies'] = array_map('trim', explode(',', $row['hobbies']));}
          if (isset($row['gender'])) {
          $row['gender'] = array_map('trim', explode(',', $row['gender']));
        }
        $data[] = $row;
      }
    }
    echo json_encode($data);
  }
}


// getalldata();
