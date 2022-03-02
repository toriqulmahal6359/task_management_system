<?php

    class Database
    {
        private $host = "localhost";
        private $username = "root";
        private $password = "rootpassword";
        private $dbname = "task_db";

        public $conn;

        public function __construct()
        {   
            $this->connect();    
        }

        public function connect(){
            $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);
        }

        public function execute_query($query){
            return mysqli_query($this->conn, $query);
        }

    }

    class Task extends Database{

        public function getData($query){
            
            $data = '';
            $result = $this->execute_query($query);
            $data .= '
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><button class="btn btn-danger btn-sm bulk-delete-btn">Delete</button></th>
                        <th width="5%">ID</th>
                        <th width="5%">Status</th>
                        <th width="15%">Task Name</th>
                        <th width="30%">Task Description</th>
                        <th width="30%">Child Task</th>
                        <th width="20%">Date Created</th>
                        <th width="30%">End Date</th>
                        <th width="30%">Completion Status</th>
                        <th width="20%">Update</th>
                        <th width="20%">Delete</th>
                        <th width="20%">Task Status</th>
                    </tr>
                ';

            while($row = mysqli_fetch_object($result)){
                $child_task = array();
                // echo "<pre>";
                // print_r($row);
                $expire = $this->expired_task($row->id);
                $cmp_del = $this->complete_delete_task($row->id);
                $sql = "SELECT * FROM task_list WHERE parent = '".$row->id."'";
                $res = $this->execute_query($sql);
                
                $data .= '<tr id="'.$row->id.'">
                <td><input type="checkbox" name="bulk_select[]" id="bulk_select" style="text-align: left" value="'.$row->id.'"></td>
                <td>'.$row->id.'</td>';
                $data .= '<input type="hidden" name="task_id" id="task_id" value="'.$row->id.'">'; 
                    if($row->status == 1){ 
                        $data .= '<td><input type="checkbox" name="status" id="'.$row->id.'" class="task_status" style="text-align: left" value="'.$row->status.'" checked></td>';
                    }else{ 
                        $data .='<td><input type="checkbox" name="status" id="'.$row->id.'" class="task_status" style="text-align: left" value="'.$row->status.'"></td>';
                    }
                    $data .= '<td>'.$row->name.'</td>
                    <td>'.$row->description.'</td>';
                    $data .= '<td>';
                    while($child = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                        // echo "<pre>";
                        // print_r($child);
                        // $child_task['name'] = $child['name'];
                        $data .= '<input type="hidden" name="child_id" id="child_id" class="child_id" value="'.$child['id'].'"></input>';
                        $data .= '<ul><li>'.$child['name'].'&nbsp;<a href="javascript:void(0)" id="'.$row->id.'" class="child_del" onClick="childDel('.$child['id'].')">Delete</a></li></ul>';
                    }
                    $data .= '</td>';
                    $data .= '<td>'.$row->date_created.'</td>
                    <td>'.$row->end_date.'</td>';
                    if($row->status == 1){
                        $data .= '<td><button name="complete_task" class="btn btn-info btn-sm cmplte_task_btn" id="'.$row->id.'" disabled>Complete</button></td>';
                    }else{
                        $data .= '<td><button name="complete_task" class="btn btn-danger btn-sm incmplte_task_btn" id="'.$row->id.'" disabled>Incomplete</button></td>';
                    }
                    
                    $data .= '<td><button name="update_task" class="btn btn-success btn-sm update_task_btn" id="'.$row->id.'">Update</button></td>
                    <td><button name="delete_task" class="btn btn-danger btn-sm delete_task_btn" id="'.$row->id.'">Delete</button></td>
                    <td>'.$expire.'</td>
                    </tr>';
            }
            $data .= '</table>';
            return $data;
        }

        public function getParentData($query){
            $parent = '';
            $res = $this->execute_query($query);
            $parent .= '<option value="0">--Select Parent Task--</option>';
            while($rows = mysqli_fetch_object($res)){
                $parent .= '<option value="'.$rows->id.'">'.$rows->name.'</option>';
            }
            return $parent;
        }

        public function expired_task($id){
            $data = '';
            $sql = "SELECT * FROM task_list WHERE id = '".$id."'";
            $res = $this->execute_query($sql);
            $current_date = $_SERVER["REQUEST_TIME"];
            $today = date("Y-m-d h:i:s", $current_date);

            $today_time_str = strtotime($today);
            while($row = mysqli_fetch_object($res)){
                $ending_time = $row->end_date;
                $end_time_str = strtotime($ending_time);
                if($today_time_str > $end_time_str){
                    $data .= 'Expired';
                }
            }
            return $data;   
        }

        public function complete_delete_task($id){
            $date = date('m-d-Y', strtotime('+2 days'));
            $sql = "DELETE FROM task_list WHERE id = '".$id."' AND status = '1' AND end_date < '".$date."'";
            $res = $this->execute_query($sql);
            return $res;
        }

        // public function getChilddata($query){
        //     $child = '';
        //     $res = $this->execute_query($query);
        //     while($rows = mysqli_fetch_object($res)){
        //         $child .= '<tr>
        //         <td>'.$rows->name.'</td>
        //         <td><button name="delete_child_task" class="btn btn-danger btn-sm delete_task_btn" id="'.$rows->id.'">Delete</button></td>
        //         </tr>';
        //     }
        //     return $child;
        // }

        // public function getChildData($query){
        //     $child = '';
        //     $res = $this->execute_query($query);
        //     while($rows = mysqli_fetch_object($res)){
        //         $child = $rows->name;
        //     }
        //     return $child;
        // }
    }
?>