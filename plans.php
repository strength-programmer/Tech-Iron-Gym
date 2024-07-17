<?php
include 'dash_button.php';

    session_start();

      if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) { 
          header('Location: index.php');
          exit;
      }
      if (isset($_SESSION['add_message'])){
        $message = $_SESSION['add_message'];
        session_destroy();
      }
      else{
        $message = "";
      }

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
                echo "<script>alert('there is an error');</script>";
            }
            
            mysqli_close($con);
        } else {
          echo "<script>alert('there is an error');</script>";
        }

    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
        header("Location:plans.php");
        exit();
    }

    // Variables for viewing plans
    include 'functions.php';
    include 'db_connect.php';  
    $search_query = "";
    $sort_by_price = $_POST['sort_price'] ?? "";

    if(isset($_POST['search_submit'])){
      $search_query = strip_tags(trim($_POST['search']));
    }

    if (!empty($search_query)){
        $sql = "SELECT * FROM plans WHERE duration LIKE '%$search_query%'";  //ORDER BY validity ASC
    } else {
        $sql = "SELECT * FROM plans";
    }

    if ($sort_by_price == "asc"){
        $sql .= " ORDER BY CAST(price as DECIMAL(10,2)) ASC";
    } elseif ($sort_by_price == "desc"){
        $sql .= " ORDER BY CAST(price as DECIMAL(10,2)) DESC";
    } else {
        $sql .= " ORDER BY validity ASC";
    }
    
    $result = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page - Plans</title>
  
  <!-- Links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="popup_form.css">
  <link rel="stylesheet" href="style_admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
 
          <h2>List of Plans</h2>
          <br>


          <!-- Add Plan Popup Form -->
        <div class="popup">
                    <button class="btn-open-popup" onclick="toggleAddPopup()">Add Plan</button>

          <div id="addPopupOverlay" class="overlay-container">
            <div class="popup-box">
              <h2>Add Plan</h2>
                
                <form class="form-container" method="post">
                  <label class="form-label" for="plan_name">Plan Name</label>
                  <input class="form-input" type="text" name="plan_name" placeholder="Eg: 10 Month/s" value="<?php echo $plan_name?>">
                  <span style="color: red;"> <?php  if ($error['plan_name']) echo $plan_name_err; ?></span>
                  <br>

                  <label class="form-label" for="validity">Validity</label>
                  <input class="form-input" type="text" name="validity" placeholder="Eg: 10" value="<?php echo $validity?>">
                  <span style="color: red;"> <?php if ($error['validity']) echo $validity_err; ?></span>
                  <br>
                  <label class="form-label" for="price"> Amount </label>
                  <input class="form-input" type="text" name="price" placeholder="Eg: 5000" value="<?php echo $price?>">
                  <span style="color: red;"> <?php if ($error['price']) echo $price_err; ?></span>
                  <br>
                    <button class="btn-submit" type="submit" name="submit" value="save">Save</button>
                    &nbsp&nbsp&nbsp&nbsp
                    <button class="btn-submit" style= "background-color: #ad0202;" type="submit" name="submit" value="cancel">Cancel</button>
                </form>

                   
              </div>
            </div>
          </div>


          <form method="post" >
            <br><br>
            <label style="font-size: 1.4em; color: black;"  for="search">Search Plan Name</label>
            <input style="  width: 80%; max-width: 200px; height: 40px; padding: 12px;  border-radius: 12px; border: 1.5px solid lightgrey;  outline: none; 
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);box-shadow: 0px 0px 20px -18px; border: 2px solid lightgrey;box-shadow: 0px 0px 20px -17px;"
       
            type="search" name="search" placeholder="Searching: <?php echo $search_query?>">
            <input style="padding: 8px 16px;  font-size: 14px;	background-color: #1e2126; color: #fff; border: none; border-radius: 8px; cursor: pointer; 
            transition: background-color 0.3s ease; " type="submit" name="search_submit" value="Search">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

            <label style="font-size: 1.4em; color: black;"  for="sort_price">Sort by Price</label>
            <select style="padding: 8px 16px;  font-size: 14px;	 color: black; border: none; border-radius: 8px; cursor: pointer; 
            transition: background-color 0.3s ease; "
            name="sort_price" >
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
                <td>Plan Name</td>
                <td>Validity</td>
                <td>Amount</td>
                <td>Action</td>
            </tr>
      </thead>
        <?php view_plan($result);
        mysqli_close($con)?>
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