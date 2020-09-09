<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
    <link type="text/css" rel="stylesheet" href="style.css">
    <title>Sign Up Form</title>
    </head>

    <body>
    <div class="backdrop"></div>
    <div class="login-wrapper" style="max-height:580px">
        <form class="login-html" action="" method="POST">
            <div class="login-heading">
                <h1 class="tab">Sign Up</h1>
            </div>
            <div class="login-form">
                <div class="sign-in-html">
                    <div class="group">
                        <label for="user" class="label">Username</label>
                        <input type="text" name="username" class="input">
                    </div>
                    <div class="group">
                        <label for="user" class="label">firstname</label>
                        <input type="text" name="firstname" class="input">
                    </div>
                    <div class="group">
                        <label for="user" class="label">lastname</label>
                        <input type="text" name="lastname" class="input">
                    </div>
                    <div class="group">
                        <label for="user" class="label">email</label>
                        <input type="text" name="email"  class="input">
                    </div>
                    <div class="group">
                        <label for="pass" class="label">Password</label>
                        <div class="pass-container">
                            <input type="password" id="pass" name="pass" class="input" data-type="password">
                            <span class="show-pass" id="show-pass" onclick="toggle()">
                                <i class="far fa-eye" onclick="myFunction(this)"></i>
                            </span>
                        </div>
                    </div>
                    <div class="group">
                        <label for="pass" class="label">repeat Password</label>
                        <div class="pass-container">
                            <input type="password" name="repeat" class="input" data-type="password">
                            <span class="show-pass" id="show-pass" onclick="toggle()">
                                <i class="far fa-eye" onclick="myFunction(this)"></i>
                            </span>
                        </div>
                    </div>
                    <div class="group">
                        <input type="submit" name="submit" class="button" value="sign up">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        var state = false;

        function toggle() {
            if (state) {
                document.getElementById("pass").setAttribute("type", "password");
                state = false;
            } else {
                document.getElementById("pass").setAttribute("type", "text");
                state = true;
            }
        }

        function myFunction(show) {
            show.classList.toggle("fa-eye-slash");
        }
    </script>
    </body>
</html>

<?php
    if(isset($_POST['submit'])){
        include_once 'dbconnect.php';
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $first = mysqli_real_escape_string($conn, $_POST['firstname']);
        $last = mysqli_real_escape_string($conn, $_POST['lastname']);
        $mail = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = mysqli_real_escape_string($conn, $_POST['pass']);

        //encryption
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering); 
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "GeeksforGeeks";
        $encryption = openssl_encrypt($pass, $ciphering, 
            $encryption_key, $options, $encryption_iv);  
        
        if(empty($first) || empty($last) || empty($mail) || empty($pass)){
            echo '<script>alert("one or more empty fields")</script>';
            header("Location: ../loginForm/signup.php?signup=empty");
            exit();
        }else{
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    echo '<script>alert("invalid email")</script>';
                    header("Location: ../loginForm/signup.php?signup=email");
                    exit();
                }else{
                    if($pass != $_POST['repeat']){
                        echo '<script>alert("passwords doesnt match)</script>';
                        header("Location: ../loginForm/signup.php?signup=pass");
                        exit();
                    }else{
                        $query = "SELECT * FROM users WHERE username='$username'";
                        $result = mysqli_query($conn,$sql);
                        $resultCheck = mysqli_num_rows($result);

                        if($resultCheck>0){
                            header("Location: ../loginForm/signup.php?signup=usertaken");
                            exit();
                        }
                    }
                }
            }

        $sql = "INSERT INTO users (username,passwd,firstName,lastName,email) VALUES ('$username','$encryption','$first','$last','$mail')";       
   
        if(mysqli_query($conn,$sql)){
            echo '<script>alert("done")</script>';
            header("Location: ../loginForm/login.php");
        }else{
            echo '<script>alert("something went wrong")</script>';
        }
    }
?>