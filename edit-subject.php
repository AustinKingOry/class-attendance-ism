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
            $editSql = "SELECT * FROM `subjects` WHERE `id`=?";
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
            $sbj_code = isset($_POST['sbj_code'])?$_POST['sbj_code']:'';
            $required[]=$sbj_code;
            $sbj_name = isset($_POST['sbj_name'])?$_POST['sbj_name']:'';
            $required[]=$sbj_name;
            $class = isset($_POST['class'])?$_POST['class']:'';
            $required[]=$class;
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
                $sql= "UPDATE `subjects` SET `sbj_code`=?,`sbj_name`=?,`class`=?,`department`=? WHERE `id`=?";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    $feedback='there was an error!';
                    $ok=false;
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt,"sssss",$sbj_code,$sbj_name,$class,$department,$pk);
                    if(mysqli_stmt_execute($stmt)){
                        $feedback = "Subject has been updated successfully!";
                    }
                }
            }  
        }
          
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    $course_sql= "SELECT * FROM `courses` ORDER BY `id` ASC";
    $course_result = $conn->query($course_sql);
    while($rows=mysqli_fetch_assoc($result)){
        $uid = $rows['id'];
        $code = $rows['sbj_code'];
        $name = $rows['sbj_name'];
        $class = $rows['class'];
        $dpt = $rows['department'];
    }
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Edit Subjects</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form action="" method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Edit Subject</div></div>
                        <div class="card-body">
                            <input type="hidden" name="uid">
                            <div class="form-group p-3">
                                <label for="id_sbj_name">Subject Name:</label>
                                <input type="text" name="sbj_name" id="id_sbj_name" placeholder="Name" class="form-control" required value="<?php echo $name;?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_sbj_code">Subject Code:</label>
                                <input type="text" name="sbj_code" id="id_sbj_code" placeholder="subject code" class="form-control" required value="<?php echo $code;?>">
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
                                <label for="id_class">Class:</label>
                                <select name="class" id="id_class" class="form-control form-select select2bs4" required>
                                    <option value="">Select Course:</option>
                                    <?php while($rows=mysqli_fetch_assoc($course_result)){
                                        if($class == $rows['course_code']){ 
                                    ?>
                                    <option value="<?php echo $rows['course_code'];?>" selected><?php echo $rows['course_name']?></option>
                                    <?php }else{?>
                                    <option value="<?php echo $rows['course_code'];?>"><?php echo $rows['course_name']?></option>
                                    <?php }}?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <input type="submit" value="Update" name="submit" class="btn btn-danger">
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