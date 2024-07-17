<?php
  include('dash_button.php');
  // Start session

  session_start();
    if (!isset($_SESSION['logged'])) { 
    header('Location: index.php');
    exit;
    }
  // Message handling
  if (isset($_SESSION['add_message'])){
      $message = $_SESSION['add_message'];
    
  } else {
      $message = "";
  }
  session_destroy();
  session_start();
  $_SESSION['logged'] = true;

  // Variables and error messages
  $username = $password = $username_err = $password_err = "";
  $empty = "Please fill out this field";
  $error['username'] = false;
  $error['password'] = false;

  // Input validation patterns
  $username_pattern = "/^[a-zA-Z0-9_]{5,15}$/";
  $password_pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>]).{8,}$/";

  // Handle form submission
  if (isset($_POST['submit']) && $_POST['submit'] == 'save'){
      $username = $_POST['username'];
      $password = $_POST['password'];
      
      if(empty($username)){
          $error['username'] = true;
          $username_err = $empty;
      } elseif(!preg_match($username_pattern, $username)){
          $error['username'] = true;
          $username_err = "ERROR: Username must be between 5 and 15 characters long<br>and can include letters, numbers, and underscores.";
      }
      
      if(empty($password)){
          $error['password'] = true;
          $password_err = $empty;
      } elseif(!preg_match($password_pattern, $password)){
          $error['password'] = true;
          $password_err = "ERROR: Password must be at least 8 characters long and include<br>at least one uppercase letter, one number, and one special character.";
      }
      
      if(!(in_array(true, $error))){
          include 'db_connect.php';
          
          // Escape special characters
          $username = mysqli_real_escape_string($con, $_POST['username']);
          $password = mysqli_real_escape_string($con, $_POST['password']);
          
          
          // SQL command
          $sql = "INSERT INTO users (username, password) VALUES('$username', '$password')";
          
          try {
              $result = mysqli_query($con, $sql);
              if ($result) {
                  $message = "User has been added.";
                  // Clear form fields
                  $username = "";
                  $password = "";
              } else {
                  $message = "Error while adding user...";
              }
          } catch (mysqli_sql_exception $e) {
              if ($e->getCode() == 1062) {
                  $message = "Error: Duplicate entry for username.";
              } else {
                  $message = "Error: " . $e->getMessage();
              }
              echo "<script>alert('There is an error');</script>";
          }
          mysqli_close($con);
      } else {
          echo "<script>alert('There is an error');</script>";
      }
  } elseif(isset($_POST['submit']) && $_POST['submit'] == 'cancel'){
      header("Location:users.php");
      exit();
  }

  // Variables for viewing users
  include 'functions.php';
  include 'db_connect.php';  
  $search_query = "";

  if(isset($_POST['search_submit'])){
      $search_query = strip_tags(trim($_POST['search']));
  }

  $sql = "SELECT * FROM users";

  if (!empty($search_query)){
      $sql .= " WHERE username LIKE '%$search_query%'";
  }

  $result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Users</title>
    
    <!-- Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="popup_form.css">
    <link rel="stylesheet" href="style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <h2>List of Users</h2>
    <br>

    <!-- Add User Popup Form -->
    <div class="popup">
        <button class="btn-open-popup" onclick="toggleAddPopup()">Add User</button>

        <div id="addPopupOverlay" class="overlay-container">
            <div class="popup-box">
                <h2>Add User Account</h2>
                
                <form class="form-container" method="post">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-input" type="text" name="username" placeholder="Username" value="<?php echo $username?>">
                    <span style="color: red;"> <?php if ($error['username']) echo $username_err; ?></span>
                    <br>

                    <label class="form-label" for="password">Password</label>
                    <input class="form-input" type="password" name="password" placeholder="Password" value="<?php echo $password?>">
                    <span style="color: red;"> <?php if ($error['password']) echo $password_err; ?></span>
                    <br>

                    <button class="btn-submit" type="submit" name="submit" value="save">Save</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn-submit" style="background-color: #ad0202;" type="submit" name="submit" value="cancel">Cancel</button>

                </form>
            </div>
        </div>
    </div>

    <form method="post">
        <br><br>
        <label style="font-size: 1.4em; color: black;" for="search">Search Username</label>
        <input style="width: 80%; max-width: 200px; height: 40px; padding: 12px; border-radius: 12px; border: 1.5px solid lightgrey; outline: none; transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1); box-shadow: 0px 0px 20px -18px; border: 2px solid lightgrey; box-shadow: 0px 0px 20px -17px;"
            type="search" name="search" placeholder="Searching: <?php echo $search_query?>">
        <input style="padding: 8px 16px; font-size: 14px; background-color: #1e2126; color: #fff; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease;" type="submit" name="search_submit" value="Search">
    </form>

    <br>

    <table class="table table-hover text-center">
        <thead class="table-dark">
            <tr>
                <td>#</td>
                <td>Username</td>
                <td>Password</td>
                <td>Action</td>
            </tr>
        </thead>
        <?php view_user($result);
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
