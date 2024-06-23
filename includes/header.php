<?php 
if(!isset($_SESSION)){
    session_start();
}
?>
<div class = "navbar navbar-default p-2 brand clearfix">
	<div  class = "container-fluid">
		<div class = "navbar-header">
			<a class = "navbar-brand text-white" >Kaiboi Class Attendance I.S.M</a>
		</div>  
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<div class="ms-mx-auto d-flex flex-row gap-2">
		<?php if(isset($_SESSION['clientUsername'])){ ?>
		<?php if(!isset($page)){?>
			<div class="bg-light p-2">
				<h4 class="text-primary bold px-4 text-capitalize"><?php if(isset($_SESSION['clientUsername'])){echo $_SESSION['clientUsername'];} ?></h4>
			</div>
			<div class="P-2">
			<a href="logout.php" class="btn btn-primary">Logout</a>
			</div>
			
		<?php }?>
		<?php }?>
		</div>
		
	</div>
</div>	