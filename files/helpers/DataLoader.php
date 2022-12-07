<?php
    class DataLoader {
        
		public static function load(string $data_file): array {
			if (file_exists($data_file) === false || self::get_next_update_time($data_file) <= time()) {
				self::download($data_file);
			}

            $data = json_decode(file_get_contents($data_file), true);
            $data = self::sort_members($data);
			return $data;
		}

		public static function get_last_update_time(string $data_file): int {
			return filemtime($data_file);
        }
        
		public static function get_next_update_time(string $data_file): int {
			# next update possible after at least 15 minutes
			return self::get_last_update_time($data_file) + 15*60;
		}

		public static function get_days_local_star_tss(array $aoc_data): array {
			$days_local_star_tss = array_map(
				function(int $day) use ($aoc_data): array {
					$day_local_star_tss = array_map(
						fn(array $member) => $member['completion_day_level'][$day][2]['get_star_ts'] ?? PHP_INT_MAX,
						$aoc_data['members']
					);
					sort($day_local_star_tss);
					return $day_local_star_tss;
				},
				range(1, 24)
			);
			array_unshift($days_local_star_tss, '0-based to effectively 1-based');
			return $days_local_star_tss;
		}
		
		private static function download(string $data_file): void {
			$data_url = sprintf(
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

			file_put_contents($data_file, file_get_contents($data_url, false, $ctx));
		}
		
		private static function sort_members(array $data): array {
			usort($data['members'], function ($member1, $member2) {
				$local_score_diff = $member2['local_score'] - $member1['local_score'];
				$last_star_ts = $member1['last_star_ts'] - $member2['last_star_ts'];
				return $local_score_diff ?: $last_star_ts;
			});
			return $data;
		}
        
    }
?>