<?php
// require('cors.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");


/**
 * Class Index
 * The index controller
 */
class Angular extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
        // this controller should only be visible/usable by logged in users, so we put login-check here
        // Auth::handleLogin();
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $data = $this->loadModel('Angular');
        $user = $data->login($email, $password);
        if ($user) {
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            // echo '2';
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }
    public function getalldata()
    {
        $data = $this->loadModel('Angular');
        $call = $data->getAllData();
        echo json_encode($call);
    }


    public function adddata()
    {
        $arr = array();
        $arr[0] = json_decode($_REQUEST['Arrar'], true);

        $data = $this->loadModel('Angular');
        $call = $data->addData($arr);
        echo json_encode($call);
    }

    public function deleteData()
    {
        $id = $_REQUEST['id'];
        $data = $this->loadModel('Angular');
        $call = $data->deleteData($id);
        echo json_encode($call);
    }

    public function updateData()
    {
        $arr = array();
        $arr[0] = json_decode($_REQUEST['Arrar'], true);

        $data = $this->loadModel('Angular');
        $call = $data->updateData($arr);
        echo json_encode($call);
    }



    public function datatable()
    {
        $draw = $_GET['draw'] ?? 1;
        $start = (int) ($_GET['start'] ?? 0);
        $length = (int) ($_GET['length'] ?? 10);
        $searchValue = $_GET['search']['value'] ?? '';
        $orderColumn = $_GET['order'][0]['column'] ?? 0;
        $orderDir = $_GET['order'][0]['dir'] ?? 'asc';

        $columns = ['id', 'name', 'email', 'phone', 'address'];
        $orderBy = $columns[$orderColumn] ?? 'name';

        $model = $this->loadModel('Angular');
        $result = $model->getDatatableData($start, $length, $searchValue, $orderBy, $orderDir);

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $result['recordsTotal'],
            "recordsFiltered" => $result['recordsFiltered'],
            "data" => $result['data']
        ]);
        exit;
    }

    public function datatable2(){
        // $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $search = $_POST['search']['value'] ?? '';
        $orderColIndex = $_POST['order'][0]['column'] ?? 0;
        $orderDir = $_POST['order'][0]['dir'] ?? 'asc';
        $columns = ['Id', 'Name', 'Code', 'OrganizationId', 'gender'];
        $orderBy = $columns[$orderColIndex] ?? 'Id';
        $orgId = $_POST['organization_id'] ?? 0;
        
        $model = $this->loadModel('Angular'); // Initialize $yourClass with the appropriate model
        $response = $model->getdataOrganization($start, $length, $search, $orderBy, $orderDir, $orgId);
        
        header('Content-Type: application/json');
        echo json_encode($response);
        
    }
}
