<?php 
    include 'includes/config.php';
$page = 'login';
if(!isset($_SESSION)){
    session_start();
}
$loggedIn='false';
$ok = true;
$feedback = "";

if(isset($_POST['submit'])){
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ( !isset($username) || empty($username) ) {
        $ok = false;
        $feedback= 'Username cannot be empty!';
    }
    
    if ( !isset($password) || empty($password) ) {
        $ok = false;
        $feedback = 'Password cannot be empty!';
    }
    
    // SQL query to select data from database
    $sql = "SELECT * FROM `lecturers` WHERE `username` = ? OR `email` = ? OR `phone` = ?";
    $stmt=mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        $feedback='there was an error!';
        $ok=false;
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt,"sss",$username,$username,$username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }
    
    if(mysqli_num_rows($result)>0){
        $ok=true;
    }
    else{
        $ok=false;
        $feedback = 'Account does not exist! Try checking your username.';
    }
    
    if ($ok) {
        while($rows=mysqli_fetch_assoc($result)){
            $fnamedb = $rows['full_name'];
            $emailinDB = $rows['email'];
            $phoneinDB = $rows['phone'];
            $userinDB = $rows['username'];
            $pwdinDB = $rows['password'];
            $clIdinDB = $rows['id'];
            $unhashedpwd = password_verify($password,$pwdinDB);
            if (($username == $userinDB && $password == $unhashedpwd)||($username == $emailinDB && $password == $unhashedpwd)||($username == $phoneinDB && $password == $unhashedpwd)) {
                //cookies
                $hashedId = password_hash($clIdinDB,PASSWORD_DEFAULT);
                $hashedUname = password_hash($userinDB,PASSWORD_DEFAULT);
                setcookie('clientId',$hashedId,time()+60*60*24*30,'/','');
                setcookie('clientUsername',$userinDB,time()+60*60*24*30,'/','');
                $_COOKIE['clientId']=$hashedId;
                $_COOKIE['logged_in'] = true;
                $_SESSION['clientId'] = $hashedId;
                $_SESSION['clientUsername'] = $userinDB;
                $_SESSION['logged_in'] = true;
                $ok = true;
                $loggedIn='true';
                $feedback = 'Successful login!';
                header("Location: index.php");
            } else {
                $ok = false;
                $feedback = "Incorrect username/password combination!";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/meta-tags.php'?>
    <title>Login To Your Account</title>
    <style>
        .card{
            top: 250px;
        }
    </style>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="row wrapper">
        <div class="col-md-12">
            <div class="col-md-5 mx-auto">
                <div class="card card-primary mx-auto">
                    <div class="card-header">Sign In</div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-row">
                                <div class="form-group p-3">
                                    <label for="id_username">Username:</label>
                                    <input type="text" name="username" id="id_username" placeholder="username" required class="form-control">
                                </div>
                                <div class="form-group p-3">
                                    <label for="id_password">Password:</label>
                                    <input type="password" name="password" id="id_password" placeholder="*********" required class="form-control">
                                </div>
                                <div class="form-group p-3">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="togglePassword">
                                        <label class="custom-control-label" for="togglePassword">Show Password</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Login" class="btn btn-danger p-2 px-3" name="submit">
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/script-tags.php');?>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#id_password');
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
        });
    </script>
</body>
</html>