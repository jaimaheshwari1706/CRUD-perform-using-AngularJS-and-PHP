  <?php
  ob_start();

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization");
  header("Content-Type: application/json");

  class AngularModel
  {
    protected $db;
    protected $view;
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
      $this->db = $db;
    }

    public function login($email, $password)
    {
      if ($email == 'jai@gmail.com' && $password == '123') {
        return ['id' => 1, 'email' => $email];
      } else {
        return ['id' => '', 'email' => ''];
      }
    }

    public function getAllData()
    {
      $sql2 = "SELECT * FROM form";
      $result = $this->db->prepare($sql2);
      $result->execute();
      $count1 = $result->rowCount();
      $data = array();
      if ($count1  > 0) {
        while ($row = $result->fetch()) {
          $hobbies = $row->hobbies;
          $gender = $row->gender;
          if (isset($hobbies)) {
            $hobbies = array_map('trim', explode(',',  $hobbies));
          }
          if (isset($gender)) {
            $gender = array_map('trim', explode(',', $gender));
          }
          $data[] = $row;
        }
      }
      // echo json_encode($data);
      return $data;
    }

    // public function addData($arr)
    // {
    //   $name = $arr[0]['name'];
    //   $email = $arr[0]['email'];
    //   $phone = $arr[0]['phone'];
    //   $address = $arr[0]['address'];
    //   $gender = is_array($arr[0]['gender']) ? implode(",", $arr[0]['gender']) : $arr[0]['gender'];
    //   $hobbies = is_array($arr[0]['hobbies']) ? implode(",", $arr[0]['hobbies']) : $arr[0]['hobbies'];
    //   $language = $arr[0]['language'];
    //   $code = $arr[0]['designation'];
    //   $filename = $arr[0]['file'];

    //   $query = "INSERT INTO form(name, email, phone, address, gender, hobbies, language, code, filename) VALUES (?,?,?,?,?,?,?,?,?)";
    //   $result = $this->db->prepare($query);
    //   $result->execute(array($name, $email, $phone, $address, $gender, $hobbies, $language, $code, $filename));

    //   return $result;
    //   // print_r($arr[0]);
    //   // exit;

    // }
    public function deleteData($id)
    {
      $query = "DELETE FROM form WHERE id = ?";
      $result = $this->db->prepare($query);
      $result->execute(array($id));
      return $result;
    }

    public function updateData($arr)
    {

      $name = $arr[0]['name'];
      $email = $arr[0]['email'];
      $phone = $arr[0]['phone'];
      $address = $arr[0]['address'];
      $gender = is_array($arr[0]['gender']) ? implode(",", $arr[0]['gender']) : $arr[0]['gender'];
      $hobbies = is_array($arr[0]['hobbies']) ? implode(",", $arr[0]['hobbies']) : $arr[0]['hobbies'];
      $language = $arr[0]['language'];
      $code = $arr[0]['designation'];
      $filename = $arr[0]['file'];

      $query = "UPDATE form SET name = ?, email = ?, phone = ?, address = ?, gender = ?, hobbies = ?, language = ?, code = ?, filename = ? WHERE id = ?";
      $result = $this->db->prepare($query);
      $result->execute(array($name, $email, $phone, $address, $gender, $hobbies, $language, $code, $filename));

      return $result;
    }


    public function getDatatableData($start, $length, $searchValue, $orderBy, $orderDir)
    {
      $search = '%' . trim($this->db->quote($searchValue), "'") . '%';

      $countQuery = "SELECT COUNT(*) AS total FROM form 
                        WHERE name LIKE '%$search%' 
                        OR email LIKE '%$search%' 
                        OR phone LIKE '%$search%' 
                        OR id LIKE '%$search%'";
      $resultCount = $this->db->query($countQuery);
      $totalRecords = 0;
      if ($resultCount && $rowCount = $resultCount->fetch(PDO::FETCH_ASSOC)) {
        $totalRecords = $rowCount['total'];
      }

      // Get paginated data
      $query = "SELECT id, name, email, phone, address FROM form 
                    WHERE name LIKE '%$search%' 
                    OR email LIKE '%$search%' 
                    OR phone LIKE '%$search%' 
                    OR id LIKE '%$search%' 
                    ORDER BY $orderBy $orderDir 
                    LIMIT $start, $length";

      $resultData = $this->db->query($query);
      $data = [];
      if ($resultData) {
        while ($row = $resultData->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      }

      return [
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data" => $data
      ];
    }

        public function getdataOrganization($start, $length, $searchValue, $orderBy, $orderDir, $organizationId)
    {
        // Validate and sanitize inputs
        $organizationId = (int)$organizationId;
        $start = (int)$start;
        $length = (int)$length;

        // Add Gender to allowed columns for ordering
        $allowedColumns = ['Id', 'Name', 'Code', 'OrganizationId', 'gender'];
        $orderBy = in_array($orderBy, $allowedColumns) ? $orderBy : 'Id';
        $orderDir = (strtoupper($orderDir) === 'DESC') ? 'DESC' : 'ASC';

        // Total records (no join needed here)
        $totalRecords = (int)$this->db->query("SELECT COUNT(*) FROM designationmaster")->fetchColumn();

        // WHERE clause
        // $where = "WHERE d.OrganizationId = $organizationId";
        // if (!empty($searchValue)) {
        //     $where .= " AND (d.Name LIKE '%$searchValue%' 
        //                 OR d.Code LIKE '%$searchValue%' 
        //                 OR d.Id LIKE '%$searchValue%' 
        //                 OR (f.gender IS NOT NULL AND f.gender LIKE '%$searchValue%'))";
        // }

        // Filtered count with JOIN
        $filteredCountQuery = "
            SELECT COUNT(*) 
            FROM designationmaster d 
            LEFT JOIN form f ON d.Code = f.code 
            
        ";
        $totalFilteredRecords = (int)$this->db->query($filteredCountQuery)->fetchColumn();

        // Main data query with JOIN
        $dataQuery = "
            SELECT d.Id, d.Name, d.Code, d.OrganizationId, f.gender
            FROM designationmaster d 
            LEFT JOIN form f ON d.Code = f.code 
            
            ORDER BY $orderBy $orderDir
            LIMIT $start, $length
        ";

        $data = $this->db->query($dataQuery)->fetchAll(PDO::FETCH_ASSOC);

        return [
            "draw" => $_POST['draw'] ?? 1,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFilteredRecords,
            "data" => $data
        ];
    }
  }
