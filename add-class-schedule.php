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
        $room = isset($_POST['room'])?$_POST['room']:'';
        $required[]=$room;
        $subject = isset($_POST['subject'])?$_POST['subject']:'';
        $required[]=$subject;
        $tutor = isset($_POST['lecturer'])?$_POST['lecturer']:'';
        $required[]=$tutor;
        $lesson_time = isset($_POST['lesson_time'])?$_POST['lesson_time']:'';
        $required[]=$lesson_time;
        $day_of_week = isset($_POST['day_of_week'])?$_POST['day_of_week']:'';
        $required[]=$day_of_week;
        $student_group = isset($_POST['student_group'])?$_POST['student_group']:'';
        $required[]=$student_group;
        $position = isset($_POST['position'])?$_POST['position']:'';
        $required[]=$position;
        for($i=0;$i<count($required);$i++){
            if(empty($required[$i])){
                $ok = false;
                $feedback = "Please fill out all the required fields.";
                break;
            }
        }
        if($ok){
            $sql= "INSERT INTO `class_schedule`(`room`,`subject`,`tutor`,`lesson_time`,`day_of_week`,`student_group`,`position`,`created`) VALUES(?,?,?,?,?,?,?,?)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                $feedback='there was an error!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"ssssssss",$room,$subject,$tutor,$lesson_time,$day_of_week,$student_group,$position,$cur_time_stamp);
                if(mysqli_stmt_execute($stmt)){
                    $feedback = "Record has been submitted successfully!";
                }
                // $result = mysqli_stmt_get_result($stmt);
            }
        }    
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    $reg_sql= "SELECT * FROM `student_attendance` ORDER BY `id` ASC";
    $reg_result = $conn->query($reg_sql);
    $lec_sql= "SELECT * FROM `lecturers` ORDER BY `id` ASC";
    $lec_result = $conn->query($lec_sql);
    $classes_sql= "SELECT * FROM `classes` ORDER BY `id` ASC";
    $classes_result = $conn->query($classes_sql);
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Teacher's Attendance Coverage<</title>
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
                                <label for="id_subject">Subject Name:</label>
                                <input type="text" name="subject" id="id_subject" placeholder="lesson name" class="form-control" required>
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
                                <label for="id_lesson_time">Lesson Time:</label>
                                <input type="time" name="lesson_time" id="id_lesson_time" placeholder="lesson time" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_room">room:</label>
                                <input type="text" name="room" id="id_room" placeholder="room" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_day_of_week">Day Of The Week:</label>
                                <input type="text" name="day_of_week" id="id_day_of_week" placeholder="Day" class="form-control" required>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_department">Student Group:</label>
                                <select name="student_group" id="id_student_group" class="form-control form-select select2bs4" required>
                                    <option value="">Select Class</option>
                                    <?php while($rows=mysqli_fetch_assoc($classes_result)){?>
                                    <option value="<?php echo $rows['class_name'];?>"><?php echo $rows['class_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_position">Schedule Position:</label>
                                <input type="number" name="position" id="id_position" placeholder="position in order of appearance" class="form-control" required>
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