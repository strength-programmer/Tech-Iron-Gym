<?php
    $id = $_GET['id'];
    $filename = $_GET['filename'];
    $table = $_GET['table'];
    $table_id = $_GET['table_id'];


    session_start();
    
    if(isset($id)){
        include 'db_connect.php';


        $sql = "DELETE FROM $table WHERE $table_id = '$id'";
        $result = mysqli_query($con, $sql);

        try {
            $result = mysqli_query($con, $sql);
            if ($result) {
                $message = "Row has been deleted.";
                
            } else {
                $message = "Error while deleting row...";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "Error: " . $e->getMessage();
        }
        
        $_SESSION['add_message'] = $message;
        mysqli_close($con);
        header("Location: $filename");
    }

?>