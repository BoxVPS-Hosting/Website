<?php
// Include config file
require_once '../config.php';
 
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = 'Hmm.. we kinda need your email...';
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'I mean, a password is kinda required...';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT email, password FROM users WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($email, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['email'] = $email;      
                            header("location: ../welcome");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'Looks like that password is not quite right.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $email_err = 'Welp, we cannot find an account with that email.';
                }
            } else{
                echo ":/ Looks like something blew up in the server room.";
            }
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login > Box VPS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Box offers high-performance unlimited hosting solutions for anyone! They are easy to set up and support is there to help you at every step of the way!">
    <meta name="author" content="techtoolbox">
    <meta name="keywords" content="VPS,hosting,startup,box,boxvps,servers,host,register">
    <meta name="theme-color" content="#6c4cff"/>
    <link rel="icon" href="../graphics/favicon.ico">
    
    <!-- Import Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <!-- Import Custom CSS -->
    <link rel="stylesheet" href="../style.css">
</head>
<body style="background-color: #613aff;">
    <div class="wrapper">
        <div class="card login-card">
          <div class="card-body login-card-body">
            <h1 class="login-card-title">👋 Hey! Welcome back!</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <input type="email" name="email" class="form-control login-card-field" placeholder="✉️ Email address" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="password" class="form-control login-card-field" placeholder="🔑 Password">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn login-card-button" value="Ship your package!">
                </div>
                <p style="text-align: center"><a href="../register" class="login-card-signup-link">Need a packing slip? Sign up!</a></p>
            </form>
          </div>
        </div>
    </div>
</body>
</html>
