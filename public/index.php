<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Scrapper</title>
	<style>
		body {
			font-family: "sans-serif";
			margin: 0;
			padding: 0;
			background: #eee;
		}
		#wrap {
			width: 750px;
			padding: 15px;
			background: white;
			margin: 50px auto;
		}
		#wrap h2,
		#wrap nav {
			text-align: center;
		}
		#wrap nav {
			padding-bottom: 10px;
			border-bottom: 1px dotted #ccc;
		}
		.row {
			overflow: auto;
  			zoom: 1;
  			position: relative;
		}
		.col {
			position: relative;
			width: 50%;
			float: left;
		}
	</style>
</head>
<body>
	<div id="wrap">
		<h2>Web Scrapper</h2>
		<nav>
			<a href="./?action=show">Show</a> /
			<a href="./?action=run">Run</a>
		</nav>
		
		<div class="row">
		<?php if(isset($_GET['action']) && 'run' == $_GET['action']) : 
			echo 'running';
			$number = start_scraping();
			print_r(get_object_vars($number[0]));
		?>
			<h3><?php //echo $number ?> new resume added!</h3>
		<?php elseif(isset($_GET['action']) && 'show' == $_GET['action']) : ?>
		<?php elseif(isset($_GET['action']) && 'view' == $_GET['action']) : ?>
		<?php endif; ?>
		</div>

	</div>
</body>
</html>