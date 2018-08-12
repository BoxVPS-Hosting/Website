<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Come on, tell us what to call you!";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $email_err = "Hmmm... looks like that's already taken...";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "I couldn't make your shipping label... :/";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "It's spooky out there, let's make a password.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Let's make it 6 characters or longer.";
    } else{
        $password = trim($_POST['password']);
    }

    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $email, $param_password);

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "I couldn't make your shipping label... :/";
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
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Box offers high-performance unlimited hosting solutions for anyone! They are easy to set up and support is there to help you at every step of the way!">
    <meta name="author" content="techtoolbox">
    <meta name="keywords" content="VPS,hosting,startup,box,boxvps,servers,host,register">
    <meta name="theme-color" content="#6c4cff"/>
    <link rel="icon" href="../graphics/favicon.ico">

    <title>Let's get going > Box VPS</title>

    <!-- Import Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <!-- Import Animate CSS -->
    <link rel="stylesheet" href="../animate.css">

    <!-- Import Animsition CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animsition/4.0.2/css/animsition.min.css">

    <!-- Import Custom CSS -->
    <link rel="stylesheet" href="../style.css">
</head>
<body style="background-color: #f1f1f1">
    <div class="container-fluid">
        <div class="row">
            <div class="card mx-auto register-card">
                <div class="card-body register-card-body">
                    <h1 class="register-card-title">👋 Hey, welcome to Box!</h1>
                    <h5 class="register-card-subtitle">We just need a few details to get going!</h5>
                
                    <!-- Registration form -->
                    <form class="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <input type="email" name="email" class="form-control register-field" placeholder="✉ What's your email address?" value="<?php echo $email; ?>">
                            <span class="help-block register-error-text"><?php echo $email_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <input type="password" name="password" class="form-control register-field" placeholder="🔑 Let's create a password." value="<?php echo $password; ?>">
                            <span class="help-block register-error-text"><?php echo $password_err; ?></span>
                        </div>
                        <!--<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <input type="password" name="confirm_password" class="form-control register-field" placeholder="🔑 Let's verify your password." value="<?php echo $confirm_password; ?>">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                        </div> -->
                        <div class="form-group row register-links-row">
                            <div class="col-6">
                                <a href="../login" class="register-login-link">Sign in</a>
                            </div>
                            <div class="col-6">
                                <input type="submit" class="btn btn-primary register-button" value="Let's do this!">
                            </div>                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
    -->
</body>
</html>
