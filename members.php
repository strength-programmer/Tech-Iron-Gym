<?php
include('dash_button.php');


//message error:
  session_start();
  if (!isset($_SESSION['logged'])) { 
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


    // Variables for viewing members
    include 'functions.php';
    include 'db_connect.php';
    $search_query = "";

    if (isset($_POST['search_submit'])){
        $search_query = strip_tags(trim($_POST['search']));
    }
    
    // so that IT CAN handle punctuations etc
    try{
        if (!empty($search_query)){
            $sql = "SELECT member_id, fname, mname, lname, birth_date, member_email,
                    member_contact, address, gender FROM members
                    WHERE CONCAT(fname, ' ', mname, ' ', lname) LIKE '%$search_query%'
                    or CONCAT(fname, ' ', lname) LIKE '%$search_query%'
                    or CONCAT(mname, ' ', lname) LIKE '%$search_query%'";
        } else {
            $sql = "SELECT member_id, fname, mname, lname, birth_date, member_email,
                    member_contact, address, gender FROM members";
        }
        $result = mysqli_query($con, $sql);
    }
    catch(Exception $e){ 
        $sql = "SELECT member_id, fname, mname, lname, birth_date, member_email,
                member_contact, address, gender FROM members where member_id=1";
        $result = mysqli_query($con, $sql);
    }
   

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Members</title>

  
  <!-- Links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="popup_form.css">
  <link rel="stylesheet" href="style_admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 
</head>
<body>
 
          <h2>List of Members</h2>
          <br>

          <!-- Add Trainer Popup Form -->
          <div class="popup">
            <button class="btn-open-popup" onclick="redirectToAddMember()">Add New Member</button>
          </div>



          <form method="post" id="search_form">
            <br><br>
            <label style="font-size: 1.4em; color: black;"  for="search">Search Member Name</label>
            <input style="  width: 80%; max-width: 200px; height: 40px; padding: 12px;  border-radius: 12px; border: 1.5px solid lightgrey;  outline: none; 
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);box-shadow: 0px 0px 20px -18px; border: 2px solid lightgrey;box-shadow: 0px 0px 20px -17px;"
       
            type="search" name="search" placeholder="Searching: <?php echo $search_query?>">
            <input style="padding: 8px 16px;  font-size: 14px;	background-color: #1e2126; color: #fff; border: none; border-radius: 8px; cursor: pointer; 
            transition: background-color 0.3s ease; " type="submit" name="search_submit" value="Search">


            
        </form>

          <br>

    <table class="table table-hover text-center">
          <thead class="table-dark">
            <tr>
              <td>#</td>
              <td>Member ID</td>
              <td>First Name</td>
              <td>Middle Name</td>
              <td>Last Name</td> 
              <td>Birthday</td>
              <td>Email</td>
              <td>Contact Number</td>
              <td>Residential Address</td>
              <td>Gender</td>
              <td>Action</td>
            </tr>
          </thead>
          <?php   view_member($result);
                  mysqli_close($con); 
               
          ?>
          
          </table>
          <?php  
                  echo $message;
          ?>
    


              <!-- To redirect to add members -->
              <script>
                function redirectToAddMember() {
                  window.location.href = 'add_member.php';
                }
              </script>

  </body>
  </html>

