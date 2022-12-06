<?php
    class Renderer {

        public static function trophy(int $rank): void {
            $outerStyle = sprintf('color: %s;', Rank::get($rank)->get_color());

            $trophyClass = 'fa-solid fa-trophy';
            if ($rank < 3) {
                $trophyClass .= ' fa-beat';
            }

            $trophyStyle = '';
            if ($rank < 3) {
                $trophyStyle .= '--fa-animation-duration: 4s;';
            }

            ?>
                <span class='fa-layers fa-fw fa-2x' style='<?php echo($outerStyle); ?>'>
                    <i class='<?php echo($trophyClass); ?>' style='<?php echo($trophyStyle); ?>'></i>
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
                <span class='fa-layers fa-fw fa-2x' style='color: <?php echo(Rank::GOLD->get_color()); ?>;'>
                    <i class='fa-solid fa-coins'></i>
                    <span class='fa-layers-text' data-fa-transform='shrink-8 down-3' style='font-weight:900'><?php echo($score); ?></span>
                </span>
            <?php
        }

        public static function star(array $memberDay): void {
            $rank = match (count($memberDay)) {
                2 => Rank::GOLD,
                1 => Rank::SILVER,
                default => RANK::OTHER
            };

            ?>
                <i class='fa-solid fa-star fa-fw' style='color:<?php echo($rank->get_color()); ?>;'></i>
            <?php
        }

        public static function stars(int $stars): void {
            ?>
                <span class='fa-layers fa-fw fa-2x' style='color: <?php echo(Rank::GOLD->get_color()); ?>;'>
                    <i class='fa-solid fa-star'></i>
                    <span class='fa-layers-text' data-fa-transform='shrink-8 down-3' style='font-weight:900'><?php echo($stars); ?></span>
                </span>
            <?php
        }

        public static function datetime(int $utcUnixTs): void {
            if ($utcUnixTs === 0) {
                echo('-');
                return;
            }

            $dateTime = new DateTime(sprintf('@%d', $utcUnixTs));
            $dateTime->setTimezone(new DateTimeZone('Europe/Berlin'));

            echo(sprintf('%s Uhr', $dateTime->format('d.m.Y H:i')));
        }

    }
?>