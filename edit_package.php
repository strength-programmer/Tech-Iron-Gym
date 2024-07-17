<?php 
    ob_start();
    include "dash_button.php";
    //use given id
    $id = $_GET['id'];
    // to get the values of the selected row
    if(isset($id)){
        include 'db_connect.php';

        $sql = "SELECT * FROM packages WHERE package_id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        include 'functions.php';
        update_package($row);
        mysqli_close($con);
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'update'){
        $id = $_POST['package_id'];
        $package_name = $_POST['package_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        

        include 'db_connect.php';
        // escape special characters first
        $package_name = mysqli_real_escape_string($con, strip_tags(trim($_POST['package_name'])));
        $description = mysqli_real_escape_string($con, strip_tags(trim($_POST['description'])));
        $price = mysqli_real_escape_string($con, $_POST['price']);

        // sql command
        $sql = "UPDATE packages
                SET package_name='$package_name', 
                    description='$description', 
                    price='$price'
                WHERE package_id='$id';
                ";

        try {
            $result = mysqli_query($con, $sql);
            if ($result) {
                session_start();
                $_SESSION['add_message'] = "Package has been updated";
                header("Location:packages.php");
            } else {
               echo "Error while updating package...";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "Error: Duplicate entry for package name.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
        mysqli_close($con);
        


    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location:packages.php");
        exit();
    }
?>