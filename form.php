<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$conn = mysqli_connect("localhost", "root", "", "formdb");
if (mysqli_connect_errno()) {
  echo "" . mysqli_connect_error();
}

// function form()
// {
//  global $conn;

//   if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $form = isset($_POST['Arrar']) ? json_decode($_POST['Arrar'], true) : '';
//     // print_r(($form));
//     $name = $form[0];
//     $email = $form[1];
//     $phone = $form[2];
//     $address = $form[3];
//     $gender = $form[4];
//     $hobbies = implode(',',$form[5]);
//     $language = $form[6];
//     // echo $hobbies;

//         $sql = "INSERT INTO form (name, email, phone, address, gender, hobbies, language) 
//         VALUES ('$name', '$email', '$phone', '$address', '$gender', '$hobbies', '$language')";
//         $query=$conn->prepare(($sql));
//          $query->execute();
//         //  return true;
//         // $result = mysqli_query($conn, $sql);
//         // echo $run;


//     // if (mysqli_query($conn, $sql)) {

//     //   echo "Form data submitted successfully.";
//     // } else {
//     //   echo "Error submitting form: " . mysqli_error($conn);
//     // }
//   }
// }
// form();
function create()
{
  global $conn;
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CREATE = isset($_POST['Arrar']) ? json_decode($_POST['Arrar'], true) : '';
    // echo  $CREATE ;
    print_r($_POST['Arrar']);
    $name = $CREATE['name'];
    $email = $CREATE['email'];
    $phone = $CREATE['phone'];
    $address = $CREATE['address'];
    $gender = $CREATE['gender'];
    $hobbies = $CREATE['hobbies'];
    $language = $CREATE['language'];
    $code = $CREATE['designation'];
    $filename = $CREATE['file'];

    $sql = "INSERT INTO form (name, email, phone, address, gender, hobbies, language, code, filename) 
        VALUES ('$name', '$email', '$phone', '$address', '$gender', '$hobbies', '$language', '$code', '$filename')";
    $query = $conn->prepare(($sql));
    $query->execute();

  }
}
create();

function getdata(){
  global $conn;

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql2 = "SELECT name, code FROM designationmaster";
    $result = $conn->query($sql2);
    // print_r ($result);
    $data = array();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        // echo $row;
      }
    }
    // print_r ($data);
    echo json_encode($data);
    // exit;
  }
}
getdata();