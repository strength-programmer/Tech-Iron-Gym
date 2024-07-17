<?php
    function initialize_select($name, $arr, $selected_value)
    {
        echo "<option disabled selected value> -- Select a " . ucwords($name) . " -- </option>";
        foreach ($arr as $value)
        {
            $selected = ($value == $selected_value) ? "selected" : "";
            echo "<option value='" . $value . "' $selected>". $value . "</option>";
        }
    }
    
    //VARIABLES:
    $genders = array("Male", "Female", "Others");
    $status_array = array("Active", "Expired", "Pending", "Cancelled");
    $specializations = array("Arm Wrestling", "Bodybuilding", "Calisthenics", "Combat Sports", "Crossfit", 
                            "Dance Fitness", "Endurance Training", "General Strength Training", 
                            "Overall Fitness", "Powerlifting", "Strongman", "Weight Loss", "Yoga");

    function get_plan_duration($plan){
        return strtok($plan, " ");
    }
    //GENERATE RAN NUMBERS 20XXXXXXX
    function generate_random_number($year) {
        // Fixed part
        $prefix = $year;
    
        // Generate the last five digits (0-9)
        $lastNineDigits = '';
        for ($i = 0; $i < 5; $i++) {
            $lastNineDigits .= rand(0, 9);
        }
    
        // Concatenate all parts
        $randomNumber = $prefix . $lastNineDigits;
    
        return $randomNumber;
    }


