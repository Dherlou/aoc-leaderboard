<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>AdventOfCode <?php echo(getenv('AOC_YEAR')); ?> - RZUW Leaderboard</title>
	<link rel="icon" type="image/png" href="assets/imgs/favicon.png">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/brands.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/solid.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
    <?php
		# main
		$dataFile = "data.json";
		if (file_exists($dataFile) === false || get_next_update_time($dataFile) <= time()) {
			download_data($dataFile);
		}
        $aoc_data = load_data($dataFile);
		
		# caching functions
		function download_data(string $dataFile): void {
			$dataURL = sprintf(
				"https://adventofcode.com/%u/leaderboard/private/view/%u.json",
				getenv('AOC_YEAR'),
				getenv('AOC_LEADERBOARD_ID')
			);
			$headers = sprintf('Cookie: session=%s', getenv('AOC_SESSION_COOKIE'));
			$ctx = stream_context_create([
				'http' => [
				  'method' => "GET",
				  'header' => $headers
				]
			]);

			file_put_contents($dataFile, file_get_contents($dataURL, false, $ctx));
		}
		function load_data(string $dataFile): array {
			$data = json_decode(file_get_contents($dataFile), true);
			usort($data['members'], function ($member1, $member2) {
				$localScoreDiff = $member2['local_score'] - $member1['local_score'];
				$lastStarTs = $member1['last_star_ts'] - $member2['last_star_ts'];
				return $localScoreDiff ?: $lastStarTs;
			});
			return $data;
		}

		# date functions
		function get_last_update_time(string $dataFile): int {
			return filemtime($dataFile);
		}
		function get_next_update_time(string $dataFile): int {
			# next update possible after at least 15 minutes
			return get_last_update_time($dataFile) + 15*60;
		}

		# render functions
		function get_trophy_color(int $place): ?string {
			switch ($place) {
				case 0:
					return '#ffd700';
				case 1:
					return '#c0c0c0';
				case 2:
					return '#cd7f32';
				default:
					return '#b5a642';
			}
		}
		function render_trophy(int $place): void {
			$outerStyle = sprintf('color: %s;', get_trophy_color($place));

			$trophyClass = 'fa-solid fa-trophy';
			if ($place < 3) {
				$trophyClass .= ' fa-beat';
			}

			$trophyStyle = '';
			if ($place < 3) {
				$trophyStyle .= '--fa-animation-duration: 4s;';
			}
?>
			<span class="fa-layers fa-fw fa-2x" style="<?php echo($outerStyle); ?>">
				<i class="<?php echo($trophyClass); ?>" style="<?php echo($trophyStyle); ?>"></i>
				<span class="fa-layers-text" data-fa-transform="shrink-8 down-3" style="font-weight:900"><?php echo(1+$place); ?></span>
			</span>
<?php
		}
		function render_user(array $member): void {
?>
			<h5 class="mb-0"><?php echo($member['name'] ?? 'Anonymer Benutzer'); ?></h5>
			<p class="text-muted mb-0">#<?php echo($member['id']); ?></p>
<?php
		}
		function render_score(int $score): void {
?>
			<span class="fa-layers fa-fw fa-2x" style="color: #ffd700;">
				<i class="fa-solid fa-coins"></i>
				<span class="fa-layers-text" data-fa-transform="shrink-8 down-3" style="font-weight:900"><?php echo($score); ?></span>
			</span>
<?php
		}
		function render_stars(int $stars): void {
?>
			<span class="fa-layers fa-fw fa-2x" style="color: #ffd700;">
				<i class="fa-solid fa-star"></i>
				<span class="fa-layers-text" data-fa-transform="shrink-8 down-3" style="font-weight:900"><?php echo($stars); ?></span>
			</span>
<?php
		}
		function render_datetime(int $utcUnixTs): void {
			if ($utcUnixTs === 0) {
				echo('-');
				return;
			}

			$dateTime = new DateTime(sprintf('@%d', $utcUnixTs));
			$dateTime->setTimezone(new DateTimeZone('Europe/Berlin'));
			echo(sprintf('%s Uhr', $dateTime->format('d.m.Y H:i')));
		}
?>
    <section class="main-content">
		<div class="container">
			<div class="clearfix">
				<div class="float-start">
					<h1>Rechenzentrum Universität Würzburg</h1>
					<h2>Advent of Code <?php echo($aoc_data['event']); ?></h3>
				</div>
				<div class="float-end">
					<img src="assets/imgs/logo.png"/>
				</div>
			</div>

			<h4>Rangliste</h4>

			<table class="table">
				<thead>
					<tr>
						<th><div class="d-flex align-items-center">Benutzer</div></th>
						<th>
							<div class="d-flex flex-column align-items-center justify-content-center">
								<span>Lokaler Punktestand</span>
								<small>(1. Platz = 1*#Benutzer, ..., Letzter Platz = 1)</small>
							</div>
						</th>
						<th>
							<div class="d-flex flex-column align-items-center justify-content-center">
								<span>Globaler Punktestand</span>
								<small>(1. Platz = 100, ..., 100. Platz = 0)</small>
							</div>
						</th>
						<th><div class="d-flex align-items-center justify-content-center">Sterne</div></th>
						<th><div class="d-flex align-items-center justify-content-center">Letzter Stern erhalten</div></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aoc_data['members'] as $place => $member) { ?>
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="circle-img circle-img--small ms-2 me-3">
									<?php render_trophy($place); ?>
								</div>
								<div class="user-info__basic">
									<?php render_user($member); ?>
								</div>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center justify-content-center">
								<?php render_score($member['local_score']); ?>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center justify-content-center">
								<?php render_score($member['global_score']); ?>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center justify-content-center">
								<?php render_stars($member['stars']); ?>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center justify-content-center">
								<?php render_datetime($member['last_star_ts']); ?>
							</div>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<div>
				<span class="float-start">
					<small>Letzte Aktualisierung: <?php render_datetime(get_last_update_time($dataFile)); ?></small>
				</span>
				<span class="float-end">
					<small>Nächste Aktualisierung: <?php render_datetime(get_next_update_time($dataFile)); ?></small>
				</span>
			</div>
		</div>
	</section>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <?php
        #echo("<pre>" . json_encode($aoc_data, JSON_PRETTY_PRINT) . "<pre/>");
    ?>
</body>
</html>