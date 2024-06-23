<?php 
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
    }

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<?php include 'includes/meta-tags.php'?>
    <title>Manage Courses</title>
</head>
<body <?php if(!empty($feedback)){?><?php if(!$ok){?> onload="makeToast('error','<?php echo htmlentities($feedback); ?>');"<?php } else if($ok){?> onload="makeToast('success','<?php echo htmlentities($feedback); ?>');"<?php }}?>>
    <?php include 'includes/header.php'?>
    <div class="ts-main-content">
        <?php include('includes/nav.php');?>
        <main class="content-wrapper p-4">
			<h3>Dashboard</h3>
            <div class="row gap-3">
                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body bk-warning text-light bg-warning">
                            <div class="stat-panel text-center">
                            
                                <div class="stat-panel-number h1 bi-book"></div>
                                <div class="stat-panel-title text-uppercase bold">Courses</div>
                            </div>
                        </div>
                        <a href="manage-courses.php" class="block-anchor panel-footer text-center p-3">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body bk-warning text-light bg-primary">
                            <div class="stat-panel text-center">
                            
                                <div class="stat-panel-number h1 bi-book"></div>
                                <div class="stat-panel-title text-uppercase bold">Lecturers</div>
                            </div>
                        </div>
                        <a href="manage-lecturers.php" class="block-anchor panel-footer text-center p-3">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body bk-warning text-light bg-danger">
                            <div class="stat-panel text-center">
                            
                                <div class="stat-panel-number h1 bi-book"></div>
                                <div class="stat-panel-title text-uppercase bold">Classes</div>
                            </div>
                        </div>
                        <a href="manage-classes.php" class="block-anchor panel-footer text-center p-3">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body bk-warning text-light bg-secondary">
                            <div class="stat-panel text-center">
                            
                                <div class="stat-panel-number h1 bi-book"></div>
                                <div class="stat-panel-title text-uppercase bold">Departments</div>
                            </div>
                        </div>
                        <a href="manage-departments.php" class="block-anchor panel-footer text-center p-3">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body bk-warning text-light bg-warning">
                            <div class="stat-panel text-center">
                            
                                <div class="stat-panel-number h1 bi-book"></div>
                                <div class="stat-panel-title text-uppercase bold">Students</div>
                            </div>
                        </div>
                        <a href="manage-students.php" class="block-anchor panel-footer text-center p-3">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body bk-warning text-light bg-primary">
                            <div class="stat-panel text-center">
                            
                                <div class="stat-panel-number h1 bi-book"></div>
                                <div class="stat-panel-title text-uppercase bold">Subjects</div>
                            </div>
                        </div>
                        <a href="manage-subjects.php" class="block-anchor panel-footer text-center p-3">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            
            </div>
        </main>
    </div>
    <?php include('includes/script-tags.php');?>
</body>
<script src = "js/jquery.js"></script>
<script src = "js/bootstrap.js"></script>	
</html>