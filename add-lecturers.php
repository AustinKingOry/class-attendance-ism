<?php 
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
    }
    else{
        include 'includes/config.php';
        if(isset($_POST['submit'])){
            $required = [];
            $ok = true;
            $full_name = isset($_POST['full_name'])?$_POST['full_name']:'';
            $required[]=$full_name;
            $tsc_no = isset($_POST['tsc_no'])?$_POST['tsc_no']:'';
            $department = isset($_POST['department'])?$_POST['department']:'';
            $required[]=$department;
            $phone = isset($_POST['phone'])?$_POST['phone']:'';
            $required[]=$phone;
            $title = isset($_POST['title'])?$_POST['title']:'';
            $required[]=$title;
            $subject1 = isset($_POST['subject1'])?$_POST['subject1']:'';
            $required[]=$subject1;
            $subject2 = isset($_POST['subject2'])?$_POST['subject2']:'';
            $required[]=$subject2;
            $gender = isset($_POST['gender'])?$_POST['gender']:'';
            $required[]=$gender;
            $username = isset($_POST['username'])?$_POST['username']:'';
            $required[]=$username;
            $password = isset($_POST['password'])?$_POST['password']:'';
            $required[]=$password;
            $email = isset($_POST['email'])?$_POST['email']:'';
            $required[]=$email;
            for($i=0;$i<count($required);$i++){
                if(empty($required[$i])){
                    $ok = false;
                    $feedback = "Please fill out all the required fields.";
                    break;
                }
            }
            if($ok){
                $password = password_hash($password, PASSWORD_DEFAULT);
                $sql= "INSERT INTO `lecturers`(`full_name`,`tsc_no`,`department`,`phone`,`title`,`subject1`,`subject2`,`gender`,`created`,`username`,`password`,`email`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    $feedback='there was an error!';
                    $ok=false;
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt,"ssssssssssss",$full_name,$tsc_no,$department,$phone,$title,$subject1,$subject2,$gender,$cur_time_stamp,$username,$password,$email);
                    if(mysqli_stmt_execute($stmt)){
                        $feedback = "Lecturer has been added successfully!";
                    }
                    // $result = mysqli_stmt_get_result($stmt);
                }
            }    
        }
        $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
        $dpt_result = $conn->query($dpt_sql);
        $sbj_sql= "SELECT * FROM `subjects` ORDER BY `id` ASC";
        $sbj_result = $conn->query($sbj_sql);
        $subjects = [];
        while($rows=mysqli_fetch_assoc($sbj_result)){
            $subjects[]=$rows['sbj_code'];
        }
    }
    
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Add Lectures</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Add New Lecturer</div></div>
                        <div class="card-body">
                            <div class="form-group p-3">
                                <label for="id_full_name">Full Name:</label>
                                <input type="text" name="full_name" id="id_full_name" placeholder="Name" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_username">Username:</label>
                                <input type="text" name="username" id="id_username" placeholder="username" required class="form-control">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_tsc_no">Tsc.No:</label>
                                <input type="text" name="tsc_no" id="id_tsc_no" placeholder="tsc number" class="form-control">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_department">Department:</label>
                                <select name="department" id="id_department" class="form-control form-select select2bs4" required>
                                    <option value="">Select Department</option>
                                    <?php while($rows=mysqli_fetch_assoc($dpt_result)){?>
                                    <option value="<?php echo $rows['dpt_code'];?>"><?php echo $rows['dpt_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_phone">Phone Number:</label>
                                <input type="tel" name="phone" id="id_phone" placeholder="+2547&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_email">Email:</label>
                                <input type="email" name="email" id="id_email" placeholder="example@example.com" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_title">Title:</label>
                                <input type="text" name="title" id="id_title" placeholder="Mr./Mrs./Ms./Dr." class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_subject1">First Subject:</label>
                                <select name="subject1" id="id_subject1" class="form-control form-select select2bs4" required>
                                    <option value="">Select Subject</option>
                                    <?php for($a=0;$a<count($subjects);$a++){?>
                                    <option value="<?php echo $subjects[$a];?>"><?php echo $subjects[$a];?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_subject2">Second Subject:</label>
                                <select name="subject2" id="id_subject2" class="form-control form-select select2bs4" required>
                                    <option value="">Select Subject</option>
                                    <?php for($a=0;$a<count($subjects);$a++){?>
                                    <option value="<?php echo $subjects[$a];?>"><?php echo $subjects[$a];?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_gender">Gender:</label>
                                <select name="gender" id="id_gender" class="form-control form-select select2bs4" required>
                                    <option value="">select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_username">Password:</label>
                                <input type="password" name="password" id="id_password" placeholder="********" required class="form-control">
                            </div>
                            <div class="form-group p-3">
                                <input type="submit" value="Submit" name="submit" class="btn btn-danger">
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </main>
    </div>
    <?php include('includes/script-tags.php');?>
</body>
</html>