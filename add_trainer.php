<?php
    session_start();
    if (!isset($_SESSION['logged'])) { 
    header('Location: index.php');
    exit;
    }

    include 'functions.php';
    // values
    $name = $email = $contact = $specialization = $rate = $submit = "";
    // variable error
    $name_err = $email_err = $contact_err = $specialization_err = $rate_err = $submit_err = "";
    // empty error message
    $empty = "Please fill out this field";
    // check if there is an error
    $error['name'] = false;
    $error['email'] = false;
    $error['contact'] = false;
    $error['specialization'] = false;
    $error['rate'] = false;

    //message error:
    $message = "";

    //input validation (regex)
    $name_pattern = "/^[A-Z][a-zñ]+( [A-Z][a-zñ]+| [A-Z].)+$/";
    $email_pattern = "/[a-zA-Z0-9._]+\@[a-zA-Z0-9.]+.(com|net|org|ph)$/";
    $contact_pattern = "/09[0-9]{9}$/";

    if(isset($_POST['submit']) && $_POST['submit'] == 'save'){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $specialization = isset($_POST['specialization'])? $_POST['specialization'] : "";
        $rate = trim($_POST['rate']);
        $submit = $_POST['submit'];

        if(empty($name)){
            $error['name'] = true;
            $name_err = $empty;
        } elseif(!preg_match($name_pattern, $name)){
            $error['name'] = true;
            $name_err = "Invalid Name";
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

        if(empty($specialization)){
            $error['specialization'] = true;
            $specialization_err = $empty;
        }

        if(empty($rate)){
            $error['rate'] = true;
            $rate_err = $empty;
        } elseif(!is_numeric($rate) || $rate <= 0){
            $error['rate'] = true;
            $rate_err = "Invalid Rate";
        }

        if(!(in_array(true, $error))){
            include 'db_connect.php';
            $name = mysqli_real_escape_string($con, $name);
            $email = mysqli_real_escape_string($con, $email);
            $contact = mysqli_real_escape_string($con, $contact);
            $specialization = mysqli_real_escape_string($con, $specialization);
            $rate = mysqli_real_escape_string($con, $rate);

            $sql = "INSERT INTO trainers (trainer_name, trainer_email, trainer_contact, specialization, rate)
                    VALUES('$name', '$email', '$contact', '$specialization', '$rate')";

            try {
                $result = mysqli_query($con, $sql);
                if ($result) {
                    $message = "Trainer has been added.";
                    $name = $email = $contact = $specialization = $rate = "";
                } else {
                    $message = "Error while adding trainer...";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $message = "Error: Duplicate entry for trainer.";
                } else {
                    $message = "Error: " . $e->getMessage();
                }
            }
            mysqli_close($con);
        }
    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel') {       
        header("Location:homepage.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Trainer</title>
</head>
<body>
    <h1>Add Trainer</h1>
    <form method="post">
        <label for="name">Trainer Name</label>&nbsp&nbsp
        <input type="text" name="name" placeholder="Name of Trainer" value = "<?php echo $name; ?>">
            <span>
                <?php if($error['name']) echo $name_err; ?>
            </span>
        <br>

        <label for="email"> Email Address </label>&nbsp&nbsp
        <input type="text" name= "email" placeholder="example@sample.com" value = "<?php echo  $email; ?>">
            <span>
                <?php if($error['email']) echo $email_err; ?>
            </span>
        <br>

        <label for="contact"> Contact Number </label>&nbsp&nbsp
        <input type="text" name="contact" placeholder="09XXXXXXXXX" value = "<?php echo $contact; ?>">
            <span>
                <?php if($error['contact']) echo $contact_err; ?>
            </span>
        <br>

        <label for="specialization">Specialization</label>&nbsp&nbsp
        <select name="specialization" >
            <?php initialize_select('Trainer Specialization', $specializations, $specialization) ?>
        </select>
            <span>
                <?php if($error['specialization']) echo $specialization_err; ?>
            </span>
        <br>

        <label for="rate">Rate</label>&nbsp&nbsp
        <input type="text" name="rate" placeholder="Rate" value = "<?php echo $rate; ?>">
            <span>
                <?php if($error['rate']) echo $rate_err; ?>
            </span>
        <br>
        <br>
        <br>
        <button type="submit" name="submit" value="save">Save</button>
        &nbsp&nbsp&nbsp&nbsp
        <button type="submit" name="submit" value="cancel">Cancel</button>
        <br>
        <span><?php echo $message?></span>
    </form>
</body>
</html>
