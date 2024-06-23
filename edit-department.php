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
            $editSql = "SELECT * FROM `departments` WHERE `id`=?";
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
            $dpt_code = isset($_POST['dpt_code'])?$_POST['dpt_code']:'';
            $required[]=$dpt_code;
            $dpt_name = isset($_POST['dpt_name'])?$_POST['dpt_name']:'';
            $required[]=$dpt_name;
            for($i=0;$i<count($required);$i++){
                if(empty($required[$i])){
                    $ok = false;
                    $feedback = "Please fill out all the required fields.";
                    break;
                }
            }
            if($ok){
                $sql= "UPDATE `departments` SET `dpt_code`=?,`dpt_name`=? WHERE `id`=?";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    $feedback='there was an error!';
                    $ok=false;
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt,"sss",$dpt_code,$dpt_name,$pk);
                    if(mysqli_stmt_execute($stmt)){
                        $feedback = "Department has been updated successfully!";
                    }
                }
            }  
        }
          
    }
    while($rows=mysqli_fetch_assoc($result)){
        $uid = $rows['id'];
        $code = $rows['dpt_code'];
        $name = $rows['dpt_name'];
    }
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Edit Departments</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper">
            <div class="container mt-5">
                <form method="post" class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header"><div class="card-title">Edit Department</div></div>
                        <div class="card-body">
                            <input type="hidden" name="uid">
                            <div class="form-group p-3">
                                <label for="id_dpt_code">Department Code:</label>
                                <input type="text" name="dpt_code" id="id_dpt_code" placeholder="department code" class="form-control" required value="<?php echo $code ?>">
                            </div>
                            <div class="form-group p-3">
                                <label for="id_dpt_name">Department Name:</label>
                                <input type="text" name="dpt_name" id="id_dpt_name" placeholder="department name" class="form-control" required value="<?php echo $name ?>">
                            </div>
                            <div class="form-group p-3">
                                <input type="submit" name="submit" value="Update" class="btn btn-danger">
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