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
        $lecturer = isset($_POST['lecturer'])?$_POST['lecturer']:'';
        $required[]=$lecturer;
        $class_group = isset($_POST['class'])?$_POST['class']:'';
        $required[]=$class_group;
        $lesson_name = isset($_POST['lesson_name'])?$_POST['lesson_name']:'';
        $required[]=$lesson_name;
        $department = isset($_POST['department'])?$_POST['department']:'';
        $required[]=$department;
        $lesson_time = isset($_POST['lesson_time'])?$_POST['lesson_time']:'';
        $required[]=$lesson_time;
        $items_taught = isset($_POST['items_taught'])?$_POST['items_taught']:'';
        for($i=0;$i<count($required);$i++){
            if(empty($required[$i])){
                $ok = false;
                $feedback = "Please fill out all the required fields.";
                break;
            }
        }
        if($ok){
            $sql= "INSERT INTO `attendance_log`(`department`,`lecturer`,`lesson_time`,`lesson_name`,`items_taught`,`created`) VALUES(?,?,?,?,?,?)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                $feedback='there was an error!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"ssssss",$department,$lecturer,$lesson_time,$lesson_name,$items_taught,$cur_time_stamp);
                if(mysqli_stmt_execute($stmt)){
                    $feedback = "Record has been submitted successfully!";
                    header("Location: add-register.php?class=$class_group&sbj=$lesson_name");
                }
                // $result = mysqli_stmt_get_result($stmt);
            }
        }    
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    $classes_sql= "SELECT * FROM `classes` ORDER BY `id` ASC";
    $classes_result = $conn->query($classes_sql);
    $lec_sql= "SELECT * FROM `lecturers` ORDER BY `id` ASC";
    $lec_result = $conn->query($lec_sql);
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Teacher's Attendance Coverage</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form action="" method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Teacher's Attendance Coverage</div></div>
                        <div class="card-body">
                            <div class="form-group p-3">
                                <label for="id_lecturer">Tutor:</label>
                                <select name="lecturer" id="id_lecturer" class="form-control form-select select2bs4" required>
                                    <option value="">Select Tutor</option>
                                    <?php while($rows=mysqli_fetch_assoc($lec_result)){?>
                                    <option value="<?php echo $rows['full_name'];?>"><?php echo $rows['full_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_lesson_name">Lesson Name:</label>
                                <input type="text" name="lesson_name" id="id_lesson_name" placeholder="lesson name" class="form-control" required>
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
                                <label for="id_class">Class Group:</label>
                                <select name="class" id="id_class" class="form-control form-select select2bs4" required>
                                    <option value="">Select Class</option>
                                    <?php while($rows=mysqli_fetch_assoc($classes_result)){?>
                                    <option value="<?php echo $rows['class_name'];?>"><?php echo $rows['class_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_lesson_time">Lesson Time:</label>
                                <input type="time" name="lesson_time" id="id_lesson_time" placeholder="lesson time" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_items_taught">Items Taught:</label>
                                <textarea name="items_taught" id="id_items_taught" cols="30" rows="4" class="form-control" placeholder="A list of topics and sub-topics taught..."></textarea>
                            </div>
                            <div class="form-group p-3">
                                <input type="submit" value="Proceed To Register" name="submit" class="btn btn-danger">
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