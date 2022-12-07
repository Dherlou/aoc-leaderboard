<?php
    class Renderer {

        public static function trophy(int $rank): void {
            $outer_style = sprintf('color: %s;', Rank::get($rank)->get_color());

            $trophy_class = 'fa-solid fa-trophy';
            if ($rank < 3) {
                $trophy_class .= ' fa-beat';
            }

            $trophy_style = '';
            if ($rank < 3) {
                $trophy_style .= '--fa-animation-duration: 4s;';
            }

            ?>
                <span class='fa-layers fa-fw fa-2x' style='<?php echo($outer_style); ?>'>
                    <i class='<?php echo($trophy_class); ?>' style='<?php echo($trophy_style); ?>'></i>
                    <span class='fa-layers-text' data-fa-transform='shrink-8 down-3' style='font-weight:900'><?php echo(1+$rank); ?></span>
                </span>
            <?php
        }

        public static function user(array $member): void {
            ?>
                <h5 class='mb-0'><?php echo($member['name'] ?? 'Anonymer Benutzer'); ?></h5>
                <p class='text-muted mb-0'>#<?php echo($member['id']); ?></p>
            <?php
        }

        public static function score(int $score): void {
            ?>
                <span class='fa-layers fix fa-fw fa-2x' style='color: <?php echo(Rank::GOLD->get_color()); ?>;'>
                    <i class='fa-solid fa-coins'></i>
                    <span class='fa-layers-text fix text-dark' data-fa-transform='shrink-8 down-3' style='font-weight:900'><?php echo($score); ?></span>
                </span>
            <?php
        }

        public static function star(int $day, array $member_days, array $day_local_star_tss): void {
            $completed = match (count($member_days[$day] ?? [])) {
                2 => Rank::GOLD,
                1 => Rank::SILVER,
                default => RANK::OTHER
            };

            if ($completed === Rank::GOLD) {
                $day_local_star_ts_idx = array_search($member_days[$day][2]['get_star_ts'], $day_local_star_tss[$day]);
                
                ?>
                    <span class='fa-layers fix fa-fw fa-xl' style='color: <?php echo($completed->get_color()); ?>;'>
                        <i class='fa-solid fa-star'></i>
                        <span class='fa-layers-text fix text-dark' data-fa-transform='shrink-8 down-3' style='font-weight:600'>
                            <?php echo(1+$day_local_star_ts_idx); ?>
                        </span>
                    </span>
                <?php
            } else {
                ?>
                    <i class='fa-solid fa-star fa-fw <?php if ($completed === Rank::SILVER) echo('fa-lg'); ?>' style='color: <?php echo($completed->get_color()); ?>;'></i>
                <?php
            }
        }

        public static function stars(int $stars): void {
            ?>
                <span class='fa-layers fix fa-fw fa-2x' style='color: <?php echo(Rank::GOLD->get_color()); ?>;'>
                    <i class='fa-solid fa-star'></i>
                    <span class='fa-layers-text fix text-dark' data-fa-transform='shrink-8 down-3' style='font-weight:900'><?php echo($stars); ?></span>
                </span>
            <?php
        }

        public static function datetime(int $utc_unix_ts): void {
            if ($utc_unix_ts === 0) {
                echo('-');
                return;
            }

            $dateTime = new DateTime(sprintf('@%d', $utc_unix_ts));
            $dateTime->setTimezone(new DateTimeZone('Europe/Berlin'));

            echo(sprintf('%s Uhr', $dateTime->format('d.m.Y H:i')));
        }

    }
?>