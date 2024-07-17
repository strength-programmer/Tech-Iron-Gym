<?php
session_start();
if (!isset($_SESSION['logged'])) { 
    header('Location: index.php');
    exit;
}

$username = $_SESSION['user'];


include "dash_button.php";
include "functions.php";


$number_members = count_rows("members");
$number_plans = count_rows("plans");
$number_packages = count_rows("packages");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>

<br>
<!-- Welcoming -->
<h3>Welcome Back <?php echo $username?>!</h3>


<!-- Line -->
<hr class="new4">

<!-- All of the box container -->
<body>
    <section class="cards">
        <div id="card-area">
            <div class="wrapper">
                <div class="box-area">
                  
                     <div class="box">
                        <img src="active_member.jpg" alt="">
                        <div class="overlay">
  
                            <!-- Active Member Box -->
                            <h3>Active Members</h3>
                            <!-- How many members -->
                            <h3><a href="members.php"> <?php echo $number_members; ?></a></h3>
                           
                        </div>
                     </div>


                     <div class="box">
                        <img src="plan.jpg." alt="">
                        <div class="overlay">
                            <!-- Membership Plans Box -->
                        <h3>Total Membership Plans</h3>
                            <!-- How many plans -->
                            <h3><a href="plans.php"><?php echo $number_plans; ?></a></h3>
                        </div>
                     </div>
                  
                  
                  
                  
                    <div class="box">
                        <img src="package.jpg" alt="">
                        <div class="overlay">
                            <!--Total Pacages Box  -->
                            <h3>Total Packages</h3>
                            <!-- How many packages -->
                            <h3><a href="packages.php"><?php echo $number_packages; ?> </a></h3>
                        </div>
                    </div>



                </div>
            </div>
        </div>



        <!-- Line -->
        <hr class="new4">
    </section>
</body>
</html>

