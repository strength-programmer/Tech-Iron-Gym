<?php
 
    $password = "";
    $username = "";

    include 'db_connect.php'; 

    $error_message = '';

    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        if (empty($username) || empty($password)) {
            $error_message = "Username and password are required.";
        } else {
            // check if username and password is in database
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($con, $sql);

            if($result){
                $row = mysqli_fetch_array($result);
                if ($row) {
                    if($row['password'] == $password){
                        session_start();
                        $_SESSION['logged'] = true;
                        $_SESSION['user'] = $username;
                        header("Location: home_page.php");
                    } else{
                        $password = "";
                        $error_message = "Incorrect password.";
                    }
                } else {
                    // username not found
                    
                    $error_message = "Invalid username.";
                }

            } else{
                // Set error message if credentials are incorrect
                $error_message = "Error: " . mysqli_error($con);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo "smoe.png"; ?>">
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/gym.css' rel='stylesheet'>
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/mail.css' rel='stylesheet'>
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/lock.css' rel='stylesheet'>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="index.php" class="logo"><i class="gg-gym"></i>Iron Gym</a>
    </header>

    <!-- Main content -->
    <section class="home">
        <div class="content">
            <h2>GO HARD OR GO HOME</h2>
            <p>Get fit, strong and motivated. The fastest way to get in shape. Shape your body, shape your destiny.</p>
        </div>

        <!-- Member login section and Form -->
        <div class="wrapper-login">
            <h2>Login</h2>

            <form method="post">
                <!-- For username -->
                <div class="input-box">
                    <span class="icon"><i class="gg-mail"></i></span>
                    <input type="text" name="username" value="<?php echo $username; ?>" required>
                    <label>Username</label>
                </div>

                <!-- For password -->
                <div class="input-box">
                    <span class="icon"><i class="gg-lock"></i></span>
                    <input type="password" name="password" value="<?php echo $password; ?>" required>
                    <label>Password</label>
                </div>

                <br><br>

                <!-- Remember Me -->
                <div class="remember-forgot">
                    <label><input type="checkbox" name="remember_me"> Remember me</label>
                </div>

                <!-- Login Button -->
                <button type="submit" name="submit" class="btn">Login</button>
            </form>

            <!-- Display error message if any -->
            <?php
            if (!empty($error_message)) {
                echo "<p style='color:lightblue; text-align:center;'>$error_message</p>";
            }
            ?>
        </div>
    </section>
</body>
</html>

<?php
// Close database connection
mysqli_close($con);
?>
