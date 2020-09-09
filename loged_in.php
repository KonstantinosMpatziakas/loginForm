<?php
session_start();
include_once 'dbconnect.php';
        $user=$_SESSION['uname'];
        $sql = "SELECT * FROM users WHERE username='$user'";
        $result = mysqli_query($conn,$sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck>0){
            while($row = mysqli_fetch_assoc($result)){
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" >
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
        <title>Profile</title>
        <style>
            .log {
                background: red;
                border:none;
                border-radius:8px;
                width:80px;
                height:20px;
                color:white;
                cursor:pointer;
            }
            .log:hover{
                width:100px;
                height:40px;
                font-size:20px;
            }
        </style>
    </head>

    <body>
    <div class="backdrop">
    </div>
    <div class="login-wrapper" style="max-height:600px">
        <form class="login-html" action="" method="POST">
            <div class="login-heading">
                <h1 class="tab">Welcome <?php echo $user ?></h1>
            </div>
            <div class="login-form">
                <div class="sign-in-html">
                    <div class="group">
                        <label for="user" class="label">Username</label>
                        <input type="text" name="username" class="input" value="<?php
                            echo $row['username']; 
                        ?>">
                    </div>
                    <div class="group">
                        <label for="user" class="label">firstname</label>
                        <input type="text" name="firstname" class="input" value="<?php 
                                    echo $row['firstName'];
                        ?>">
                    </div>
                    <div class="group">
                        <label for="user" class="label">lastname</label>
                        <input type="text" name="lastname" class="input" value="<?php 
                                    echo $row['lastName'];
                        ?>">
                    </div>
                    <div class="group">
                        <label for="user" class="label">email</label>
                        <input type="text" name="email"  class="input" value="<?php 
                                    echo $row['email'];
                        ?>">
                    </div>
                    <div class="group">
                        <label for="pass" class="label">Password</label>
                        <div class="pass-container">
                            <input type="password" id ="pass1" name="pass" class="input" data-type="password" value="<?php  
                                $ciphering = "AES-128-CTR";
                                $iv_length = openssl_cipher_iv_length($ciphering); 
                                $options = 0; 
                                $decryption_iv = '1234567891011121';
                                $decryption_key = "GeeksforGeeks";  
                                $decryption=openssl_decrypt ($row['passwd'], $ciphering,  
                                        $decryption_key, $options, $decryption_iv);
                                
                                echo $decryption;
                            ?>">
                            <span class="show-pass" id="show-pass" onclick="toggle()">
                                <i class="far fa-eye" onclick="myFunction(this)"></i>
                            </span>
                        </div>
                    </div>
                    <form action="" method="POST">
                        <input type="submit" onmouseenter="enter()" onmouseleave="leave()" id="lg" name="logout" value="logout" class="log">
                    </form>
                    <div class="group">
                        <input type="submit" name="submit2" class="button" value="update">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        var state = false;

        function toggle() {
            if (state) {
                document.getElementById("pass1").setAttribute("type", "password");
                state = false;
            } else {
                document.getElementById("pass1").setAttribute("type", "text");
                state = true;
            }
        }

        function myFunction(show) {
            show.classList.toggle("fa-eye-slash");
        }

        function enter(){
            document.getElementById("lg").value="logout!";
        }
        function leave(){
            document.getElementById("lg").value="logout";
        }
    </script>
    </body>
</html>

<?php 
            }}
    if(isset($_POST['submit2'])){

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
        
        /*if((empty($first) || empty($last) || empty($mail) || empty($pass) || empty($username)){
            echo '<script>alert("one or more empty fields")</script>';
            exit();
            if($pass != $repeatPass){
                echo '<script>alert("one or more empty fields")</script>';
                exit();
            }else{
                if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
                    echo '<script>alert("invalid email")</script>';
                    exit();
                }
            }
        }else{*/
            $sql = "UPDATE users SET username='$username',passwd='$encryption',firstName='$first',lastName='$last',email='$mail' WHERE username='$user'";       
   
            if(mysqli_query($conn,$sql)){
                echo '<script>alert("done")</script>';
            }else{
                echo '<script>alert("something went wrong")</script>';
            }
        }
?> 