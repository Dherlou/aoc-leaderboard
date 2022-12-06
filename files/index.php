<?php
	spl_autoload_register(function ($className) {
		include sprintf('helpers/%s.php', $className);
	});

	$dataFile = 'data.json';
	$aoc_data = DataLoader::load($dataFile);
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title><?php echo(getenv('AOC_LEADERBOARD_TITLE') ?: 'Advent of Code Private Leaderboard'); ?></title>
	<link rel='icon' type='image/png' href='<?php echo(getenv('AOC_LEADERBOARD_FAVICON') ?: 'assets/imgs/favicon.png'); ?>'>
    <link rel='stylesheet' type='text/css' href='assets/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href='assets/css/fontawesome.min.css'>
    <link rel='stylesheet' type='text/css' href='assets/css/brands.min.css'>
    <link rel='stylesheet' type='text/css' href='assets/css/solid.min.css'>
	<link rel='stylesheet' type='text/css' href='assets/css/style.css'>
</head>
<body>
    <section class='main-content'>
		<div class='container'>
			<div class='clearfix'>
				<div class='float-start'>
					<h1><?php echo(getenv('AOC_LEADERBOARD_TITLE') ?: 'Advent of Code Private Leaderboard'); ?></h1>
					<h2>Advent of Code <?php echo($aoc_data['event']); ?></h3>
				</div>
				<div class='float-end'>
					<img src='<?php echo(getenv('AOC_LEADERBOARD_LOGO') ?: 'assets/imgs/logo.png'); ?>'/>
				</div>
			</div>

			<h4>Rangliste</h4>

			<table class='table'>
				<thead>
					<tr>
						<th><div class='d-flex align-items-center'>Benutzer</div></th>
						<th>
							<div class='d-flex flex-column align-items-center justify-content-center'>
								<span>Lokaler Punktestand</span>
								<small>(1. Platz = 1*#Benutzer, ..., Letzter Platz = 1)</small>
							</div>
						</th>
						<?php if (getenv('AOC_NO_GLOBAL_SCORE') === null) { ?>
							<th>
								<div class='d-flex flex-column align-items-center justify-content-center'>
									<span>Globaler Punktestand</span>
									<small>(1. Platz = 100, ..., 100. Platz = 0)</small>
								</div>
							</th>
						<?php } ?>
						<th><div class='d-flex align-items-center justify-content-center'>Sterne</div></th>
						<th><div class='d-flex align-items-center justify-content-center'>Letzter Stern erhalten</div></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aoc_data['members'] as $rank => $member) { ?>
					<tr>
						<td>
							<div class='d-flex align-items-center'>
								<div class='circle-img circle-img--small ms-2 me-3'>
									<?php Renderer::trophy($rank); ?>
								</div>
								<div class='user-info__basic'>
									<?php Renderer::user($member); ?>
								</div>
							</div>
						</td>
						<td>
							<div class='d-flex align-items-center justify-content-center'>
								<?php Renderer::score($member['local_score']); ?>
							</div>
						</td>
						<?php if (getenv('AOC_NO_GLOBAL_SCORE') === null) { ?>
							<td>
								<div class='d-flex align-items-center justify-content-center'>
									<?php Renderer::score($member['global_score']); ?>
								</div>
							</td>
						<?php } ?>
						<td>
							<div class='d-flex align-items-center justify-content-center'>
								<?php Renderer::stars($member['stars']); ?>
							</div>
						</td>
						<td>
							<div class='d-flex align-items-center justify-content-center'>
								<?php Renderer::datetime($member['last_star_ts']); ?>
							</div>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<h4 class="mt-5">Tageswertung</h4>
			
			<table class='table'>
				<thead>
					<tr>
						<th><div class='d-flex align-items-center'>Benutzer</div></th>
						<?php for ($i = 1; $i <= 24; $i++) { ?>
							<th>
								<div class='d-flex align-items-center justify-content-center'>
									<?php echo($i); ?>
								</div>
							</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aoc_data['members'] as $rank => $member) { ?>
					<tr>
						<td>
							<div class='d-flex align-items-center'>
								<div class='circle-img circle-img--small ms-2 me-3'>
									<?php Renderer::trophy($rank); ?>
								</div>
								<div class='user-info__basic'>
									<?php Renderer::user($member); ?>
								</div>
							</div>
						</td>
						<?php for ($i = 1; $i <= 24; $i++) { ?>
						<td>
							<div class='d-flex align-items-center justify-content-center'>
								<?php Renderer::star($member['completion_day_level'][$i] ?? []); ?>
							</div>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<div>
				<span class='float-start'>
					<small>Letzte Aktualisierung: 
						<?php Renderer::datetime(DataLoader::get_last_update_time($dataFile)); ?>
					</small>
				</span>
				<span class='float-end'>
					<small>Nächste Aktualisierung:
						<?php Renderer::datetime(DataLoader::get_next_update_time($dataFile)); ?>
					</small>
				</span>
			</div>
		</div>
	</section>
	
	<script src='assets/js/jquery.min.js'></script>
	<script src='assets/js/popper.min.js'></script>
    <script src='assets/js/bootstrap.min.js'></script>

    <?php
		if (getenv('DEV_MODE')) {
			?>
				<pre><?php echo(json_encode($aoc_data, JSON_PRETTY_PRINT)); ?></pre>
    		<?php
		}
	?>
</body>
</html>