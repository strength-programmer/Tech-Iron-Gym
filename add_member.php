<?php
    include 'dash_button.php';
    include 'functions.php';
    // values
    $member_id = $fname = $mname = $lname = $birthday = $email = $contact = $address = 
    $gender = $start_date = $plan = $package = $trainer = $status = $submit = "";
    // variable error
    $member_id_err = $fname_err = $mname_err = $lname_err = $birthday_err = $email_err = $contact_err = 
    $address_err = $gender_err = $start_date_err = $plan_err = $package_err =  $status_err = $submit_err = "";
    // empty error message
    $empty = "Please fill out this field";
    // check if there is an error
    $error['member_id'] = false; // 
    $error['fname'] = false;
    $error['mname'] = false;
    $error['lname'] = false; //
    $error['birthday'] = false; //
    $error['email'] = false; //
    $error['contact'] = false;
    $error['address'] = false;
    $error['gender'] = false;
    $error['start_date'] = false;
    $error['plan'] = false;
    $error['package'] = false;
    $error['status'] = false;
    $message = "";
    //input validation (regex)
    $member_id_pattern = "/20(2[4-9]|[3-9][0-9])([0-9]{5})$/";
    $name_pattern = "/^[A-Z][a-zñ]*( [A-Z][a-zñ]*){0,}$/";
    $email_pattern = "/[a-zA-Z0-9._]+\@[a-zA-Z0-9.]+.(com|net|org|ph)$/";
    $contact_pattern = "/09[0-9]{9}$/";
    $address_pattern = "/^([1-9][0-9]*|[A-Z][a-z]*.?)( [1-9][0-9]*| [A-Z][a-z]*.?)*( [A-Z][a-z]*.?)*(,( ([A-Z]*[a-z]*.?)*)*)*$/";


    if(isset($_POST['submit']) && $_POST['submit'] == 'save'){
        $member_id = trim($_POST['member_id']);
        $fname = trim($_POST['fname']);
        $mname = isset($_POST['mname'])? trim($_POST['mname']) : "";
        $lname = trim($_POST['lname']);
        $birthday = $_POST['birthday'];
        $email = trim($_POST['email']);
        $contact = trim($_POST['contact']);
        $address = trim($_POST['address']);
        $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
        $start_date = $_POST['start_date'];
        $plan = isset($_POST['plan']) ? $_POST['plan'] : "";
        $package = isset($_POST['package']) ? $_POST['package'] : "";
        $trainer = isset($_POST['trainer']) ? $_POST['trainer'] : "";
        $status = isset($_POST['status']) ? $_POST['status'] : "";

// INPUT VALIDATION
        if(empty($member_id)){
            $member_id = generate_random_number(2024);
        } elseif(!preg_match($member_id_pattern, $member_id)){
            $error['member_id'] = true;
            $member_id_err = "Invalid Member Number";
        }

        if(empty($fname)){
            $error['fname'] = true;
            $fname_err = $empty;
        } elseif(!preg_match($name_pattern, $fname)){
            $error['fname'] = true;
            $fname_err = "Invalid Name";
        }

        if (!empty($mname)) {
            if (!preg_match($name_pattern, $mname)) {
                $error['mname'] = true;
                $mname_err = "Invalid Middle Name";
            }
        }

        if(empty($lname)){
            $error['lname'] = true;
            $lname_err = $empty;
        } elseif(!preg_match($name_pattern, $lname)){
            $error['lname'] = true;
            $lname_err = "Invalid Name";
        }

        if(empty($birthday)){
            $error['birthday'] = true;
            $birthday_err = $empty;
        }
        if(empty($email)){
            $error['email'] = true;
            $email_err = $empty;
        } elseif(!preg_match($email_pattern, $email)){
            $error['email'] = true;
            $email_err = "Invalid Email Address";
        }

        if(empty($contact)){
            $error['contact'] = true;
            $contact_err = $empty;
        } elseif(!preg_match($contact_pattern, $contact)){
            $error['contact'] = true;
            $contact_err = "Invalid Contact Number";
        }

        if(empty($address)){
            $error['address'] = true;
            $address_err = $empty;
        } elseif(!preg_match($address_pattern, $address)){
            $error['address'] = true;
            $address_err = "Invalid Address";
        }

        if(empty($gender)){
            $error['gender'] = true;
            $gender_err = $empty;
        } 

        if(empty($start_date)){
            $error['start_date'] = true;
            $start_date_err = $empty;
        }

        if(empty($plan)){
            $error['plan'] = true;
            $plan_err = $empty;
        } 

        if(empty($package)){
            $error['package'] = true;
            $package_err = $empty;
        }

        if(empty($status)){
            $error['status'] = true;
            $status_err = $empty;
        } 

        // If no errors, insert into database
        if (!in_array(true, $error)) {
            include 'db_connect.php';

            // Escape special characters
            $member_id = mysqli_real_escape_string($con, strip_tags($member_id));
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

            // SQL command
            if(empty($trainer)){
                $sql = "INSERT INTO members (member_id, fname, mname, lname, birth_date, member_email, member_contact, address, gender, start_date, end_date, plan, package, status)
                VALUES ('$member_id', '$fname', '$mname', '$lname', '$birthday', '$email', '$contact', '$address', '$gender', '$start_date', '$end_date', '$plan', '$package', '$status')";

            }
            else{
                $sql = "INSERT INTO members 
                    VALUES ('$member_id', '$fname', '$mname', '$lname', '$birthday', '$email', '$contact', '$address', '$gender', '$start_date', '$end_date', '$plan', '$package', '$trainer', '$status')";
            }  

            try {
                $result = mysqli_query($con, $sql);
                if ($result) {
                    session_start();
                    $_SESSION['add_message'] = "Member has been added.";
                    header("Location:members.php");
                    exit();
                    // Clear form fields
                    // $member_id = $fname = $mname = $lname = $birthday = $email = $contact = $address = 
                    // $gender = $start_date = $plan = $package = $trainer = $status = "";
                } else {
                    $birthday = date('Y-m-d', strtotime($birthday));
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $message = "<br>Error while adding member...";
                }
            } catch (mysqli_sql_exception $e) {
                $birthday = date('Y-m-d', strtotime($birthday));
                $start_date = date('Y-m-d', strtotime($start_date));
                if ($e->getCode() == 1062) {
                    $message = "<br>Error: Duplicate entry for member ID.";
                } else {
                    $message = "<br>Error: " . $e->getMessage();
                }
            }
            mysqli_close($con);
        }
    
       
    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel') {       
        header("Location:members.php");
        exit();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit.css">
    <title>Add Member</title>
    <style>
    /* Additional CSS can be placed here for specific styling */
    .input-row {
        display: flex;
        gap: 20px; /* Adjust the gap between input boxes */
        margin-bottom: 20px; /* Optional: Adjust vertical spacing */
    }

    .input-row .input-box {
        flex: 1; /* Allow the input boxes to take up equal space */
    }
</style>
</head>
<body>
    <div class="container">
        <header><b>Add New Member</b></header>

        <form class="form" method="post">
            <!-- VALIDATION & SANITATION -->
            <div class="input-box">
                <label for="member_id">Member Number</label>
                <input type="text" name="member_id" placeholder="eg: 202400001" value = "<?php echo $member_id; ?>">
                <span>
                        <?php if($error['member_id']) echo $member_id_err; ?>
                    </span>
                <i> Leave this blank if you want an auto-generated member_id</i>
            </div>
           
        <!-- NAMES -->
            <div class="input-row">
                <div class="input-box">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" placeholder="eg: Jose Protacio" value = "<?php echo $fname; ?>">
                    <span>
                        <?php if($error['fname']) echo $fname_err; ?>
                    </span>
                </div>

                <div class="input-box">
                    <label for="mname">Middle Name</label>
                    <input type="text" name="mname" placeholder="eg: Alonso Realonda" value = "<?php echo $mname; ?>">
                    <span>
                        <?php if($error['mname']) echo $mname_err; ?>
                    </span>
                </div>

                <div class="input-box">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" placeholder="eg: Rizal" value = "<?php echo $lname; ?>">
                    <span>
                        <?php if($error['lname']) echo $lname_err; ?>
                    </span>
                </div>
            </div>
            <!-- 2nd row -->

            <div class="input-row">
                <div class="input-box">
                    <label for="birthday"> Date of Birth </label>
                    <input type="date" name="birthday" value = "<?php echo $birthday; ?>">
                    <span>
                        <?php if($error['birthday']) echo $birthday_err; ?>
                    </span>
                </div>

                <div class="input-box">
                    <label for="email"> Email Address </label>
                    <input type="text" name= "email" placeholder="example@sample.com" value = "<?php echo  $email; ?>">
                    <span>
                        <?php if($error['email']) echo $email_err; ?>
                    </span>
                </div>
                
                <div class="input-box">
                <label for="contact"> Contact Number </label>
                    <input type="text" name="contact" placeholder="09XXXXXXXXX" value = "<?php echo $contact; ?>">
                    <span>
                        <?php if($error['contact']) echo $contact_err; ?>
                    </span>
                </div>
            </div>
            <!-- 3rd row -->
            <div class="input-row">      
                <div class="input-box">
                <label for="address">Address</label>
                    <textarea name="address" placeholder="Address of Client"><?php echo $address; ?></textarea>
                    <span>
                        <?php if($error['address']) echo $address_err; ?>
                    </span>
                </div>
          
                <div class="input-box">
                    <label for="gender">Gender</label>
                    <select name="gender">
                    <?php initialize_select('Gender', $genders, $gender) ?>
                    </select>
                    <span>
                        <?php if($error['gender']) echo $gender_err; ?>
                    </span>
                </div>
            </div>
            <!-- 4th row -->
            <div class="input-row">      
                <div class="input-box">
                <label for="start_date">Start of Membership</label>
                    <input type="date" name="start_date" value = "<?php echo $start_date; ?>" >
                    <span>
                        <?php if($error['start_date']) echo $start_date_err; ?>
                    </span>
                </div>
                
                <div class="input-box">
                    <label for="plan">Plan</label>
                    <select name="plan">
                        <?php 
                        //This will get the list of plans and initialize the plan dropdown button with
                            include 'db_connect.php';
                            $sql = "SELECT duration from plans ORDER BY CAST(SUBSTRING_INDEX(duration, ' ', 1) AS UNSIGNED) ASC"; // MAKE THEM IN ASCENDING ORDER
                            $result = mysqli_query($con, $sql);
                            $plan_array = [];
                            while($row = mysqli_fetch_array($result)){
                                $plan_array[] = $row['duration'];
                            }
                            initialize_select('Plan', $plan_array, $plan)
                        ?>
                    </select>
                    <span>
                        <?php if($error['plan']) echo $plan_err; ?>
                    </span>
                </div>
            </div>
            <!-- 5th row -->
            <div class="input-row">
                <div class="input-box">
                <label for="package">Package</label>
                    <select name="package">
                        <?php 
                        //This will get the list of plans and initialize the plan dropdown button with
                            include 'db_connect.php';
                            $sql = "SELECT package_name from packages ORDER BY package_id"; // MAKE THEM IN ASCENDING ORDER
                            $result = mysqli_query($con, $sql);
                            $package_array = [];
                            while($row = mysqli_fetch_array($result)){
                                $package_array[] = $row['package_name'];
                            }
                            initialize_select('Package', $package_array, $package)
                        ?>
                    </select>
                    <span>
                        <?php if($error['package']) echo $package_err; ?>
                    </span>
                </div>
                
                        
                <div class="input-box">
                <label for="trainer">Trainer</label>
                    <select name="trainer">
                        <?php 
                        //This will get the list of trainera and initialize the plan dropdown button with
                            include 'db_connect.php';
                            $sql = "SELECT trainer_name from trainers ORDER BY trainer_name ASC"; // MAKE THEM IN ASCENDING ORDER
                            $result = mysqli_query($con, $sql);
                            $trainer_array = [];
                            while($row = mysqli_fetch_array($result)){
                                $trainer_array[] = $row['trainer_name'];
                            }
                            initialize_select('Trainer', $trainer_array, $trainer)
                        ?>
                    </select>
                </div>
                
                <div class="input-box">
                    <label for="status">Membership Status</label>
                    <select name="status">
                        <?php initialize_select('Status', $status_array, $status) ?>
                    </select>
                    <span>
                        <?php if($error['status']) echo $status_err; ?>
                    </span>
                </div>
            </div>



            <!-- submit field -->
            <div class="form-actions">
                <button type="submit" name="submit" value="save">Save</button>
                <button type="submit" name="submit" value="cancel">Cancel</button>
            </div>
            <span><?php echo $message ?></span>

        </form>
    </div>
</body>
</html>