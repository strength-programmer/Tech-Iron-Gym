<?php 
    include 'dash_button.php';

    // use given id 
    $id = $_GET['id'];

    // to get the values of the selected row
    if(isset($id)){
        include 'db_connect.php';


        $sql = "SELECT * FROM users WHERE username = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        include 'functions.php';
        update_user($row);
        mysqli_close($con);
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'update'){
        $id = $_POST['username'];
        $password = $_POST['password'];

        include 'db_connect.php';
        // escape special characters first
        $password = mysqli_real_escape_string($con, $password);

        // sql command
        $sql = "UPDATE users
                SET password='$password' 
                WHERE username='$id';
                ";

        $result = mysqli_query($con, $sql);
        if ($result) {
            session_start();
            $_SESSION['add_message'] = "Password has been updated.";
            header("Location:users.php");
        } else {
        echo "Error while updating user password...";
        }
        mysqli_close($con);


    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location:users.php");
        // DESTINATIONS: edit_user, functions view_user
        exit();
    }
?>