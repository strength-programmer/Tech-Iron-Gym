<?php
  session_start();
  if (!isset($_SESSION['logged'])) { 
    header('Location: index.php');
    exit;
    }
    ob_start();
    include ('dash_button.php');

    // use given id 
    $id = $_GET['id'];

    // to get the values of the selected row
    if(isset($id)){
        include 'db_connect.php';

        $sql = "SELECT * FROM members WHERE member_id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        include 'functions.php';
        update_members($row);
        mysqli_close($con);
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'update'){
        $id = $_POST['member_id'];
        $fname = $_POST['fname'];
        $mname = isset($_POST['mname'])? $_POST['mname'] : "";
        $lname = $_POST['lname'];
        $birthday = $_POST['birthday'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
        $start_date = $_POST['start_date'];
        $plan = isset($_POST['plan']) ? $_POST['plan'] : "";
        $package = isset($_POST['package']) ? $_POST['package'] : "";
        $trainer = isset($_POST['trainer']) ? $_POST['trainer'] : "";
        $status = isset($_POST['status']) ? $_POST['status'] : "";
        

        include 'db_connect.php';
        // escape special characters first
        $fname = mysqli_real_escape_string($con, strip_tags($fname));
        $mname = mysqli_real_escape_string($con, strip_tags($mname));
        $lname = mysqli_real_escape_string($con, strip_tags($lname));

        $email = mysqli_real_escape_string($con, strip_tags($email));
        $contact = mysqli_real_escape_string($con, strip_tags($contact));
        $address = mysqli_real_escape_string($con, strip_tags($address));
        $gender = mysqli_real_escape_string($con, strip_tags($gender));
        $start_date = mysqli_real_escape_string($con, strip_tags($start_date));
        $plan = mysqli_real_escape_string($con, strip_tags($plan));
        $package = mysqli_real_escape_string($con, strip_tags($package));
        $trainer = mysqli_real_escape_string($con, strip_tags($trainer));
        $status = mysqli_real_escape_string($con, strip_tags($status));

        // Calculate end date
        $plan_duration = get_plan_duration($plan); // Assume this function gets the duration of the plan in months
        $end_date = date('Y-m-d', strtotime("+$plan_duration months", strtotime($start_date)));

        //format dates
        $birthday = date('F d, Y', strtotime($birthday));
        $start_date = date('F d, Y', strtotime($start_date));
        $end_date = date('F d, Y', strtotime($end_date));



        // sql command
        $sql = "UPDATE members
                SET fname='$fname', 
                    mname='$mname', 
                    lname='$lname',
                    birth_date='$birthday',
                    member_email ='$email',
                    member_contact ='$contact',
                    address='$address',
                    gender='$gender',
                    start_date='$start_date',
                    end_date='$end_date',
                    plan='$plan',
                    package='$package',
                    status='$status'";

        if(!empty($trainer)) $sql .= ", trainer='$trainer'";

        $sql .= " WHERE member_id='$id';";

        try {
            $result = mysqli_query($con, $sql);
            if ($result) {
                session_start();
                $_SESSION['add_message'] = "Member has been updated.";
                header("Location:members.php");
                exit();
            } else {
               echo "Error while updating member...";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "Error: Duplicate entry for member ID.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
        mysqli_close($con);


    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location: members.php");
        // DESTINATIONS: edit_member, functions, members
        exit();
    }
?>