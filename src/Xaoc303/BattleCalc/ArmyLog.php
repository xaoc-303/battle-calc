<?php namespace Xaoc303\BattleCalc;

/**
 * Class ArmyLog
 * @package Xaoc303\BattleCalc
 */
class ArmyLog
{
    /**
     * colorize
     */
    public static function colorize(Army $army)
    {
        $units = $army->getUnits();
        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            self::colorize1($units, $i);
            self::colorize2($units, $i);
        }
        $army->setUnits($units);
    }

    /**
     * colorize1
     *
     * @param array $units
     * @param int $i
     */
    private static function colorize1(&$units, &$i) {
        if ($units[$i]['All']['AttackAir'] != 0 || $units[$i]['All']['AttackTer'] != 0) {
            $units[$i]['All']['Color'] = 1;
        }
    }

    /**
     * colorize2
     *
     * @param array $units
     * @param int $i
     */
    private static function colorize2(&$units, &$i) {
        for ($k = 0; $k < 3; $k++) {
            if ($units[$i]['All']['Magic'][$k] != null) {
                $units[$i]['All']['Color'] = 2;
            }
        }
    }
}
