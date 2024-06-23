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
        $class_name = isset($_POST['class_name'])?$_POST['class_name']:'';
        $required[]=$class_name;
        $course = isset($_POST['course'])?$_POST['course']:'';
        $required[]=$course;
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
            $sql= "INSERT INTO `classes`(`class_name`,`course`,`department`,`created`) VALUES(?,?,?,?)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                $feedback='there was an error!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"ssss",$class_name,$course,$department,$cur_time_stamp);
                if(mysqli_stmt_execute($stmt)){
                    $feedback = "Class has been added successfully!";
                }
                // $result = mysqli_stmt_get_result($stmt);
            }
        }    
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    $course_sql= "SELECT * FROM `courses` ORDER BY `id` ASC";
    $course_result = $conn->query($course_sql);
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Add Classes</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form action="" method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Add New Class</div></div>
                        <div class="card-body">
                            <div class="form-group p-3">
                                <label for="id_class_name">Name:</label>
                                <input type="text" name="class_name" id="id_class_name" placeholder="class name" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_course">Course:</label>
                                <select name="course" id="id_course" class="form-control form-select select2bs4" required>
                                    <option value="">Select course</option>
                                    <?php while($rows=mysqli_fetch_assoc($course_result)){?>
                                    <option value="<?php echo $rows['course_code'];?>"><?php echo $rows['course_name']?></option>
                                    <?php }?>
                                </select>
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