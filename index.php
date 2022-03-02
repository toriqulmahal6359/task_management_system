<?php
    include 'assets/Database.php';
    $task = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
</head>
<body>
    <button type="submit" name="add_task" class="btn btn-success" data-target="#task-collapse" data-toggle="collapse">Add Task</button>
    <div id="task-collapse" class="collapse col-md-10 offset-md-1">
        <form method="post" id="task_form">
            <label for="">Task Name</label>
            <input type="text" name="name" id="task_name" class="form-control"><br>
            <label for="">Short Description</label>
            <textarea name="description" id="task_description" cols="30" rows="10" class="form-control"></textarea><br>
            <label for="">End Date</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control"><br>
            <label for="">Parent Task</label>
            <select name="parent_task" id="parent_task" class="form-control">
                
            </select>
            <br>
            <input type="checkbox" name="status" id="status" value="0">&nbsp;Not Completed&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="status" id="status" value="1">&nbsp;Completed&nbsp;&nbsp;&nbsp;
            <br><br><br>
            <div>
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="task_id" id="task_id">
                <input type="hidden" name="parent_id" id="parent_id">
                <input type="submit" class="btn-action btn btn-primary" id="btn-action" value="Add">
            </div>
            <br>
        </form>
    </div>
    <div class="container-fluid">
        <div class="task-table table-responsive table-striped col-lg-12">

        </div>
    </div>
    
</body>
</html>
<script>
    $(document).ready(function(){

        //function call
        load_data();
        load_parent_data();
        $("#action").val("Insert");

        function load_data(){
            var action = "LOAD";
            $.ajax({
                url: "config/actions.php",
                type: "POST",
                data: {
                    action: action
                },
                success:function(data){
                    $(".task-table").html(data);
                }
            });
        }

        $("#task_form").on('submit', function(e){
            e.preventDefault();
            load_parent_data();
            var task_name = $("#task_name").val();
            var task_description = $("#task_description").val();
            var parent_task = $("#parent_task").val();
            var status = $("#status").val();
            // var date_created = $("#date_created").val();
            if(task_name != '' && task_description != '' && status != ''){
                $.ajax({
                    url: "config/actions.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data){
                        alert(data);
                        $('#task_form')[0].reset();
                        load_data();
                        $("#action").val("Insert");
                        $(".btn-action").val("Insert");
                    }
                })
            }else{
                alert("All Fields are Required");
            }
        });
        
        $(document).on('click', '.update_task_btn', function(){
            var task_id = $(this).attr("id");
            var action = "FETCH";
            $.ajax({
                url: "config/actions.php",
                method: "POST",
                data: {
                    task_id: task_id,
                    action: action
                },
                dataType: "json",
                success: function(data){
                    $('.collapse').collapse('show');
                    $('#task_name').val(data.name);
                    $('#task_description').val(data.description);
                    $('#end_date').val(data.end_date);
                    $("#btn-action").val("Edit");
                    $("#action").val("EDIT");
                    $("#task_id").val(task_id);
                } 
            })
        });

        $(document).on('click', '.delete_task_btn', function(){
            var task_id = $(this).attr("id");
            var child_id = $("#child_id").val();
            var action = "DEL";
            $.ajax({
                url: "config/actions.php",
                method: "POST",
                data: {
                   child_id: child_id,
                   task_id: task_id,
                   action: action 
                },
                success: function(data){
                   let del = "Are You Sure you want to Delete this Task ?";
                   if(confirm(del) == true){
                       alert(data);
                       load_data();
                   }
                }
            })
        });

        $(document).on('click', '.bulk-delete-btn', function(){
            if(confirm("Are You sure You want to Delete ?")){
                var select_id = [];
                var action = "BULK";

                $('#bulk_select:checked').each(function(i){
                    select_id[i] = $(this).val();
                });

                if(select_id.length === 0){
                    alert("Please check at least One Checkbox");
                }else{
                    $.ajax({
                        url: "config/actions.php",
                        method: "POST",
                        data:{
                            select_id: select_id,
                            action: action
                        },
                        success: function(data){
                            for(var i=0; i < select_id.length; i++){
                                $("tr#"+select_id[i]+'').fadeOut('slow');
                                alert(data);
                            }
                        }
                    });
                }
            }else{
                return false;
            }
        });

        // $(document).on('click', '.multiple-select-btn', function(){
        //     var task_id = [];
        //     var action = "_SEL";
        //     var status_select = [];

        //     $
        // });

        $(document).on('click', '.task_status:checked', function(){
            var task_id = $(this).attr('id');
            var action = 'SEL_CMP';
            var select_id = $('.task_status').val();

            $.ajax({
                url: "config/actions.php",
                method: "POST",
                data: {
                    task_id: task_id,
                    select_id: select_id,
                    action: action
                },
                success: function(data){
                    load_data();
                }
            });
        });  
           
        function load_parent_data(){
            var action = "PARENT";
            $.ajax({
                url: "config/actions.php",
                type: "POST",
                data: {
                    action: action
                },
                success:function(data){
                    console.log(data);
                    $("#parent_task").html(data);
                }
            });
        }

        //parent_child_del
        // function load_child_data(){
        //     load_parent_data();
        //     var parent_id = $("#parent_task").val();
        //     var action = "CHILD";
        //     $.ajax({
        //         url: "config/actions.php",
        //         type: "POST",
        //         data: {
        //             parent_id: parent_id,
        //             action: action
        //         },
        //         success:function(data){
        //             console.log(data);
        //         }
        //     });
        // }

    });

    function childDel(id){
            
        var child_id = id;
        var parent_id = $('.child_del').attr('id');
        var action = 'CH_DEL';
        $.ajax({
            url: "config/actions.php",
            type: "POST",
            data:{
                parent_id: parent_id,
                child_id: child_id,
                action: action
            },
            success: function(data){
                alert(data);
            }
        });
    } 

</script>