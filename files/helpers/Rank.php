<?php
    enum Rank
    {
        case GOLD;
        case SILVER;
        case BRONZE;
        case OTHER;

        public function get_color(): string {
            return match ($this) {
                self::GOLD => '#ffd700',   
                self::SILVER => '#c0c0c0',   
                self::BRONZE => '#cd7f32',
                default => '#333333'
            };
        }

        public static function get(int $rank): Rank {
            return match ($rank) {
                0 => self::GOLD,
                1 => self::SILVER,
                2 => self::BRONZE,
                default => self::OTHER
            };
        }
    }
?>