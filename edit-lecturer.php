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
            $editSql = "SELECT * FROM `lecturers` WHERE `id`=?";
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
            $full_name = isset($_POST['full_name'])?$_POST['full_name']:'';
            $required[]=$full_name;
            $tsc_no = isset($_POST['tsc_no'])?$_POST['tsc_no']:'';
            $required[]=$tsc_no;
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
            for($i=0;$i<count($required);$i++){
                if(empty($required[$i])){
                    $ok = false;
                    $feedback = "Please fill out all the required fields.";
                    break;
                }
            }
            if($ok){
                $sql= "UPDATE `lecturers` SET `full_name`=?,`tsc_no`=?,`department`=?,`phone`=?,`title`=?,`subject1`=?,`subject2`=?,`gender`=? WHERE `id`=?";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    $feedback='there was an error!';
                    $ok=false;
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt,"sssssssss",$full_name,$tsc_no,$department,$phone,$title,$subject1,$subject2,$gender,$pk);
                    if(mysqli_stmt_execute($stmt)){
                        $feedback = "Lecturer has been updated successfully!";
                    }
                }
            }  
        }
          
    }
    $dpt_sql= "SELECT * FROM `departments` ORDER BY `id` ASC";
    $dpt_result = $conn->query($dpt_sql);
    while($rows=mysqli_fetch_assoc($result)){
        $uid = $rows['id'];
        $full_name = $rows['full_name'];
        $tsc_no = $rows['tsc_no'];
        $dpt = $rows['department'];
        $phone = $rows['phone'];
        $title = $rows['title'];
        $subject1 = $rows['subject1'];
        $subject2 = $rows['subject2'];
        $gender = $rows['gender'];
    }
    $sbj_sql= "SELECT * FROM `subjects` ORDER BY `id` ASC";
    $sbj_result = $conn->query($sbj_sql);
    $subjects = [];
    while($rows=mysqli_fetch_assoc($sbj_result)){
        $subjects[]=$rows['sbj_code'];
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
                            <input type="hidden" name="uid">
                            <div class="form-group p-3">
                                <label for="id_full_name">Full Name:</label>
                                <input type="text" name="full_name" id="id_full_name" placeholder="Name" class="form-control" required value="<?php echo $full_name; ?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_tsc_no">Tsc.No:</label>
                                <input type="text" name="tsc_no" id="id_tsc_no" placeholder="tsc number" class="form-control" value="<?php echo $tsc_no; ?>">
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
                                <label for="id_phone">Phone Number:</label>
                                <input type="tel" name="phone" id="id_phone" placeholder="+2547&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="form-control" required  value="<?php echo $phone; ?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_title">Title:</label>
                                <input type="text" name="title" id="id_title" placeholder="Mr./Mrs./Ms./Dr." class="form-control" required  value="<?php echo $title; ?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_subject1">First Subject:</label>
                                <select name="subject1" id="id_subject1" class="form-control form-select select2bs4" required>
                                    <option value="">Select Subject</option>
                                    <?php for($a=0;$a<count($subjects);$a++){
                                        if($subject1 == $subjects[$a]){ ?>
                                    <option value="<?php echo $subjects[$a];?>" selected><?php echo $subjects[$a];?></option>
                                    <?php }else{?>
                                    <option value="<?php echo $subjects[$a];?>"><?php echo $subjects[$a];?></option>
                                    <?php }}?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_subject2">Second Subject:</label>
                                <select name="subject2" id="id_subject2" class="form-control form-select select2bs4" required>
                                    <option value="">Select Subject2</option>
                                    <?php for($a=0;$a<count($subjects);$a++){
                                        if($subject2 == $subjects[$a]){ ?>
                                    <option value="<?php echo $subjects[$a];?>" selected><?php echo $subjects[$a];?></option>
                                    <?php }else{?>
                                    <option value="<?php echo $subjects[$a];?>"><?php echo $subjects[$a];?></option>
                                    <?php }}?>
                                </select>
                            </div>
                            <div class="form-group p-3">
                                <label for="id_gender">Gender:</label>
                                <select name="gender" id="id_gender" class="form-control form-select select2bs4" required>
                                    <option value="">select gender</option>
                                    <option value="Male" <?php if ($gender == 'Male'){ echo('selected'); }?>>Male</option>
                                    <option value="Female" <?php if ($gender == 'Female'){ echo('selected'); }?>>Female</option>
                                    <option value="Other" <?php if ($gender == 'Other'){ echo('selected'); }?>>Other</option>
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