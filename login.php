<?php 
    session_start();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
    <link type="text/css" rel="stylesheet" href="style.css">
    <title>Login Form</title>
</head>

<body>
    <div class="backdrop">
    </div>
    <div class="login-wrapper">
        <form class="login-html" action ="" method="POST">
            <div class="login-heading">
                <h1 class="tab">Login</h1>
            </div>
            <div class="login-form">
                <div class="sign-in-html">
                    <div class="group">
                        <label for="user" class="label">Username</label>
                        <input type="text" name="username" class="input">
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
                        <input type="checkbox" id="check" class="check" checked>
                        <label for="check">
                            <span class="icon"></span>Keep me signed in
                        </label>
                    </div>
                    <div class="group">
                        <input type="submit" name="submit" class="button" value="sign in">
                    </div>
                    <div class="hr"></div>
                    <div class="foot-link">
                        <a href="forgot.php">Forgot password?</a>
                    </div>
                    <div class="sign-up">
                        <h4>Don't have an account? <a href="signup.php">Sign up</a></h4>
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
        include 'dbconnect.php';

        $user = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = mysqli_real_escape_string($conn, $_POST['pass']);
        
        //encryption
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering); 
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "GeeksforGeeks";
        $encryption = openssl_encrypt($pass, $ciphering, 
            $encryption_key, $options, $encryption_iv); 
        
        if(empty($user) || empty($pass)){
            echo "<script>alert('one or more fields are empty')</script>";
            exit();
        }else{

            $sql = "SELECT * FROM users WHERE username = '$user' AND passwd = '$encryption'";
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            if($resultCheck > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $_SESSION['uname'] = $row['username'];
                }
            }
        }
    }
?>

