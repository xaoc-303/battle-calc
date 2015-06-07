<?php namespace Xaoc303\BattleCalc;

/**
 * Class Squad
 * @package Xaoc303\BattleCalc
 */
class Squad
{
    public function createArmy($input_units)
    {
        $squads = [];
        foreach ($input_units as $unit_id => $units_count) {
            if ($units_count > 0) {
                $unit = (new Unit)->findById($unit_id);
                $squads[] = $this->create($unit, $units_count);
            }
        }
        return $squads;
    }

    public function create(Unit $unit, $units_count)
    {
        $masUnitTownDistruct = array(104, 204, 308);

        $base = array();
        $base['ID']            = $unit->id;               // id
        $base['ManCount']      = $units_count;            // Количество
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
