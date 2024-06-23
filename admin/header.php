<head>
    <link rel="stylesheet" href="../css/style.css">
</head>
<nav style = "background-color:var(--bg-green);color: var(--font-white);" class = "navbar navbar-default ">
		<div  class = "container-fluid">
			<div class = "navbar-header">
				<a class = "navbar-brand text-white" >Azam Hotel Management System</a>
			</div>
			<ul class = "nav navbar-nav pull-right ">
				<li class = "dropdown">
					<a class="dropdown-toggle text-white" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class = "glyphicon glyphicon-user"></i> <?php echo $name;?></a>
					<ul class="dropdown-menu">
						<li><a href="logout.php"><i class = "glyphicon glyphicon-off"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>