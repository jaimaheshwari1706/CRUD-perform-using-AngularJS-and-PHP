<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$conn = mysqli_connect("localhost", "root", "", "formdb");
if (mysqli_connect_errno()) {
  echo json_encode(["status" => "error", "message" => mysqli_connect_error()]);
  exit();
}

function getdata() {
  global $conn;
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM form";
    $result = $conn->query($sql);
    $data = array();
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    echo json_encode($data);
  }
}

function update() {
  global $conn;
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $UPDATE = isset($_POST['Arrar']) ? json_decode($_POST['Arrar'], true) : [];

    $id = $UPDATE['id'];
    $name = $UPDATE['name'];
    $email = $UPDATE['email'];
    $phone = $UPDATE['phone'];
    $address = $UPDATE['address'];
    $gender = is_array($UPDATE['gender']) ? implode(",", $UPDATE['gender']) : $UPDATE['gender'];
    $hobbies = is_array($UPDATE['hobbies']) ? implode(",", $UPDATE['hobbies']) : $UPDATE['hobbies'];
    $language = $UPDATE['language'];
    $code = $UPDATE['designation'];
    $filename = $UPDATE['file'];

    $sql = "UPDATE form SET 
              name='$name',
              email='$email',
              phone='$phone',
              address='$address',
              gender='$gender',
              hobbies='$hobbies',
              language='$language',
              code='$code',
              filename='$filename'
            WHERE id='$id'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
      echo json_encode(['status' => 'success', 'message' => 'Record updated']);
    } else {
      echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
  }
}

function delete() {
  global $conn;

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM form WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
      echo json_encode(["status" => "success", "message" => "Record deleted"]);
    } else {
      echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    exit();
  }
}

// Router
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  update();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
  delete();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  getdata();
} else {
  echo json_encode(["status" => "error", "message" => "Invalid request"]);
  exit();
}
?>
