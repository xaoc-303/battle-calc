<?php namespace Xaoc303\BattleCalc;

use Config;

/**
 * Class Unit
 * @package Xaoc303\BattleCalc
 */
class Unit
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $race_id;

    /**
     * @var int
     */
    public $init;

    /**
     * @var int
     */
    public $type;

    /**
     * @var int
     */
    public $bio;

    /**
     * @var int
     */
    public $shield;

    /**
     * @var int
     */
    public $armor;

    /**
     * @var int
     */
    public $hp;

    /**
     * @var int
     */
    public $mround;

    /**
     * @var int
     */
    public $cool;

    /**
     * @var int
     */
    public $attack_ter;

    /**
     * @var int
     */
    public $attack_air;

    /**
     * @var int
     */
    public $attack_magic;

    /**
     * @var null|string
     */
    public $magic1;

    /**
     * @var null|string
     */
    public $magic2;

    /**
     * @var null|string
     */
    public $magic3;

    public function findAllOfRace($race)
    {
        return array_map(function ($v) { return (object) $v; }, Config::get('battle-calc::units.'.$race));
    }

    public function createArmy($input_units, $race)
    {
        $units = $this->findAllOfRace($race);

        $result_units = array();

        foreach ($units as $unit) {
            if ($input_units[$unit->id] > 0) { // количество
                $result_units[] = $this->create($unit, $input_units);
            }
        }

        return $result_units;
    }

    public function create($unit, $input_units)
    {
        $masUnitTownDistruct = array(104, 204, 308);

        $base = array();
        $base['ID']            = $unit->id;               // id
        $base['ManCount']      = $input_units[$unit->id]; // Количество
        $base['Iniciative']    = 0;                       // Инициатива
        $base['ShieldUp']      = 1;                       // Щит
        $base['ArmorUp']       = 1;                       // Броня
        $base['AttackTerUp']   = 1;                       // атака земля
        $base['AttackAirUp']   = 1;                       // атака воздух
        $base['AttackMagicUp'] = 1;                       // Магия
        $base['Magic'][0]      = $unit->magic1;           // Магия
        $base['Magic'][1]      = $unit->magic2;           // Магия
        $base['Magic'][2]      = $unit->magic3;           // Магия


        $battle = array();
        $base['TownDistruct'] = in_array($base['ID'], $masUnitTownDistruct);
        $battle['TownDistruct'] = $base['TownDistruct'];

        $battle['Iniciative'] = $unit->init;    // Инициатива
        $battle['UT'] = $unit->type;            // Тип

        $battle['Bio'] = $unit->bio;            // био-тип

        $base['Shield'] = $unit->shield;        // Щит
        $battle['Shield'] = $base['Shield'] * $base['ManCount'];

        $base['Armor'] = $unit->armor;          // Броня
        $battle['Armor'] = $base['Armor'] * $base['ManCount'];

        $base['HP'] = $unit->hp;                // Здоровье
        $battle['HP'] = $base['HP'] * $base['ManCount'];

        $battle['Magic'][0] = null;
        $battle['Magic'][1] = null;
        $battle['Magic'][2] = null;

        $battle['MagicRound'] = $unit->mround;  // Магическая атака
        $battle['AttackMagicAll'] = 0;
        $battle['AttackMagicShield'] = 0;
        $battle['AttackMagicHP'] = 0;

        $base['AttackCool'] = $unit->cool;      // атака задержка
        $battle['AttackCoolInt'] = 0;
        $battle['AttackCoolDouble'] = 0;

        $base['AttackTer'] = $unit->attack_ter; // атака земля
        $battle['AttackTer'] = 0;

        $base['AttackAir'] = $unit->attack_air; // атака воздух
        $battle['AttackAir'] = 0;

        $battle['ManCountVisible'] = 0;
        $battle['MagicManCount'] = $unit->attack_magic * $base['AttackMagicUp'];
        $battle['ManLock'] = 0;
        $battle['ManPhantom'] = 0;
        $battle['Damage'] = 0;
        $battle['Color'] = 0;
        $battle['ManCount'] = $base['ManCount'];

        $battle['HealingLive'] = 0;
        $battle['HealingTech'] = 0;

        return [
            'Base' => $base,
            'All'  => $battle
        ];
    }
}