//DATABASE FUNCTION

    //COUNT
        function count_rows($table_name){ // count how many entries in database 
            include "db_connect.php";
            $sql = "SELECT COUNT(*) as count FROM $table_name";
            try{
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                return $row['count'];
            }
            catch (mysqli_sql_exception $e){
                return "Error: " . $e->getMessage();
            }
        }
    //VIEW
        function view_plan($result){
            $i = 1;
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['duration'] . "</td>";
                    echo "<td>" . $row['validity'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>";
          
        
                    echo "<a class='link-dark' style='cursor:pointer' href='edit_plan.php?id=" . $row['plan_id'] . "'><i class='fa-solid fa-pen-to-square me-2'></i></a> | &nbsp&nbsp";
                    echo "<a class='link-dark' href='delete_item.php?id=" . $row['plan_id'] . "&filename=plans.php&table=plans&table_id=plan_id' 
                    onclick='return confirm(\"Are you sure you want to delete this plan?\")'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                echo "</tr>";
                $i++;
            }
        }
        function view_user($result){
            $i = 1;
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['password'] . "</td>";
                    echo "<td>";
          
        
                    echo "<a class='link-dark' style='cursor:pointer' href='edit_user.php?id=" . $row['username'] . "'><i class='fa-solid fa-pen-to-square me-2'></i></a> | &nbsp&nbsp";
                    echo "<a class='link-dark' href='delete_item.php?id=" . $row['username'] . "&filename=users.php&table=users&table_id=username' 
                    onclick='return confirm(\"Are you sure you want to delete this plan?\")'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                echo "</tr>";
                $i++;
            }
        }

        function view_package($result){
            $i = 1;
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['package_name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>";
                    echo "<a class='link-dark' style='cursor:pointer' href='edit_package.php?id=" . $row['package_id'] . "'><i class='fa-solid fa-pen-to-square me-2'></i></a> | &nbsp&nbsp";
                    echo "<a class='link-dark' href='delete_item.php?id=" . $row['package_id'] . "&filename=packages.php&table=packages&table_id=package_id' 
                    onclick='return confirm(\"Are you sure you want to delete this package?\")'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                echo "</tr>";
                $i++;
            }
        }
        function view_trainer($result){
            $i = 1;
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['trainer_name'] . "</td>";
                    echo "<td>" . $row['trainer_email'] . "</td>";
                    echo "<td>" . $row['trainer_contact'] . "</td>";
                    echo "<td>" . $row['specialization'] . "</td>";
                    echo "<td>" . "₱" . $row['rate'] . "</td>";
                    echo "<td>";
                    echo "<a class='link-dark' href='edit_trainer.php?id=" . $row['trainer_id'] . "'><i class='fa-solid fa-pen-to-square me-2'></i></a> | &nbsp&nbsp";
                    echo "<a class='link-dark'  href='delete_item.php?id=" . $row['trainer_id'] . "&filename=trainers.php&table=trainers&table_id=trainer_id' 
                    onclick='return confirm(\"Are you sure you want to delete this trainer?\")'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                echo "</tr>";
                $i++;
            }
        }

        function view_member($result){
            $i = 1;
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['member_id'] . "</td>";
                    echo "<td>" . $row['fname'] . "</td>";
                    echo "<td>" . $row['mname'] . "</td>";
                    echo "<td>" . $row['lname'] . "</td>";
                    echo "<td>" . $row['birth_date'] . "</td>";
                    echo "<td>" . $row['member_email'] . "</td>";
                    echo "<td>" . $row['member_contact'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>";
          
                    echo "<a href='member_details.php?id=" . $row['member_id'] . "&version=1'><i class='fa-solid fa-eye' style='color: #1e2126;'></i></a> &nbsp | &nbsp";
                    echo "<a class='link-dark' style='cursor:pointer' href='edit_member.php?id=" . $row['member_id'] . "'><i class='fa-solid fa-pen-to-square me-2'></i></a> &nbsp| &nbsp";
                    echo "<a class='link-dark' href='delete_item.php?id=" . $row['member_id'] . "&filename=members.php&table=members&table_id=member_id' 
                    onclick='return confirm(\"Are you sure you want to delete this member?\")'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                echo "</tr>";
                $i++;
            }
        }


        function view_membership($result){
            $i = 1;
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['member_id'] . "</td>";
                    echo "<td>" . $row['fullname'] . "</td>";
      
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['plan'] . "</td>";
                    echo "<td>" . $row['package'] . "</td>";
                    echo "<td>" . (empty($row['trainer'])? "None" : $row['trainer']) . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
          
                    echo "<a href='member_details.php?id=" . $row['member_id'] . "&version=2'><i class='fa-solid fa-eye' style='color: #1e2126;'></i></a> &nbsp | &nbsp";
                    //echo "<a class='link-dark' style='cursor:pointer' href='edit_members.php?id=" . $row['member_id'] . "'>Edit<i class='fa-solid fa-pen-to-square me-2'></i></a> | &nbsp&nbsp";
                    echo "<a class='link-dark' href='delete_item.php?id=" . $row['member_id'] . "&filename=members.php&table=members&table_id=member_id' 
                    onclick='return confirm(\"Are you sure you want to delete this member?\")'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                echo "</tr>";
                $i++;
            }
        }




    //UPDATE ITEM
    function update_plan($data) { ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" href="edit.css">
          <title></title>
        </head>
        <body>
 
        <div class="container">
              <h1>Edit Plan</h1><br>
              <form method="post" class="form" >
                  <div class="input-box">
                      <label for="plan_name">Plan Name</label>
                      <input type="text" name="plan_name" placeholder="Eg: 10 Month/s" value="<?php echo $data['duration']?>">
                  </div>
      
                  <div class="input-box">
                      <label for="validity">Validity</label>
                      <input type="text" name="validity" placeholder="Eg: 10" value="<?php echo $data['validity']?>">
                  </div>
                  
                  <div class="input-box">
                      <label for="price">Amount</label>
                      <input type="text" name="price" placeholder="Eg: 5000" value="<?php echo $data['price']?>">
                  </div>
      
                  <input type="hidden" name="plan_id" value="<?php echo $data['plan_id']; ?>">
      
                  <div class="form-actions">
                      <button type="submit" name="submit" value="update">Update</button>
                      <button type="submit" name="submit" value="cancel">Go Back</button>
                  </div>
              </form>
          </div>
          </body>
          </html>
      <?php }
            function update_package($data) { ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="edit.css">
                <title></title>
                </head>
                <body>
          
                <div class="container">
                <h1>Edit Package</h1><br>
                    <form method="post">
                        <div class="input-box">
                            <label for="package_name">Package Name</label>
                            <input type="text" name="package_name" placeholder="Eg: Premium Package" value="<?php echo $data['package_name']?>">
                        </div>

                        <div class="input-box">
                            <label for="description">Package Description</label>
        
                            <textarea name="description"><?php echo $data['description']?></textarea>
                        </div>
        
                        <div class="input-box">
                            <label for="package_price">Amount</label>
                            <input type="text" name="price" placeholder="Eg: 5000" value="<?php echo $data['price']?>">
                        </div>
                       
                        <!-- hidden is to get the id of the row so that it can be updated using where clause -->
                        <input type="hidden" name="package_id" value="<?php echo $data['package_id']; ?>">
                        
                        <div class="form-actions">
                            <button type="submit" name="submit" value="update">Update Package</button>
                            <button type="submit" name="submit" value="cancel">Go Back</button>
                        </div>
        
                    </form>
                </div>
                </body>
                </html>
    
    
            <?php 
                }
        function update_trainer($data) {?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="edit.css">
        <title></title>
        </head>
        <body>
            
            <div class="container">
            <h1>Edit Trainer</h1><br>
                <form method="post">
                    <div class="input-box">
                        <label for="trainer_name">Trainer Name</label>
                        <input type="text" name="trainer_name" placeholder="Name of Trainer" value = "<?php echo $data['trainer_name']; ?>">
                    </div>
        
                    <div class="input-box">
                        <label for="trainer_email"> Email Address </label>
                        <input type="text" name= "trainer_email" placeholder="example@sample.com" value = "<?php echo  $data['trainer_email']; ?>">
                    </div>
                 
                    <div class="input-box">
                        <label for="trainer_contact"> Contact Number </label>
                        <input type="text" name="trainer_contact" placeholder="09XXXXXXXXX" value = "<?php echo $data['trainer_contact']; ?>">

                    </div>

                    <div class="input-box">
                        <label for="specialization">Specialization</label>
                        <select name="specialization" >
                            <?php global $specializations; initialize_select('Trainer Specialization', $specializations, $data['specialization']) ?>
                        </select>
                    </div>

                     <div class="input-box">

                        <label for="rate">Rate</label>
                        <input type="text" name="rate" placeholder="Rate" value = "<?php echo $data['rate'];; ?>">
                    </div>
                    
                    
                    <input type="hidden" name="trainer_id" value="<?php echo $data['trainer_id'];?>">
                    <div class="form-actions">
                        <button type="submit" name="submit" value="update">Update Trainer</button>
                        
                        <button type="submit" name="submit" value="cancel">Go Back</button>
                    </div>

                </form>
            </div>
        </body>
        </html>

            <?php 
                }
            function update_members($data) {
                $birth_date = date('Y-m-d', strtotime($data['birth_date']));
                $start_date = date('Y-m-d', strtotime($data['start_date']));
                ?>

                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="edit.css">
                <title></title>
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
                
                        <h1>Edit Member Details</h1><br>
                        <form class="form" method="post">
                            <!-- VALIDATION & SANITATION -->
                        
                        <!-- NAMES -->
                            <div class="input-row">
                                <div class="input-box">
                                    <label for="fname">First Name</label>
                                    <input type="text" name="fname" placeholder="eg: Jose Protacio" value = "<?php echo $data['fname']; ?>">
                                </div>

                                <div class="input-box">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" name="mname" placeholder="eg: Alonso Realonda" value = "<?php echo $data['mname']; ?>">
                                </div>

                                <div class="input-box">
                                    <label for="lname">Last Name</label>
                                    <input type="text" name="lname" placeholder="eg: Rizal" value = "<?php echo $data['lname']; ?>">
                                </div>
                            </div>
                            <!-- 2nd row -->

                            <div class="input-row">
                                <div class="input-box">
                                    <label for="birthday"> Date of Birth </label>
                                    <input type="date" name="birthday" value = "<?php echo $birth_date; ?>">
                                </div>

                                <div class="input-box">
                                    <label for="email"> Email Address </label>
                                    <input type="text" name= "email" placeholder="example@sample.com" value = "<?php echo  $data['member_email']; ?>">
                                </div>
                                
                                <div class="input-box">
                                <label for="contact"> Contact Number </label>
                                    <input type="text" name="contact" placeholder="09XXXXXXXXX" value = "<?php echo $data['member_contact']; ?>">
                                </div>
                            </div>
                            <!-- 3rd row -->
                            <div class="input-row">      
                                <div class="input-box">
                                <label for="address">Address</label>
                                    <textarea name="address" placeholder="Address of Client"><?php echo $data['address']; ?></textarea>
                                </div>
                        
                                <div class="input-box">
                                    <label for="gender">Gender</label>
                                    <select name="gender">
                                    <?php global $genders; initialize_select('Gender', $genders, $data['gender']) ?>
                                    </select>

                                </div>
                            </div>
                            <!-- 4th row -->
                            <div class="input-row">      
                                <div class="input-box">
                                <label for="start_date">Start of Membership</label>
                                    <input type="date" name="start_date" value = "<?php echo $start_date; ?>" >

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
                                            initialize_select('Plan', $plan_array, $data['plan'])
                                        ?>
                                    </select>

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
                                            initialize_select('Package', $package_array, $data['package'])
                                        ?>
                                    </select>

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
                                            initialize_select('Trainer', $trainer_array, $data['trainer'])
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="input-box">
                                    <label for="status">Membership Status</label>
                                    <select name="status">
                                        <?php global $status_array; initialize_select('Status', $status_array, $data['status']) ?>
                                    </select>
                                </div>
                            </div>


                            <input type="hidden" name="member_id" value="<?php echo $data['member_id'];?>">
                            <!-- submit field -->
                            <div class="form-actions">
                                <button class="btn-submit" type="submit" name="submit" value="update">Update</button>
                                <button class="btn-submit" style="background-color: #ad0202;" type="submit" name="submit" value="cancel">Go Back</button>
                            </div>
                            

                        </form>
                </div>
                </body>
                </html>


<?php

            }
            function update_user($data) { ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="edit.css">
                <title></title>
                </head>
                <body>
          
                <div class="container">
                <h1>Change Password</h1><br>
                    <form class="form-container" method="post">
                        <div class="input-box">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-input" type="text" name="password" placeholder="Password" value="<?php echo $data['password']?>">
                        </div>
                        <input type="hidden" name="username" value="<?php echo $data['username'];?>">
                        <div class="form-actions">
                            <button class="btn-submit" type="submit" name="submit" value="update">Update</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn-submit" style="background-color: #ad0202;" type="submit" name="submit" value="cancel">Go Back</button>
                        </div>
                    </form>
                </div>
                </body>
                </html>
    
    
            <?php 
                }
?>  