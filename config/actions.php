<?php
    include "../assets/Database.php";
    $task = new Task();

    if(isset($_POST['action'])){

        //load_data
        if($_POST['action'] == 'LOAD'){
            $get_query = "SELECT * FROM task_list WHERE parent = '0' ORDER BY date_created DESC";
            echo $task->getData($get_query);
        }

        //add data
        if($_POST['action'] == "Insert"){
            $task_name = mysqli_real_escape_string($task->conn, $_POST['name']);
            $task_description = mysqli_real_escape_string($task->conn, $_POST['description']);
            $parent_task = mysqli_real_escape_string($task->conn, $_POST['parent_task']);
            $status = mysqli_real_escape_string($task->conn, $_POST['status']);
            $date_time = $_POST['end_date'];
            $end_date = mysqli_real_escape_string($task->conn, $date_time);
            // echo "<pre>";
            // print_r($_POST);
            // die();
            $create_query = "INSERT INTO task_list(name, description, parent, status, end_date) VALUES('".$task_name."', '".$task_description."', '".$parent_task."', '".$status."', '".$end_date."')";
            $task->execute_query($create_query);
            echo "New task Created"; 
        }

        //fetch_single_data
        if($_POST['action'] == "FETCH"){
            $data = [];
            $query = "SELECT * FROM task_list WHERE id = '".$_POST['task_id']."'";
            $res = $task->execute_query($query);
            while($row = mysqli_fetch_array($res)){
                // $data[] = $row;
                $data['name'] = $row['name'];
                $data['description'] = $row['description'];
                $data['date_created'] = $row['date_created'];
                $data['end_date'] = $row['end_date'];
            }
            echo json_encode($data);
        }

        //update_data
        if($_POST['action'] == "EDIT"){
            $task_name = mysqli_real_escape_string($task->conn, $_POST['name']);
            $task_description = mysqli_real_escape_string($task->conn, $_POST['description']);
            $date_time = $_POST['end_date'];
            $end_date = mysqli_real_escape_string($task->conn, $date_time);

            $update_query = "UPDATE task_list SET name='".$task_name."', description='".$task_description."', end_date='".$end_date."' WHERE id = '".$_POST['task_id']."'";
            $task->execute_query($update_query);
            echo "Task has been Updated";
        }

        //get parent_data
        if($_POST['action'] == 'PARENT'){
            $parent_query = "SELECT * FROM task_list WHERE parent = '0' ORDER BY id DESC";
            echo $task->getParentData($parent_query);
        }

        //delete_data
        if($_POST['action'] == 'DEL'){
            $child_id = $_POST['child_id'];
            echo "<pre>";
            print_r($_POST);
            // $delete_query = "DELETE FROM task_list WHERE id = '".$_POST['task_id']."' AND parent = '0'";
            // $task->execute_query($delete_query);
            echo "Task has been Deleted";
        }

        //bulk_delete_data
        if($_POST['action'] == 'BULK'){
            foreach($_POST['select_id'] as $select_id){
                $delete_query_bulk = "DELETE FROM task_list WHERE id = '".$select_id."'";
                $task->execute_query($delete_query_bulk);
            }
            echo "Delete Complete";
        }

        //select_complete
        if($_POST['action'] == 'SEL_CMP'){
            $status = $_POST['select_id'] ;
            $complete_query = "UPDATE task_list SET status = '".$status."' WHERE id = '".$_POST['task_id']."'";
            $task->execute_query($complete_query);
            echo "Task Updated";
        }

        //child_delete
        if($_POST['action'] == 'CH_DEL'){
            $parent_id = $_POST['parent_id'];
            $child_id = $_POST['child_id'];
            // echo "<pre>";
            // print_r($_POST);
            $child_del_query = "DELETE FROM task_list WHERE id = '".$child_id."' AND parent = '".$parent_id."'";
            $task->execute_query($child_del_query);
            echo "Child Task has been Deleted";
        }

        //get child_data
        // if($_POST['action'] == 'CHILD'){
        //     $id = $_GET['task_id'];
        //     $child_query = "SELECT name FROM task_list WHERE parent = '".$id."'";
        //     echo $task->getChilddata($child_query);
        // }
        // if($_POST['action'] == 'CHILD'){
        //     $parent_id = $_POST['parent_id'];
        //     echo "<pre>";
        //     print_r($_POST);
        //     $child_query = "SELECT * FROM task_list WHERE parent = '".$parent_id."'";
        //     echo $task->getChildData($child_query);
        // }

    }



?>