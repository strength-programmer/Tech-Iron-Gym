<?php 
  session_start();
  if (!isset($_SESSION['logged'])) { 
    header('Location: index.php');
    exit;
    }
    ob_start();
    // use given id 
    include ('dash_button.php');
    $id = $_GET['id'];

    // to get the values of the selected row
    if(isset($id)){
        include 'db_connect.php';

        $sql = "SELECT * FROM trainers WHERE trainer_id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        include 'functions.php';
        update_trainer($row);
        mysqli_close($con);
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'update'){
        $id = $_POST['trainer_id'];
        $trainer_name = $_POST['trainer_name'];
        $trainer_email = $_POST['trainer_email'];
        $trainer_contact = $_POST['trainer_contact'];
        $specialization = $_POST['specialization'];
        $rate = $_POST['rate'];
        
        include 'db_connect.php';
        // escape special characters first
        $trainer_name = mysqli_real_escape_string($con, $trainer_name);
        $trainer_email = mysqli_real_escape_string($con, $trainer_email);
        $trainer_contact = mysqli_real_escape_string($con, $trainer_contact);
        $specialization = mysqli_real_escape_string($con, $specialization);
        $rate = mysqli_real_escape_string($con, $rate);


        // sql command
        $sql = "UPDATE trainers
                SET trainer_name='$trainer_name', 
                    trainer_email='$trainer_email',
                    trainer_contact='$trainer_contact',
                    specialization='$specialization', 
                    rate='$rate'
                WHERE trainer_id='$id';
                ";

        try {
            $result = mysqli_query($con, $sql);
            if ($result) {
                session_start();
                $_SESSION['add_message'] = "Trainer has been updated";
                header("Location:trainers.php");
            } else {
               echo "Error while updating trainer...";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "Error: Duplicate entry for trainer.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
        mysqli_close($con);


    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location:trainers.php");
        exit();
    }
?>