<?php 
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
    }
    include 'includes/config.php';
    include('includes/create-id.php');
    $target_sbj = isset($_GET['sbj'])?$_GET['sbj']:'';
    $target_class = isset($_GET['class'])?$_GET['class']:'';
    if($target_sbj!=''&& $target_class!=''){
        $students_sql= "SELECT * FROM `students` WHERE `class_name`=? ORDER BY `id` ASC"; //SQL to get all students in the class
        $sbj_sql= "SELECT * FROM `subjects` WHERE `sbj_code`=? ORDER BY `id` ASC";

        // executing the students SQL
        if($students_sql!=''){
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$students_sql)){
                $feedback='SQL Statement Failed!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"s",$target_class);
                mysqli_stmt_execute($stmt);
                $students_result = mysqli_stmt_get_result($stmt);
            }
        }
        if($sbj_sql!=''){
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sbj_sql)){
                $feedback='SQL Statement Failed!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"s",$target_sbj);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while($rows=mysqli_fetch_assoc($result)){
                    $sbj_dpt = $rows['department'];
                    $sbj_code = $rows['sbj_code'];
                    break;
                }
            }
        }        
    }
    if(isset($_POST['submit'])){
        $required = [];
        $ok = true;

        $students = isset($_POST['students'])?$_POST['students']:'';
        $required[]=$students;
        $class = isset($_POST['class'])?$_POST['class']:'';
        $required[]=$class;
        $statuses = isset($_POST['statuses'])?$_POST['statuses']:'';
        $required[]=$statuses;
        for($i=0;$i<count($required);$i++){
            if(empty($required[$i])){
                $ok = false;
                $feedback = "Please fill out all the required fields.";
                break;
            }
        }
        if($ok){
            $batchSql = "SELECT * FROM `student_attendance`";
            $batch = genId($conn,$batchSql,'REG','attendance_batch');
            // $student_list = str_split($students);
            $student_list = explode(',',$students);
            $status_list = explode(',',$statuses);
            for($s=0;$s<count($student_list);$s++){
                $sql= "INSERT INTO `student_attendance`(`attendance_batch`,`class`,`department`,`lecturer`,`student`,`status`,`created`) VALUES(?,?,?,?,?,?,?)";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    $feedback='there was an error!';
                    $ok=false;
                    exit();
                    break;
                }
                else{
                    $student = $student_list[$s];
                    if($status_list[$s]=='on'){
                        $status = 'present';
                    } 
                    else{
                        $status = 'absent';
                    }
                    $lecturer = 'none';
                    mysqli_stmt_bind_param($stmt,"sssssss",$batch,$class,$sbj_dpt,$lecturer,$student,$status,$cur_time_stamp);
                    if(mysqli_stmt_execute($stmt)){
                        $ok=true;
                    }
                    // $result = mysqli_stmt_get_result($stmt);
                }
            }
            if($ok){
                $feedback = "Register has been submitted successfully!";
            }
        }    
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    $sbj_sql= "SELECT * FROM `subjects` ORDER BY `id` ASC";
    $sbj_result = $conn->query($sbj_sql);
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Daily Class Attendance</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form action="" method="post" class="col-md-8 mx-auto" id="attendance_form" name="attendance_form" onsubmit="return prepare_register()">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Daily Class Attendance</div></div>
                        <div class="card-body">
                        <div class="form-group p-3">
                            <label for="id_class">Lesson Name:</label>
                            <input type="text" name="class" id="id_class" placeholder="Lesson taught" class="form-control" required>
                        </div>
                        <table class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%" id="mini-table">
                            <thead>
                                <th>#</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>Present</th>
                            </thead>
                            <tbody>
                            <?php 
                            if(mysqli_num_rows($students_result)>0){
                                $autonum = 0;
                                while($rows=mysqli_fetch_assoc($students_result)){
                                    $autonum++;
                                    $uid = $rows['id'];
                                    $reg_no = $rows['reg_no'];
                                    $full_name = $rows['full_name'];

                                ?>
                                <tr>
                                    <td><?php echo $autonum;?></td>
                                    <td class="student" data="<?php echo $reg_no;?>"><?php echo $reg_no;?></td>
                                    <td><?php echo $full_name;?></td>
                                    <td>
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="status custom-control-input" id="id_status_<?php echo $uid;?>">
                                            <!-- <label class="custom-control-label" for="id_status"></label> -->
                                        </div>
                                    </td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                            <div class="form-group p-3">
                                <input type="hidden" name="students" id="id_students">
                                <input type="hidden" name="statuses" id="id_statuses">
                                <input type="submit" value="Submit" name="submit" class="btn btn-danger">
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </main>
    </div>
    <?php include('includes/script-tags.php');?>
    <script>
        var attendance_form = document.forms['attendance_form'];
        attendance_form.addEventListener('submit',prepare_register);
        function prepare_register(e){
            // e.preventDefault();
            let students_list = [];
            let status_list = [];
            var students = document.querySelectorAll('.student');
            students.forEach(s=>{
                let value = s.getAttribute('data');
                students_list.push(value);
            })
            var statuses = document.querySelectorAll('.status');
            statuses.forEach(s=>{
                let stat = s.value;
                status_list.push(stat);
            });

            let students_field = document.getElementById('id_students');
            let statuses_field = document.getElementById('id_statuses');
            students_field.value=students_list;
            statuses_field.value=status_list;
            return true;
        }
        
    </script>
</body>
</html>