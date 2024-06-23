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
        $department_code = isset($_POST['dpt_code'])?$_POST['dpt_code']:'';
        $required[]=$department_code;
        $department_name = isset($_POST['dpt_name'])?$_POST['dpt_name']:'';
        $required[]=$department_name;
        for($i=0;$i<count($required);$i++){
            if(empty($required[$i])){
                $ok = false;
                $feedback = "Please fill out all the required fields.";
                break;
            }
        }
        if($ok){
            $sql= "INSERT INTO `departments`(`dpt_code`,`dpt_name`,`created`) VALUES(?,?,?)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                $feedback='there was an error!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"sss",$department_code,$department_name,$cur_time_stamp);
                if(mysqli_stmt_execute($stmt)){
                    $feedback = "Department has been added successfully!";
                }
                // $result = mysqli_stmt_get_result($stmt);
            }
        }    
    }
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Add Departments</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Add New Department</div></div>
                        <div class="card-body">
                            <div class="form-group p-3">
                                <label for="id_dpt_code">Department Code:</label>
                                <input type="text" name="dpt_code" id="id_dpt_code" placeholder="department code" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_dpt_name">Department Name:</label>
                                <input type="text" name="dpt_name" id="id_dpt_name" placeholder="department name" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <input type="submit" name="submit" value="Submit" class="btn btn-danger">
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