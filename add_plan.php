<?php

    //variables
    $plan_name = $validity = $price = $plan_name_err = $validity_err = $price_err = "";
    $empty = "Please fill out this field";
    $error['plan_name'] = false;
    $error['validity'] = false;
    $error['price'] = false;
    //input validation
    $plan_name_pattern = "/^[1-9][0-9]{0,1} Month\/s$/";
    $validity_pattern = "/^[1-9][0-9]{0,1}$/";
    $price_pattern = "/^[1-9][0-9]{0,4}$/";
    $message = "";


    if (isset($_POST['submit']) && $_POST['submit'] == 'save'){
        $plan_name = $_POST['plan_name'];
        $validity = $_POST['validity'];
        $price = $_POST['price'];
        
        if(empty($plan_name)){
            $error['plan_name'] = true;
            $plan_name_err = $empty;
        } elseif(!preg_match($plan_name_pattern, $plan_name)){
            $error['plan_name'] = true;
            $plan_name_err = "Invalid Plan Name";
        }
        if(empty($validity)){
            $error['validity'] = true;
            $validity_err = $empty;
        } elseif(!preg_match($validity_pattern, $validity)){
            $error['validity'] = true;
            $validity_err = "Invalid Validity Number";
        }
        if(empty($price)){
            $error['price'] = true;
            $price_err = $empty;
        } elseif(!preg_match($price_pattern, $price)){
            $error['price'] = true;
            $price_err = "Invalid Price";
        }
        if(!(in_array(true, $error))){
            include 'db_connect.php';
            // escape special characters first
            $plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
            $validity = mysqli_real_escape_string($con, $_POST['validity']);
            $price = mysqli_real_escape_string($con, $_POST['price']);

            // sql command
            $sql = "INSERT INTO plans (duration, validity, price)
                    VALUES('$plan_name', '$validity', '$price')";

            try {
                $result = mysqli_query($con, $sql);
                if ($result) {
                    $message = "Plan has been added.";
                    // clear form fields
                    $plan_name = "";
                    $validity = "";
                    $price = "";
                } else {
                    $message = "Error while adding item...";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $message = "Error: Duplicate entry for plan name.";
                } else {
                    $message = "Error: " . $e->getMessage();
                }
            }
            mysqli_close($con);
        }

    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location:homepage.php");
        exit();
    }

    // Variables for viewing plans
    include 'functions.php';
    include 'db_connect.php';  
    $search_query = "";

    if(isset($_POST['search_submit'])){
        $search_query = strip_tags(trim($_POST['search']));
    }

    if (!empty($search_query)){
        $sql = "SELECT * FROM plans WHERE duration LIKE '%$search_query%' ORDER BY validity ASC";
    }else{
        $sql = "SELECT * FROM plans ORDER BY validity ASC";
    }
    
    $result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- TITLE -->
     <h1> ADD PLAN </h2>
    <form method="post">
        <label for="plan_name">Plan Name</label>
        <input type="text" name="plan_name" placeholder="Eg: 10 Month/s" value="<?php echo $plan_name?>">
        <span> <?php if ($error['plan_name']) echo $plan_name_err; ?></span>
        <br>
        <label for="validity">Validity</label>
        <input type="text" name="validity" placeholder="Eg: 10" value="<?php echo $validity?>">
        <span> <?php if ($error['validity']) echo $validity_err; ?></span>
        <br>
        <label for="price"> Amount </label>
        <input type="text" name="price" placeholder="Eg: 5000" value="<?php echo $price?>">
        <span> <?php if ($error['price']) echo $price_err; ?></span>
        <br>
        <br>

        <button type="submit" name="submit" value="save">Save</button>
        &nbsp&nbsp&nbsp&nbsp
        <button type="submit" name="submit" value="cancel">Cancel</button>
        <br>
        <span><?php echo $message?></span>

    </form>
    <br><br><br>
    <form method="post">
        <label for="search">Search Plan Name</label>
        <input type="text" name="search" placeholder="Searching: <?php echo $search_query?>">
        <input type="submit" name="search_submit" value="Search">
    </form>
    <br>
    <table width="70%">
        <tr>
            <td>#</td>
            <td>Plan Name</td>
            <td>Validity</td>
            <td>Amount</td>
            <td>Action</td>
        </tr>
        <?php view_plan($result);
        mysqli_close($con)?>
    <table>
    

    
</body>
</html>