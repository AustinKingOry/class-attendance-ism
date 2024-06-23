<?php 
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
    }
    include 'includes/config.php';
    if(isset($_POST['submit'])){
        $required = [];
        $ok = true;
        $reg_no = isset($_POST['reg_no'])?$_POST['reg_no']:'';
        $required[]=$reg_no;
        $full_name = isset($_POST['full_name'])?$_POST['full_name']:'';
        $required[]=$full_name;
        $department = isset($_POST['department'])?$_POST['department']:'';
        $required[]=$department;
        $class_code = isset($_POST['class_code'])?$_POST['class_code']:'';
        $required[]=$class_code;
        $class_name = isset($_POST['class_name'])?$_POST['class_name']:'';
        $required[]=$class_name;
        $gender = isset($_POST['gender'])?$_POST['gender']:'';
        $required[]=$gender;
        for($i=0;$i<count($required);$i++){
            if(empty($required[$i])){
                $ok = false;
                $feedback = "Please fill out all the required fields.";
                break;
            }
        }
        if($ok){
            $sql= "INSERT INTO `students`(`reg_no`,`full_name`,`department`,`class_code`,`class_name`,`gender`,`created`) VALUES(?,?,?,?,?,?,?)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                $feedback='there was an error!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"sssssss",$reg_no,$full_name,$department,$class_code,$class_name,$gender,$cur_time_stamp);
                if(mysqli_stmt_execute($stmt)){
                    $feedback = "Student has been added successfully!";
                }
                // $result = mysqli_stmt_get_result($stmt);
            }
        }    
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Add Students</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form action="" method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Add New Student</div></div>
                        <div class="card-body">
                            <div class="form-group p-3">
                                <label for="id_full_name">Full Name:</label>
                                <input type="text" name="full_name" id="id_full_name" placeholder="Name" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_reg_no">Registration Number:</label>
                                <input type="text" name="reg_no" id="id_reg_no" placeholder="Adm" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_class_code">Class Code:</label>
                                <input type="text" name="class_code" id="id_class_code" placeholder="class code" class="form-control" required>
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
                                <label for="id_class_name">Class Name:</label>
                                <input type="text" name="class_name" id="id_class_name" placeholder="Class" class="form-control" required>
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