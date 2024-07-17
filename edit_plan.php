<?php 
    session_start();
    if (!isset($_SESSION['logged'])) { 
        header('Location: index.php');
        exit;
    }

    include 'dash_button.php';

    // use given id 
    $id = $_GET['id'];

    // to get the values of the selected row
    if(isset($id)){
        include 'db_connect.php';


        $sql = "SELECT * FROM plans WHERE plan_id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        include 'functions.php';
        update_plan($row);
        mysqli_close($con);
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'update'){
        $id = $_POST['plan_id'];
        $plan_name = $_POST['plan_name'];
        $validity = $_POST['validity'];
        $price = $_POST['price'];
        


        include 'db_connect.php';
        // escape special characters first
        $plan_name = mysqli_real_escape_string($con, $plan_name);
        $validity = mysqli_real_escape_string($con, $validity);
        $price = mysqli_real_escape_string($con, $price);

        // sql command
        $sql = "UPDATE plans
                SET duration='$plan_name', 
                    validity='$validity', 
                    price='$price'
                WHERE plan_id='$id';
                ";

        try {
            $result = mysqli_query($con, $sql);
            if ($result) {
                session_start();
                $_SESSION['add_message'] = "Plan has been updated";
                header("Location:plans.php");
            } else {
               echo "Error while updating plan...";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "Error: Duplicate entry for plan name.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
        mysqli_close($con);


    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location:plans.php");
        // DESTINATIONS: edit_plan, functions view_plan
        exit();
    }
?>