<?php 
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
    }
    include 'includes/config.php';
    if(isset($_GET['action'])){
        $action = isset($_GET['action'])?$_GET['action']:'';
        $pk = isset($_GET['id'])?$_GET['id']:'';
        if($action=="edit" && ($pk)!=''){
            $editSql = "SELECT * FROM `courses` WHERE `id`=?";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$editSql)){
                $feedback='SQL Statement Failed!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"s",$pk);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            }
        }
        if(isset($_POST['uid'])){
            $required = [];
            $ok = true;
            $course_code = isset($_POST['course_code'])?$_POST['course_code']:'';
            $required[]=$course_code;
            $course_name = isset($_POST['course_name'])?$_POST['course_name']:'';
            $required[]=$course_name;
            $department = isset($_POST['department'])?$_POST['department']:'';
            $required[]=$department;
            for($i=0;$i<count($required);$i++){
                if(empty($required[$i])){
                    $ok = false;
                    $feedback = "Please fill out all the required fields.";
                    break;
                }
            }
            if($ok){
                $sql= "UPDATE `courses` SET `course_code`=?,`course_name`=?,`department`=? WHERE `id`=?";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    $feedback='there was an error!';
                    $ok=false;
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt,"ssss",$course_code,$course_name,$department,$pk);
                    if(mysqli_stmt_execute($stmt)){
                        $feedback = "Course has been updated successfully!";
                    }
                }
            }  
        }
          
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    while($rows=mysqli_fetch_assoc($result)){
        $uid = $rows['id'];
        $code = $rows['course_code'];
        $name = $rows['course_name'];
        $dpt = $rows['department'];
    }
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Edit Courses</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form action="" method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Edit Course</div></div>
                        <div class="card-body">
                            <input type="hidden" name="uid">
                            <div class="form-group p-3">
                                <label for="id_course_code">Course Code:</label>
                                <input type="text" name="course_code" id="id_course_code" placeholder="course code" class="form-control" required value="<?php echo $code ?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_course_name">Course Name:</label>
                                <input type="text" name="course_name" id="id_course_name" placeholder="course name" class="form-control" required value="<?php echo $name ?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_department">Department:</label>
                                <select name="department" id="id_department" class="form-control form-select select2bs4" required>
                                    <option value="">Select Department</option>
                                    <?php while($rows=mysqli_fetch_assoc($dpt_result)){
                                        if($dpt == $rows['dpt_code']){ 
                                    ?>
                                    <option value="<?php echo $rows['dpt_code'];?>" selected><?php echo $rows['dpt_name']?></option>
                                    <?php }else{?>
                                    <option value="<?php echo $rows['dpt_code'];?>"><?php echo $rows['dpt_name']?></option>
                                    <?php }}?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <input type="submit" value="Submit" name="Update" class="btn btn-danger">
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