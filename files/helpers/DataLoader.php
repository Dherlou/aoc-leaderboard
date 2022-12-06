<?php
    class DataLoader {
        
		public static function load(string $dataFile): array {
			if (file_exists($dataFile) === false || self::get_next_update_time($dataFile) <= time()) {
				self::download($dataFile);
			}

            $data = json_decode(file_get_contents($dataFile), true);
            $data = self::sort_members($data);
			return $data;
		}

		public static function get_last_update_time(string $dataFile): int {
			return filemtime($dataFile);
        }
        
		public static function get_next_update_time(string $dataFile): int {
			# next update possible after at least 15 minutes
			return self::get_last_update_time($dataFile) + 15*60;
		}
		
		private static function download(string $dataFile): void {
			$dataURL = sprintf(
				'https://adventofcode.com/%u/leaderboard/private/view/%u.json',
				getenv('AOC_YEAR') ?: date('Y'),
				getenv('AOC_LEADERBOARD_ID')
			);
			$headers = sprintf('Cookie: session=%s', getenv('AOC_SESSION_COOKIE'));
			$ctx = stream_context_create([
				'http' => [
				  'method' => 'GET',
				  'header' => $headers
				]
			]);

			file_put_contents($dataFile, file_get_contents($dataURL, false, $ctx));
		}
		
		private static function sort_members(array $data): array {
			usort($data['members'], function ($member1, $member2) {
				$localScoreDiff = $member2['local_score'] - $member1['local_score'];
				$lastStarTs = $member1['last_star_ts'] - $member2['last_star_ts'];
				return $localScoreDiff ?: $lastStarTs;
			});
			return $data;
		}
        
    }
?>