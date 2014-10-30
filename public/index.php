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
		a {
			color: #2890D1;
		}
		a:hover {
			color: darkorange;
		}
		#wrap {
			width: 750px;
			padding: 15px;
			background: white;
			margin: 50px auto;
		}
		#wrap h2,		
		#wrap h3,		
		#wrap nav {
			text-align: center;
		}
		#wrap nav {
			padding-bottom: 10px;
			border-bottom: 1px dotted #ccc;
		}
		#wrap h4 {
			margin: 0;
		}
		#wrap p {
			font-size: 15px;
			margin: 0;
			padding-bottom: 7px;
			text-align: center;
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

		/* resume style*/
		div#resume { color: #444}
		div#resume h1, div#resume h2, div#resume h3, div#resume h4, div#resume h5, div#resume h6 {
			text-align: left;
		}
		div#resume p {
			text-align: left;
			padding-bottom: 10px;
			line-height: 1.5em;
		}

		div#resume h1 {font-size: 28px;}
		div#resume h2 {font-size: 18px;}
		div#resume p.locality {font-size: 20px;}
		div#resume div#basic_info_cell div.separator-hyphen{display: none}
		div.section_title {
			border-top: 2px solid #CCC;
			border-bottom: 1px solid #CCC;
			margin-bottom: 8px;
			padding-bottom: 15px;
			padding-top: 15px;
		}
		div#resume p.title {

		}
		.separator-hyphen {
			display: none;
		}
		div#resume p.work_title {
			font-weight: bold;
			font-size: 18px;
			padding: 15px 0 10px;
		}
		div#resume .inline-block {
			display: inline-block;
		}
		div#resume{}
		.pagination {
			background: #FFF5E4;
			padding: 10px 0;
			text-align: center;
		}
		.pagination a {
			border: 1px dotted #ccc;
			background: #eee;
			padding: 5px;
		}
		.pagination a:hover {
			background: #2890D1;
			color: white;
		}
	</style>
</head>
<body>
	<div id="wrap">
		<h2>Indeed Resume Search</h2>
		<nav>
			<a href="./?action=show">Show All</a>
			<!-- <a href="./?action=run">Run Search</a> -->
		</nav>
		
		<div class="row">
			<!-- run -->
			<?php if(isset($_GET['action']) && 'run' == $_GET['action']) : 
				// $number = start_scraping();
			?>
				<h3><?php echo $number ?> new resume added!</h3>

			<!-- view single -->
			<?php elseif(isset($_GET['action']) && 'view' == $_GET['action']) : 
				$id = (int)$_GET['id'];
				$cv = Content::find($id);
			?>
				<?php echo $cv->resume; ?>

			<!-- archives -->
			<?php elseif(isset($_GET['action']) && 'archive' == $_GET['action']) : 
				$id = (int)$_GET['id'];
				$content= Content::find($id);
				if($content->archived()) :
			?>
				<h3>Resume Archived! <a href="./?action=show">Go Back</a></h3>
				<?php endif; ?>

			<!-- show all -->
			<?php else:
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$show = isset($_GET['show']) ? (int)$_GET['show'] : 20;
				$page --;
				$args = [
					'show' => $show,
					'page' => $page
				];
				$cvs = Content::get($args);
				$current_date = null;
			?>
				<table border="0" cellpadding="5">
				<?php foreach ($cvs as $cv) : 
					$cv_date = date("d/m/Y", strtotime($cv->created_at));
					if(!$current_date || $current_date != $cv_date) {
						$current_date = $cv_date; 
					?>
					<tr><td colspan="3"><h4>Added <?php echo $current_date ?></h4></td>
					<?php } ?>
					<tr>
						<td><a href="<?php echo $cv->link; ?>"><?php echo $cv->name; ?></a></td>
						<td><a href="?action=view&amp;id=<?php echo $cv->id; ?>">CV</a></td>
						<td><a href="?action=archive&amp;id=<?php echo $cv->id; ?>">Archive</a></td>
					</tr>
				<?php endforeach; ?>
				</table>
			<?php endif; ?>

			<div class="pagination">
			<?php  
				$total = Content::get(null, true);
				$show = isset($_GET['show']) ? (int)$_GET['show'] : 20;
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$page--;
				$total_page = (int) ($total/$show) + 1;
			?>
				<p>Page <?php echo ($page+1);?> of <?php echo $total_page ?></p>
				<div>
					<?php for($p=0; $p<$total_page; $p++) : ?>
						<?php if($p == $page) : ?>
							<a href="javascript: return 0;">
						<?php else: ?>
							<a href="?action=show&amp;show=<?php echo $show ?>&amp;page=<?php echo ($p+1) ?>">
						<?php endif; ?>
							<?php echo $p+1 ?></a>
					<?php endfor; ?>
				</div>

			</div>
		</div>

	</div>
</body>
</html>