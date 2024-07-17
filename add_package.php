<?php
      session_start();
      if (!isset($_SESSION['logged'])) { 
        header('Location: index.php');
        exit;
        }
// Variables
$package_name = $package_description = $package_price = "";
$package_name_err = $package_description_err = $package_price_err = "";
$empty = "Please fill out this field";
$error = [
    'package_name' => false,
    'package_description' => false,
    'package_price' => false,
];
$message = "";

// Input validation
$package_name_pattern = "/^[a-zA-Z0-9]+( [a-zA-Z0-9]+)*$/";
$package_price_pattern = "/^[1-9][0-9]{0,4}$/";

if (isset($_POST['submit']) && $_POST['submit'] == 'save') {
    $package_name = $_POST['package_name'];
    $package_description = $_POST['package_description'];
    $package_price = $_POST['package_price'];
    
    // Validate package name
    if (empty($package_name)) {
        $error['package_name'] = true;
        $package_name_err = $empty;
    } elseif (!preg_match($package_name_pattern, $package_name)) {
        $error['package_name'] = true;
        $package_name_err = "Invalid Package Name";
    }

    // Validate package description
    if (empty($package_description)) {
        $error['package_description'] = true;
        $package_description_err = $empty;
    }

    // Validate package price
    if (empty($package_price)) {
        $error['package_price'] = true;
        $package_price_err = $empty;
    } elseif (!preg_match($package_price_pattern, $package_price)) {
        $error['package_price'] = true;
        $package_price_err = "Invalid Price";
    }

    // If no errors, insert into database
    if (!in_array(true, $error)) {
        include 'db_connect.php';

        // Escape special characters
        $package_name = mysqli_real_escape_string($con, strip_tags($_POST['package_name']));
        $package_description = mysqli_real_escape_string($con, strip_tags($_POST['package_description']));
        $package_price = mysqli_real_escape_string($con, $_POST['package_price']);

        // SQL command
        $sql = "INSERT INTO packages (package_name, description, price)
                VALUES ('$package_name', '$package_description', '$package_price')";

        try {
            $result = mysqli_query($con, $sql);
            if ($result) {
                $message = "Package has been added.";
                // Clear form fields
                $package_name = "";
                $package_description = "";
                $package_price = "";
            } else {
                $message = "Error while adding package...";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $message = "Error: Duplicate entry for package name.";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }
        mysqli_close($con);
    }

} elseif (isset($_POST['submit']) && $_POST['submit'] == 'cancel') {
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package</title>
</head>
<body>
    <!-- TITLE -->
    <h1>ADD PACKAGE</h1>
    <form method="post">
        <label for="package_name">Package Name</label>
        <input type="text" name="package_name" placeholder="Eg: Premium Package" value="<?php echo $package_name ?>">
        <span><?php if ($error['package_name']) echo $package_name_err; ?></span>
        <br>
        
        <label for="package_description">Package Description</label>
        <br>
        <textarea name="package_description" placeholder="Eg: This package includes..."><?php echo $package_description ?></textarea>
        <span><?php if ($error['package_description']) echo $package_description_err; ?></span>
        <br>
        
        <label for="package_price">Amount</label>
        <input type="text" name="package_price" placeholder="Eg: 5000" value="<?php echo $package_price ?>">
        <span><?php if ($error['package_price']) echo $package_price_err; ?></span>
        <br>
        
        <button type="submit" name="submit" value="save">Save</button>
        &nbsp&nbsp&nbsp&nbsp
        <button type="submit" name="submit" value="cancel">Cancel</button>
        <br>
        <span><?php echo $message ?></span>
    </form>
</body>
</html>
