<?php
/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * @author   https://zehntech.com/
 */
class DB{
    private $dbHost     = "localhost";
    private $dbUsername = "badges";
    private $dbPassword = 'badges';
    private $dbName     = "badges";
    
    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }
   
    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table, $conditions = array()){
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }else{
            $sql .= ' ORDER BY id DESC '; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        
        $result = $this->db->query($sql);
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $result->num_rows;
                    break;
                case 'single':
                    $data = $result->fetch_assoc();
                    break;
                default:
                    $data = '';
            }
        }else{
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
            }
        }
        return !empty($data)?$data:false;
    }



    public function get_suppliers_email_list($table_name){
        //$sql = 'SELECT * from '.$table_name.'';

        //    $sql = 'SELECT `email`,GROUP_CONCAT(`param`) FROM '.$table_name.' WHERE DATE(`created_time`) = DATE(NOW()) GROUP BY email
        //     ';

        // $sql = 'SELECT* FROM '.$table_name.' WHERE DATE(`created_time`) = DATE(NOW())';

        $sql = 'SELECT DISTINCT consigncloud_supplier.email, consigncloud_supplier.id FROM consigncloud_supplier INNER JOIN consigncloud_supplier_items ON consigncloud_supplier.id = consigncloud_supplier_items.supplier_id WHERE DATE(consigncloud_supplier_items.`created_time`) = DATE(NOW())
        ';
        $result = $this->db->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
        }
        return !empty($data)?$data:false;
    }
    
    public function get_suppliers_item($table_name,$supplier_id){

        require_once('vendor/autoload.php');
        $client = new \GuzzleHttp\Client();


        //$sql = 'SELECT * from '.$table_name.'';
        //    $sql = 'SELECT `email`,GROUP_CONCAT(`param`) FROM '.$table_name.' WHERE DATE(`created_time`) = DATE(NOW()) GROUP BY email
        //     ';
        // $sql = 'SELECT* FROM '.$table_name.' WHERE DATE(`created_time`) = DATE(NOW())';
        // $sql = 'SELECT DISTINCT consigncloud_supplier.email, consigncloud_supplier.id FROM consigncloud_supplier INNER JOIN consigncloud_supplier_items ON consigncloud_supplier.id = consigncloud_supplier_items.supplier_id WHERE DATE(consigncloud_supplier_items.`created_time`) = DATE(NOW())
        // ';

        $sql = 'SELECT* FROM '.$table_name.' WHERE supplier_id='.$supplier_id.' AND DATE(`created_time`) = DATE(NOW())';

        $result = $this->db->query($sql);
        $tr='';
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $data[] = $row;
                $item_lists = json_decode($row['item_lists']);
                //  echo "<pre>";
                //  print_r($item_lists);
                //  echo "</pre>";
                $tr .= "<tr><td>".$item_lists->email."</td><td>".$item_lists->number."</td><td>".$item_lists->line_items->title."</td></tr>";
            }
            $table = "<table border='1'><tr><th>Email</th><th>Number</th><th>Title</th></tr>".$tr."</table>";
           
            echo $item_lists->email;
            echo "<br>";
            
            echo $item_lists->first_name;
            echo "<br>";

            echo $item_lists->last_name;
            echo "<br>";

        
        // Update template 

          $response = $client->request('PUT', 'https://a.klaviyo.com/api/v1/email-template/QUuJ8n?api_key=pk_7b16755cb90e647cf1a1aaed020e3d0d46', [
            'form_params' => [
              'html' => '<html><body><p>This is an email for "'.$item_lists->email.'".</p></body></html>'
            ],
            'headers' => [
              'accept' => 'application/json',
              'content-type' => 'application/x-www-form-urlencoded',
            ],

        ]);
    
        print_r($response);

        // $delete_profiles = array($item_lists->email);

        // $rr = json_encode($delete_profiles);
      
        // $response = $client->request('DELETE', 'https://a.klaviyo.com/api/v2/list/WE9Bpp/members?api_key=pk_7b16755cb90e647cf1a1aaed020e3d0d46', [
        //     'body' => '{"emails":'.$rr.'}',
        //     'headers' => [
        //       'content-type' => 'application/json',
        //     ],
        //   ]);


        // $data_array = array("email"=>$item_lists->email,"first_name"=>$item_lists->first_name, "last_name"=>$item_lists->last_name, "number"=>'0123', "account"=>'test account', "tag_price"=>'tag_price');
        // $response = $client->request('POST', 'https://a.klaviyo.com/api/v2/list/WE9Bpp/subscribe?api_key=pk_7b16755cb90e647cf1a1aaed020e3d0d46', [
        //     'body' => '{"profiles":['.json_encode($data_array).']}',
        //     'headers' => [
        //     'accept' => 'application/json',
        //     'content-type' => 'application/json',
        // ],
        // ]);

       // print_r($response);

        //echo $table;

        }
        
        

        // echo "<pre>";
        // print_r($data[0]['item_lists']);
        // echo "</pre>";

        //return !empty($data)?$data:false;
    }
    

    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table, $data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
            if(!array_key_exists('created',$data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $columns .= $pre.$key;
                $values  .= $pre."'".$this->db->real_escape_string($val)."'";
                $i++;
            }
            $query = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
            $insert = $this->db->query($query);
            return $insert?$this->db->insert_id:false;
        }else{
            return false;
        }
    }
    
    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table, $data, $conditions){
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$this->db->real_escape_string($val)."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
           echo  $query = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $update = $this->db->query($query);
            return $update?$this->db->affected_rows:false;
        }else{
            return false;
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table, $conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $query = "DELETE FROM ".$table.$whereSql;
        $delete = $this->db->query($query);
        return $delete?true:false;
    }
   
   /* public function badges($table_name, $col_ids){        

        $sql = "SELECT title FROM $table_name WHERE collection_id IN($col_ids) order by status LIMIT 1";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $title =  $row['title'];
       
       // print_r($row);
         
        // if($result->num_rows > 0){
        //     while($row = $result->fetch_assoc()){
        //         $data[] = $row['title'];
        //     }
        // }
        // return !empty($data)?$data:false;
    }*/

    public function badges($table, $conditions = array(), $ids, $collection_id){
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){                
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." IN ($ids) AND collection_id != '".$collection_id."' AND is_delete = 0" ;
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }
        else{
            $sql .= ' ORDER BY id DESC '; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        
        $result = $this->db->query($sql);
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $result->num_rows;
                    break;
                case 'single':
                    $data = $result->fetch_assoc();
                    break;
                default:
                    $data = '';
            }
        }else{
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
            }
        }
        //echo $sql;
        $data_blank_array1=array('id'=>'', 'title'=>'', 'collection_handle' => '');
        return !empty($data)?$data:$data_blank_array1;
    }

    public function badges_single($table, $conditions = array(), $ids){
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){                
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." IN ($ids) AND is_delete = 0" ;          
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }
        else{
            $sql .= ' ORDER BY id DESC '; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        
        $result = $this->db->query($sql);
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $result->num_rows;
                    break;
                case 'single':
                    $data = $result->fetch_assoc();
                    break;
                default:
                    $data = '';
            }
        }else{
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
            }
        }
        //echo $sql;
        $data_blank_array=array('id'=>'', 'title'=>'', 'collection_handle' => '');
        return !empty($data)?$data:$data_blank_array;
    }

}

