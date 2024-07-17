<?php
session_start();
    include('dash_button.php');

    if (!isset($_SESSION['logged'])) { 
        header('Location: index.php');
        exit;
    }
    if (isset($_SESSION['add_message'])) {
        $message = $_SESSION['add_message'];
       

    } else {
        $message = "";
    }
    session_destroy();
    session_start();
    $_SESSION['logged'] = true;
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
                echo "<script>alert('there is an error');</script>";
            }
            mysqli_close($con);
        }
        else{
            echo "<script>alert('there is an error');</script>";
        }
    } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel') {       
        header("Location:trainers.php");
        exit();
    }

        // Variables for viewing trainers
        include 'functions.php';
        include 'db_connect.php';  
        $search_query = "";
        $sort_by_rate = $_POST['sort_rate'] ?? '';

        if(isset($_POST['search_submit'])){
            $search_query = strip_tags(trim($_POST['search']));
        }

        if (!empty($search_query)){
            $sql = "SELECT * FROM trainers WHERE trainer_name LIKE '%$search_query%'";
        } else {
            $sql = "SELECT * FROM trainers";
        }

        if ($sort_by_rate == 'asc') {
            $sql .= " ORDER BY CAST(rate as DECIMAL(10,2)) ASC";
        } elseif ($sort_by_rate == 'desc') {
            $sql .= " ORDER BY CAST(rate as DECIMAL(10,2)) DESC";
        } else {
            $sql .= " ORDER BY trainer_id ASC";
        }
        
        $result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page - Trainers</title>

  
  <!-- Links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="popup_form.css">
  <link rel="stylesheet" href="style_admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 
</head>
<body>
 
          <h2>List of Trainers</h2>
          <br>


          <!-- Add Trainer Popup Form -->
        <div class="popup">
                    <button class="btn-open-popup" onclick="toggleAddPopup()">Add Trainer</button>

          <div id="addPopupOverlay" class="overlay-container">
            <div class="popup-box">
              <h2>Add Trainer</h2>
                
              <form class="form-container" method="post">
                <label class="form-label" for="name">Trainer Name</label>
                <input class="form-input" type="text" name="name" placeholder="Name of Trainer" value = "<?php echo $name; ?>">
                    <span style="color: red;">
                        <?php if($error['name']) echo $name_err; ?>
                    </span>
              

                <label class="form-label" for="email"> Email Address </label>
                <input class="form-input" type="text" name= "email" placeholder="example@sample.com" value = "<?php echo  $email; ?>">
                    <span style="color: red;">
                        <?php if($error['email']) echo $email_err; ?>
                    </span>
            

                <label class="form-label" for="contact"> Contact Number </label>
                <input class="form-input" type="text" name="contact" placeholder="09XXXXXXXXX" value = "<?php echo $contact; ?>">
                    <span style="color: red;">
                        <?php if($error['contact']) echo $contact_err; ?>
                    </span>
           

                <label class="form-label" for="specialization">Specialization</label>
                <select class="form-input" name="specialization" >
                    <?php initialize_select('Trainer Specialization', $specializations, $specialization) ?>
                </select>
                    <span style="color: red;">
                        <?php if($error['specialization']) echo $specialization_err; ?>
                    </span>
          

                <label class="form-label" for="rate">Rate</label>
                <input class="form-input" type="text" name="rate" placeholder="Rate" value = "<?php echo $rate; ?>">
                    <span style="color: red;">
                        <?php if($error['rate']) echo $rate_err; ?>
                    </span>


                <button class="btn-submit" type="submit" name="submit" value="save">Save</button>
               &nbsp
                <button class="btn-submit" style= "background-color: #ad0202;" type="submit" name="submit" value="cancel">Cancel</button>

            </form>

                   
              </div>
            </div>
          </div>



          <form method="post" id="search_form">
            <br><br>
            <label style="font-size: 1.4em; color: black;"  for="search">Search Trainer Name</label>
            <input style="  width: 80%; max-width: 200px; height: 40px; padding: 12px;  border-radius: 12px; border: 1.5px solid lightgrey;  outline: none; 
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);box-shadow: 0px 0px 20px -18px; border: 2px solid lightgrey;box-shadow: 0px 0px 20px -17px;"
       
            type="search" name="search" placeholder="Searching: <?php echo $search_query?>">
            <input style="padding: 8px 16px;  font-size: 14px;	background-color: #1e2126; color: #fff; border: none; border-radius: 8px; cursor: pointer; 
            transition: background-color 0.3s ease; " type="submit" name="search_submit" value="Search">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

            <label style="font-size: 1.4em; color: black;"  for="sort_rate">Sort by Rate</label>
            <select style="padding: 8px 16px;  font-size: 14px;	 color: black; border: none; border-radius: 8px; cursor: pointer; 
            transition: background-color 0.3s ease; "
            name="sort_rate" onchange="document.getElementById('search_form').submit();">
                <option value=""> -- Select -- </option>
                <option value="asc" <?php if ($sort_by_rate == 'asc') echo 'selected'; ?>>Low to High</option>
                <option value="desc" <?php if ($sort_by_rate == 'desc') echo 'selected'; ?>>High to Low</option>
            </select>
        </form>

          <br>

    <table class="table table-hover text-center">
          <thead class="table-dark">
            <tr>
              <td>#</td>
              <td>Name</td>
              <td>Email</td>
              <td>Contact Number</td>
              <td>Area of Specialization</td>
              <td>Rate</td>
              <td>Action</td>
            </tr>
      </thead>
        <?php view_trainer($result);
        mysqli_close($con)?>
      </table>
      <?php echo $message; ?>
    


              <!-- JavaScript for popup forms -->
              <script>
                function toggleAddPopup() {
                  const addOverlay = document.getElementById('addPopupOverlay');
                  addOverlay.classList.toggle('show');
                }

  
              </script>

  </body>
  </html>


