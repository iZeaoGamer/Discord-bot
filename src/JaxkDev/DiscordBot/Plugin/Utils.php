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

     /** Converts seconds to years.
     * @param int $seconds
     * @return int
     */
    static function toYears(int $seconds): int
    {
        if($seconds >= 31557600){
        $unit = floor($seconds / 31557600);
        }else{
            $unit = 0;
        }
        return $unit;
    }

    /** Converts years to seconds.
     * @param int $year
     * @return int
     */
    static function fromYears(int $year): int
    {
        $seconds = $year * 3155760000;
        return $seconds;
    }

    /** Converts seconds to months.
     * @param int $seconds
     * @return int
     */
    static function toMonths(int $seconds): int
    {
        if($seconds >= 2628288){
        $unit = floor($seconds / 2628288);
        }else{
            $unit = 0;
        }
        return $unit;
    }

    /** Converts months to seconds.
     * @param int $month
     * @return int
     */
    static function fromMonths(int $month): int
    {
        $seconds = $month * 2628288;
        return $seconds;
    }

    /** Converts seconds to weeks.
     * Keep in mind - 4 weeks means a month! So if you're planning to make a timing a month, it's recommended you put 1 month instead of 4 weeks.
     * @param int $seconds
     * @return int
     */
    static function toWeeks(int $seconds): int
    {
        if($seconds >= 604800){
        $unit = floor($seconds / 604800);
        }else{
            $unit = 0;
        }
        return $unit;
    }

    /** Converts weeks to seconds.
     * @param int $week
     * @return int
     */
    static function fromWeeks(int $week): int
    {
        $seconds = $week * 604800;
        return $seconds;
    }

    /** Converts seconds to days.
     * @param int $seconds
     * @return int
     */
    static function toDays(int $seconds): int
    {
        if($seconds >= 86400){
        $unit = floor($seconds / 86400);
        }else{
            $unit = 0;
        }
        return $unit;
    }

    /** Converts days to seconds.
     * @param int $day
     * @return int
     */
    static function fromDays(int $day): int
    {
        $seconds = $day * 86400;
        return $seconds;
    }

    /** Converts seconds to hours.
     * @param int $seconds
     * @return int
     */
    static function toHours(int $seconds): int
    {
        if($seconds >= 3600){
        $unit = floor($seconds / 3600);
        }else{
            $unit = 0;
        }
        return $unit;
    }

    /** Converts hours to seconds.
     * @param int $hour
     * @return int
     */
    static function fromHours(int $hour): int
    {
        $seconds = $hour * 3600;
        return $seconds;
    }

    /** Converts seconds to minutes.
     * @param int $seconds
     * @return int
     */
    static function toMinutes(int $seconds): int
    {
        if($seconds >= 60){
        $unit = floor($seconds / 60);
        }else{
            $unit = 0;
        }
        return $unit;
    }

    /** Converts minutes to seconds.
     * @param int $minute
     * @return int
     */
    static function fromMinutes(int $minute): int
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
    public static function validUserDiscriminator(string $discriminator): bool{
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
