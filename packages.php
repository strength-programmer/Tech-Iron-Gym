<?php
include('dash_button.php');


    session_start();
    if (!isset($_SESSION['logged'])) { 
      header('Location: index.php');
      exit;
  }
    if (isset($_SESSION['add_message'])){
      $message = $_SESSION['add_message'];
      session_destroy();
    } else {
      $message = "";
    }
    // Variables for package input and errors
    $package_name = $description = $price = $package_name_err = $description_err = $price_err = "";
    $empty = "Please fill out this field";
    $error['package_name'] = false;
    $error['description'] = false;
    $error['price'] = false;

    // Input validation patterns
    $package_name_pattern = "/^[A-Za-z0-9 ]+$/"; // Adjust as necessary
    $price_pattern = "/^[1-9][0-9]{0,4}$/";

    // Handle form submission
    if (isset($_POST['submit']) && $_POST['submit'] == 'save') {
        $package_name = $_POST['package_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Validate inputs
        if (empty($package_name)) {
            $error['package_name'] = true;
            $package_name_err = $empty;
        } elseif (!preg_match($package_name_pattern, $package_name)) {
            $error['package_name'] = true;
            $package_name_err = "Invalid Package Name";
        }
        if (empty($description)) {
            $error['description'] = true;
            $description_err = $empty;
        }
        if (empty($price)) {
            $error['price'] = true;
            $price_err = $empty;
        } elseif (!preg_match($price_pattern, $price)) {
            $error['price'] = true;
            $price_err = "Invalid Price";
        }

        // If no errors, insert into database
        if (!(in_array(true, $error))) {
            include 'db_connect.php';
            $package_name = mysqli_real_escape_string($con, $_POST['package_name']);
            $description = mysqli_real_escape_string($con, $_POST['description']);
            $price = mysqli_real_escape_string($con, $_POST['price']);

            $sql = "INSERT INTO packages (package_name, description, price)
                    VALUES ('$package_name', '$description', '$price')";

            try {
                $result = mysqli_query($con, $sql);
                if ($result) {
                    $message = "Package has been added.";
                    $package_name = "";
                    $description = "";
                    $price = "";
                } else {
                    $message = "Error while adding package...";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $message = "Error: Duplicate entry for package name.";
                } else {
                    $message = "Error: " . $e->getMessage();
                }
                echo "<script>alert('there is an error');</script>";
            }
            mysqli_close($con);
        }else {
          echo "<script>alert('there is an error');</script>";
        }
    } elseif (isset($_POST['submit']) && $_POST['submit'] == 'cancel') {
        header("Location: packages.php");
        exit();
    }

    // Variables for viewing packages
    include 'functions.php';
    include 'db_connect.php';  
    $search_query = "";
    $sort_by_price = $_POST['sort_price'] ?? "";

    if (isset($_POST['search_submit'])) {
        $search_query = strip_tags(trim($_POST['search']));
    }

    if (!empty($search_query)) {
        $sql = "SELECT * FROM packages WHERE package_name LIKE '%$search_query%'";
    } else {
        $sql = "SELECT * FROM packages";
    }

    if ($sort_by_price == "asc") {
        $sql .= " ORDER BY CAST(price AS DECIMAL(10,2)) ASC";
    } elseif ($sort_by_price == "desc") {
        $sql .= " ORDER BY CAST(price AS DECIMAL(10,2)) DESC";
    } else {
        $sql .= " ORDER BY package_id ASC";
    }

    $result = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page - Packages</title>
  
  <!-- Links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="popup_form.css">
  <link rel="stylesheet" href="style_admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
  <h2>List of Packages</h2>
  <br>

  <!-- Add Package Popup Form -->
  <div class="popup">
    <button class="btn-open-popup" onclick="toggleAddPopup()">Add Package</button>

    <div id="addPopupOverlay" class="overlay-container">
      <div class="popup-box">
        <h2>Add Package</h2>

        <form class="form-container" method="post">
          <label class="form-label" for="package_name">Package Name</label>
          <input class="form-input" type="text" name="package_name" placeholder="Eg: Basic Package" value="<?php echo $package_name ?>">
          <span> <?php if ($error['package_name']) echo $package_name_err; ?></span>

          <label class="form-label" for="description">Description</label>
          <textarea class="form-input" name="description" placeholder="Package Description"><?php echo $description ?></textarea>
          <span> <?php if ($error['description']) echo $description_err; ?></span>

          <label class="form-label" for="price">Amount</label>
          <input class="form-input" type="text" name="price" placeholder="Eg: 5000" value="<?php echo $price ?>">
          <span> <?php if ($error['price']) echo $price_err; ?></span>

          <button class="btn-submit" type="submit" name="submit" value="save">Save</button>
          &nbsp&nbsp&nbsp&nbsp
          <button class="btn-submit" type="submit" style="background-color: #ad0202;" name="submit_c" value="cancel">Cancel</button>

        </form>
      </div>
    </div>
  </div>

  

  <form method="post">
    <br><br>
    <label style="font-size: 1.4em; color: black;" for="search">Search Package Name</label>
    <input style="width: 80%; max-width: 200px; height: 40px; padding: 12px; border-radius: 12px; border: 1.5px solid lightgrey; outline: none; transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1); box-shadow: 0px 0px 20px -18px; border: 2px solid lightgrey; box-shadow: 0px 0px 20px -17px;"
    type="search" name="search" placeholder="Searching: <?php echo $search_query ?>">
    <input style="padding: 8px 16px; font-size: 14px; background-color: #1e2126; color: #fff; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease;" type="submit" name="search_submit" value="Search">
    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

    <label style="font-size: 1.4em; color: black;" for="sort_price">Sort by Price</label>
    <select style="padding: 8px 16px; font-size: 14px; color: black; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease;" name="sort_price">
        <option value=""> -- Select -- </option>
        <option value="asc" <?php if ($sort_by_price == 'asc') echo 'selected'; ?>>Low to High</option>
        <option value="desc" <?php if ($sort_by_price == 'desc') echo 'selected'; ?>>High to Low</option>
    </select>
  </form>

  <br>

  <table class="table table-hover text-center">
    <thead class="table-dark">
      <tr>
          <td>#</td>
          <td>Package Name</td>
          <td>Description</td>
          <td>Amount</td>
          <td>Action</td>
      </tr>
    </thead>
    <?php view_package($result); // Implement this function to display packages
    mysqli_close($con) ?>
  </table>
  <?php echo $message; ?>

  <!-- JavaScript for popup forms -->
  <script>
    function toggleAddPopup() {
      const addOverlay = document.getElementById('addPopupOverlay');
      addOverlay.classList.toggle('show');
    }

    function toggleEditPopup() {
      const editOverlay = document.getElementById('editPopupOverlay');
      editOverlay.classList.toggle('show');
    }
  </script>

</body>
</html>
