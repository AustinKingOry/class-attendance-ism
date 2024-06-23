<?php 
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
    }
    include 'includes/config.php';
    $ok=true;
    if($ok){
        $sql= "SELECT * FROM `students` ORDER BY `id` ASC";
        $result = $conn->query($sql);
    }
    if(isset($_GET['action'])){
        $action = isset($_GET['action'])?$_GET['action']:'';
        $id = isset($_GET['id'])?$_GET['id']:'';
        if($action=="delete" && ($id)!=''){
            $delSql = "DELETE FROM `students` WHERE `id`=?";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$delSql)){
                $feedback='SQL Statement Failed!';
                $ok=false;
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt,"s",$id);
                if(mysqli_stmt_execute($stmt)){
                    $feedback = "Student has been deleted successfully!";
                    header("location: manage-students.php");
                }
                // $result = mysqli_stmt_get_result($stmt);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Manage Students</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title">Manage Students</div>
                        <div class="card-tools">
                            <a href="add-students.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Student</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="display table table-striped table-bordered table-hover" id="main-table">
                            <thead>
                                <th>#</th>
                                <th>Reg No.</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Department</th>
                                <th>Gender</th>
                                <th>Date Added</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            <?php 
                            if(mysqli_num_rows($result)>0){
                                $autonum = 0;
                                while($rows=mysqli_fetch_assoc($result)){
                                    $autonum++;
                                    $uid = $rows['id'];
                                    $reg_no = $rows['reg_no'];
                                    $name = $rows['full_name'];
                                    $class_code = $rows['class_code'];
                                    $dpt = $rows['department'];
                                    $class = $rows['class_name'];
                                    $gender = $rows['gender'];
                                    $created = $rows['created'];
                                ?>
                                <tr>
                                    <td><?php echo $autonum;?></td>
                                    <td><?php echo $reg_no;?></td>
                                    <td><?php echo $name;?></td>
                                    <td><?php echo $class;?></td>
                                    <td><?php echo $dpt;?></td>
                                    <td><?php echo $gender;?></td>
                                    <td><?php echo $created;?></td>
                                    <td>
                                        <button type="button" class="btn btn-tool btn-danger bi-trash2" onclick="confirmDeletion('manage-students.php?action=delete&id=<?php echo $uid;?>')"></button>
                                        <a href="edit-student.php?action=edit&id=<?php echo $uid;?>" class="btn btn-tool btn-primary bi-pencil-square"></a>
                                    </td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include('includes/script-tags.php');?>
</body>
</html>