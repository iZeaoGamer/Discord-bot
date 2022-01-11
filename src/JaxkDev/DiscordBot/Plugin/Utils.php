<?php
/*
 * DiscordBot, PocketMine-MP Plugin.
 *
 * Licensed under the Open Software License version 3.0 (OSL-3.0)
 * Copyright (C) 2020-2021 JaxkDev
 *
 * Twitter :: @JaxkDev
 * Discord :: JaxkDev#2698
 * Email   :: JaxkDev@gmail.com
 */

namespace JaxkDev\DiscordBot\Plugin;

abstract class Utils
{
     /** 
     * Converts a string to a regex.
     * 
     * Returns null if the given string's regex isn't valid.
     * 
     * @param string $string
     * @return string|null
     */
    public static function toRegex(string $string): ?string
    {
        $pattern = '/' . $string . "test" . '/i'; //use this as a test replacement in the future.
        $replacement = '/' . $string . '/i';
        $name = preg_replace($pattern, $replacement, $string);
        return $name;
    }

    /** 
     * Converts timestamps to seconds.
     * @param int $timestamp
     * @return int
     */
    public static function toSeconds(int $timestamp): int
    {
        $timediff = time() - $timestamp;
        return $timediff;
    }

    /** Converts seconds to years.
     * @param int $seconds
     * @return int
     */
    public static function toYears(int $seconds): int
    {
        if ($seconds >= 31557600) {
            $unit = floor($seconds / 31557600);
        } else {
            $unit = 0;
        }
        return $unit;
    }

    /** Converts years to seconds.
     * @param int $year
     * @return int
     */
    public static function fromYears(int $year): int
    {
        $seconds = $year * 3155760000;
        return $seconds;
    }

    /** Converts seconds to months.
     * @param int $seconds
     * @return int
     */
    public static function toMonths(int $seconds): int
    {
        if ($seconds >= 2628288) {
            $unit = floor($seconds / 2628288);
        } else {
            $unit = 0;
        }
        return $unit;
    }

    /** Converts months to seconds.
     * @param int $month
     * @return int
     */
    public static function fromMonths(int $month): int
    {
        $seconds = $month * 2628288;
        return $seconds;
    }

    /** Converts seconds to weeks.
     * Keep in mind - 4 weeks means a month! So if you're planning to make a timing a month, it's recommended you put 1 month instead of 4 weeks.
     * @param int $seconds
     * @return int
     */
    public static function toWeeks(int $seconds): int
    {
        if ($seconds >= 604800) {
            $unit = floor($seconds / 604800);
        } else {
            $unit = 0;
        }
        return $unit;
    }

    /** Converts weeks to seconds.
     * @param int $week
     * @return int
     */
    public static function fromWeeks(int $week): int
    {
        $seconds = $week * 604800;
        return $seconds;
    }

    /** Converts seconds to days.
     * @param int $seconds
     * @return int
     */
    public static function toDays(int $seconds): int
    {
        if ($seconds >= 86400) {
            $unit = floor($seconds / 86400);
        } else {
            $unit = 0;
        }
        return $unit;
    }

    /** Converts days to seconds.
     * @param int $day
     * @return int
     */
    public static function fromDays(int $day): int
    {
        $seconds = $day * 86400;
        return $seconds;
    }

    /** Converts seconds to hours.
     * @param int $seconds
     * @return int
     */
    public static function toHours(int $seconds): int
    {
        if ($seconds >= 3600) {
            $unit = floor($seconds / 3600);
        } else {
            $unit = 0;
        }
        return $unit;
    }

    /** Converts hours to seconds.
     * @param int $hour
     * @return int
     */
    public static function fromHours(int $hour): int
    {
        $seconds = $hour * 3600;
        return $seconds;
    }

    /** Converts seconds to minutes.
     * @param int $seconds
     * @return int
     */
    public static function toMinutes(int $seconds): int
    {
        if ($seconds >= 60) {
            $unit = floor($seconds / 60);
        } else {
            $unit = 0;
        }
        return $unit;
    }

    /** Converts minutes to seconds.
     * @param int $minute
     * @return int
     */
    public static function fromMinutes(int $minute): int
    {
        $seconds = $minute * 60;
        return $seconds;
    }

    public static function getDiscordSnowflakeTimestamp(string $snowflake): int
    {
        return intval(floor(((intval($snowflake) >> 22) + 1420070400000) / 1000));
    }

    /** Checks a discord snowflake by verifying the timestamp at when it was created. */
    public static function validDiscordSnowflake(string $snowflake): bool
    {
        $len = strlen($snowflake);
        if ($len < 17 or $len > 18) return false;
        $timestamp = self::getDiscordSnowflakeTimestamp($snowflake);
        if ($timestamp > time() + 86400 or $timestamp <= 1420070400) return false; //+86400 (24h for any timezone problems)
        return true;
    }
    public static function validUserDiscriminator(string $discriminator): bool
    {
        return strlen($discriminator) === 4;
    }
    /**
     * Converts objects to arrays.
     * @param mixed $d
     * @return mixed
     */
    public static function objectToArray(mixed $d): mixed
    {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map([Utils::class, "objectToArray"], $d);
        } else {
            return $d;
        }
    }
}
