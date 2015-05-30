<?php namespace Xaoc303\BattleCalc;

/**
 * Class Army
 * @package Xaoc303\BattleCalc
 *
 */
class Army
{
    /**
     * @var array
     */
    private $units;

    /**
     * @param array $input_units
     * @param integer $race
     */
    public function __construct($input_units, $race)
    {
        $units = new Unit();
        $this->setUnits($units->createArmy($input_units, $race));
    }

    /**
     * setUnits
     *
     * @param array $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * getUnits
     *
     * @return array
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * manCount
     *
     * @param integer $Inic
     * @return int
     */
    public function manCount(&$Inic)
    {
        $units = $this->getUnits();

        $ManCount = 0;
        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            $ManCount += $units[$i]['All']['ManCount'];
            if ($units[$i]['All']['Iniciative'] > $Inic and $units[$i]['All']['ManCount'] > 0) {
                $Inic = $units[$i]['All']['Iniciative'];
            }
        }

        return $ManCount;
    }

    /**
     * shadow
     *
     * @param Army $ArmyDet
     * @param integer $Round
     */
    public function shadow(Army &$ArmyDet, $Round)
    {
        /** @var integer $ManCount */
        /** @var bool $ManCountB */

        $unitsSh = $this->getUnits();
        $unitsDet = $ArmyDet->getUnits();

        // set shadow
        $count = count($unitsSh);
        for ($i = 0; $i < $count; $i++) {
            $unitsSh[$i]['All']['ManCountVisible'] = $unitsSh[$i]['All']['ManCount'];

            if ($unitsSh[$i]['All']['ManCount'] > 0) {
                switch ($unitsSh[$i]['Base']['ID']) {
                    case    105:    // Dark Templar
                    case    109:    // Observer
                        $unitsSh[$i]['All']['ManCountVisible'] = 0;
                        break;
                    case    114:    // Arbiter
                        // todo: проверить
                        $ManCount = $unitsSh[$i]['All']['MagicManCount'] * $unitsSh[$i]['All']['ManCount'];
                        while ($ManCount > 0) {
                            $ManCountB = true;

                            for ($i1 = 0; $i1 < count($unitsSh); $i1++) {
                                if ($i1 != $i && $unitsSh[$i1]['All']['ManCount'] > 0 && $unitsSh[$i1]['All']['ManCountVisible'] != 0) {
                                    $unitsSh[$i1]['All']['ManCountVisible']--;
                                    $ManCount--;
                                    $ManCountB = false;
                                    if ($ManCount == 0) {
                                        $i1 = count($unitsSh);
                                    }
                                }
                            }

                            if ($ManCountB) {
                                $ManCount = 0;
                            }
                        }
                        $i = count($unitsSh);
                        break;
                    case    204:    // Ghost
                        // todo: проверить
                        if ($Round > $unitsSh[$i]['All']['Iniciative'] - 20) {
                            $unitsSh[$i]['All']['ManCountVisible'] = 0;
                        } else {
                            $unitsSh[$i]['All']['ManCountVisible'] = $unitsSh[$i]['All']['ManCount'];
                        }
                        break;
                    case    213:    // Writh
                        // todo: проверить
                        if ($Round > $unitsSh[$i]['All']['Iniciative'] - 20) {
                            $unitsSh[$i]['All']['ManCountVisible'] = 0;
                        } else {
                            $unitsSh[$i]['All']['ManCountVisible'] = $unitsSh[$i]['All']['ManCount'];
                        }
                        break;
                    case    305:    // Lurker
                        // todo: проверить
                        if ($Round < $unitsSh[$i]['All']['Iniciative'] - 2) {
                            $unitsSh[$i]['All']['ManCountVisible'] = 0;
                        }
                        break;
                }
            }
        }

        $ManCount = 0;
        $count = count($unitsDet);
        for ($i = 0; $i < $count; $i++) {
            if (in_array($unitsDet[$i]['Base']['ID'], [109, 211, 309]) and $unitsDet[$i]['All']['ManCount'] > 0) {
                $ManCount += $unitsDet[$i]['All']['MagicManCount'] * $unitsDet[$i]['All']['ManCount'];
            }
        }

        while ($ManCount > 0) {
            $ManCountB = true;
            $count = count($unitsSh);
            for ($i = 0; $i < $count; $i++) {
                if ($unitsSh[$i]['All']['ManCount'] > 0 && $unitsSh[$i]['All']['ManCountVisible'] != $unitsSh[$i]['All']['ManCount']) {
                    $unitsSh[$i]['All']['ManCountVisible']++;
                    $ManCount--;
                    $ManCountB = false;
                    if ($ManCount == 0) {
                        $i = count($unitsSh);
                    }
                }
            }
            if ($ManCountB) {
                $ManCount = 0;
            }
        }

        $this->setUnits($unitsSh);
        $ArmyDet->setUnits($unitsDet);
    }

    /**
     * clear
     */
    public function clear()
    {
        $units = $this->getUnits();
        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            $units[$i]['All']['Color']             = 0;
            $units[$i]['All']['AttackTer']         = 0;
            $units[$i]['All']['AttackAir']         = 0;
            $units[$i]['All']['AttackMagicAll']    = 0;
            $units[$i]['All']['AttackMagicShield'] = 0;
            $units[$i]['All']['AttackMagicHP']     = 0;
            $units[$i]['All']['ManLock']           = 0;
            $units[$i]['All']['Magic'][0]          = null;
            $units[$i]['All']['Magic'][1]          = null;
            $units[$i]['All']['Magic'][2]          = null;
            $units[$i]['All']['HealingLive']       = 0;
            $units[$i]['All']['HealingTech']       = 0;
        }

        $this->setUnits($units);
    }

    /**
     * attackTo
     *
     * @param Army $ArmyDef
     * @param integer $Inic
     */
    public function attackTo(Army &$ArmyDef, $Inic)
    {
        $unitsAtt = $this->getUnits();
        $unitsDef = $ArmyDef->getUnits();

        $count = count($unitsAtt);

        for ($i = 0; $i < $count; $i++) {
            if ($unitsAtt[$i]['All']['Iniciative'] >= $Inic and $unitsAtt[$i]['All']['ManCount']>0) {
                $Cool = 24.0 / $unitsAtt[$i]['Base']['AttackCool'];         // Атак в секунду
                $Cool = round($Cool, 2);                                    // Округление до сотых
                $Cool += $unitsAtt[$i]['All']['AttackCoolDouble'];          // + предыдущий остаток
                $unitsAtt[$i]['All']['AttackCoolInt'] = (int) $Cool;        // int атак в секунду
                $unitsAtt[$i]['All']['AttackCoolDouble'] = $Cool - $unitsAtt[$i]['All']['AttackCoolInt'];    // double остаток атак в секунду
                if ($unitsAtt[$i]['All']['AttackCoolInt'] > 0) {
                    $unitsAtt[$i]['All']['AttackAir'] = ($unitsAtt[$i]['Base']['AttackAir'] * $unitsAtt[$i]['All']['ManCount']) * $unitsAtt[$i]['All']['AttackCoolInt'];
                    $unitsAtt[$i]['All']['AttackTer'] = ($unitsAtt[$i]['Base']['AttackTer'] * $unitsAtt[$i]['All']['ManCount']) * $unitsAtt[$i]['All']['AttackCoolInt'];

                    if ($unitsAtt[$i]['All']['MagicRound'] > 0) {
                        for ($k = 0; $k < 3; $k++) {
                            $unitsAtt[$i]['All']['Magic'][$k] = $unitsAtt[$i]['Base']['Magic'][$k];
                            $this->magicAttackTo($i, $k, $unitsAtt, $unitsDef);
                        }
                    }
                    $unitsAtt[$i]['All']['MagicRound']--;
                    $unitsAtt[$i]['All']['MagicRound'] = $this->unitMagicRound($unitsAtt[$i]['Base']['ID'], $unitsAtt[$i]['All']['MagicRound']);
                }
            }
        }

        $this->setUnits($unitsAtt);
        $ArmyDef->setUnits($unitsDef);
    }

    /**
     * unitMagicRound
     *
     * @param integer $id
     * @param integer $round
     * @return int
     */
    private function unitMagicRound($id, $round)
    {
        $rounds = array(
            101 => 0,
            102 => 0,
            103 => 0,
            104 => $round == -4 ? 1 : $round,
            105 => 0,
            106 => 0,
            107 => $round == -4 ? 1 : $round,
            108 => $round == -1 ? 1 : $round,
            109 => 0,
            110 => 0,
            111 => $round == -1 ? 1 : $round,
            112 => 0,
            113 => 0,
            114 => $round == -4 ? 1 : $round,

            201 => $round == -1 ? 1 : $round,
            202 => $round == -1 ? 1 : $round,
            203 => $round == -1 ? 1 : $round,
            204 => $round == -1 ? 1 : $round,
            205 => $round == -1 ? 1 : $round,
            206 => 0,
            207 => 0,
            208 => 0,
            209 => 0,
            210 => 0,
            211 => $round == -4 ? 1 : $round,
            212 => 0,
            213 => 0,
            214 => $round == -4 ? 1 : $round,

            301 => 0,
            302 => 0,
            303 => 0,
            304 => 0,
            305 => 0,
            306 => 0,
            307 => 0,
            308 => $round == -4 ? 1 : $round,
            309 => 0,
            310 => 0,
            311 => $round == -3 ? 1 : $round,
            312 => 0,
            313 => 0,
            314 => 0
        );

        return isset($rounds[$id]) ? $rounds[$id] : 0;
    }

    /**
     * magicAttackTo
     *
     * @param integer $i
     * @param string $k
     * @param array $ArmyAtt
     * @param array $ArmyDef
     */
    private function magicAttackTo($i, $k, &$ArmyAtt, &$ArmyDef)
    {
        /** @var integer $ManCount */
        /** @var bool $ManCountB */

        if ($ArmyAtt[$i]['All']['Magic'][$k] != null and $ArmyAtt[$i]['All']['Magic'][$k] != 'Steam') {
            $ArmyAtt[$i]['All']['AttackAir'] = 0;
            $ArmyAtt[$i]['All']['AttackTer'] = 0;
        }

        switch ($ArmyAtt[$i]['All']['Magic'][$k]) {
            // -------------------------------
            case    'Storm':
                $ArmyAtt[$i]['All']['AttackMagicAll'] = 250 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'FogP':
                $ManCount = $ArmyAtt[$i]['All']['MagicManCount'] * $ArmyAtt[$i]['All']['ManCount'];
                while ($ManCount > 0) {
                    $ManCountB = true;
                    $count = count($ArmyDef);
                    for ($i1 = 0; $i1 < $count; $i1++) {
                        if ($ArmyDef[$i1]['All']['ManCount'] > 0 and $ArmyDef[$i1]['All']['ManLock'] != $ArmyDef[$i1]['All']['ManCount']) {
                            $ArmyDef[$i1]['All']['ManLock']++;
                            $ManCount--;
                            $ManCountB = false;
                            if ($ManCount == 0) {
                                $i1 = $count;
                            }
                        }
                    }
                    if ($ManCountB) {
                        $ManCount = 0;
                    }
                }
                break;
            case    'MindControl':
                break;
            case    'LockP':
                $ManCount = $ArmyAtt[$i]['All']['MagicManCount'] * $ArmyAtt[$i]['All']['ManCount'];
                while ($ManCount > 0) {
                    $ManCountB = true;
                    $count = count($ArmyDef);
                    for ($i1 = 0; $i1 < $count; $i1++) {
                        if ($ArmyDef[$i1]['All']['ManCount'] > 0 and $ArmyDef[$i1]['All']['ManLock'] != $ArmyDef[$i1]['All']['ManCount']) {
                            $ArmyDef[$i1]['All']['ManLock']++;
                            $ManCount--;
                            $ManCountB = false;
                            if ($ManCount == 0) {
                                $i1 = $count;
                            }
                        }
                    }
                    if ($ManCountB) {
                        $ManCount = 0;
                    }
                }
                break;
            case    'Jump':
                break;
            case    'Phantom':
                $ArmyAtt[$i]['All']['ManCount']++;
                $ArmyAtt[$i]['All']['ManCountVisible']++;
                $ArmyAtt[$i]['All']['ManPhantom']++;
                $ArmyAtt[$i]['All']['HP'] += $ArmyAtt[$i]['Base']['HP'];
                $ArmyAtt[$i]['All']['Shield'] += $ArmyAtt[$i]['Base']['Shield'];
                break;
            case    'Scarab':
                $ArmyAtt[$i]['All']['AttackTer'] = 100 * $ArmyAtt[$i]['All']['ManCount'];
                break;


            // -------------------------------
            case    'Remont':
                $ArmyAtt[$i]['All']['HealingTech'] = 50 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'Medicine':
                $ArmyAtt[$i]['All']['HealingLive'] = 80 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'Blind':
                $ManCount = 3 * $ArmyAtt[$i]['All']['ManCount'];
                while ($ManCount > 0) {
                    $ManCountB = true;
                    $count = count($ArmyDef);
                    for ($i1 = 0; $i1 < $count; $i1++) {
                        if (in_array($ArmyDef[$i1]['Base']['ID'], [105, 109, 204, 213, 305])) {
                            if ($ArmyDef[$i1]['All']['ManCount'] > 0 && $ArmyDef[$i1]['All']['ManCountVisible'] != $ArmyDef[$i1]['All']['ManCount']) {
                                $ArmyDef[$i1]['All']['ManCountVisible']++;
                                $ManCount--;
                                $ManCountB = false;
                                if ($ManCount == 0) {
                                    $i1 = $count;
                                }
                            }
                        }
                    }
                    if ($ManCountB) {
                        $ManCount = 0;
                    }
                }
                break;
            case    'Steam':
                if ($ArmyAtt[$i]['All']['HP']/$ArmyAtt[$i]['All']['ManCount'] > 16) {
                    $ArmyAtt[$i]['All']['AttackAir'] = $ArmyAtt[$i]['All']['AttackAir'] * 2;
                    $ArmyAtt[$i]['All']['AttackTer'] = $ArmyAtt[$i]['All']['AttackTer'] * 2;
                    $ArmyAtt[$i]['All']['HP'] = $ArmyAtt[$i]['All']['HP'] - (8 * $ArmyAtt[$i]['All']['ManCount']);
                }
                break;
            case    'Yamato':
                $ArmyAtt[$i]['All']['AttackMagicAll'] = 260 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'Nuclear':
                $ArmyAtt[$i]['All']['AttackMagicAll'] = 300 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'LockT':
                break;
            case    'Matrix':
                break;
            case    'EMI':
                $ArmyAtt[$i]['All']['AttackMagicShield'] = 250 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'Radiation':
                $ArmyAtt[$i]['All']['AttackMagicHP'] = 100 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'Mines':
                $ArmyAtt[$i]['All']['AttackTer'] = 125 * $ArmyAtt[$i]['All']['ManCount'];
                break;

            // -------------------------------
            case    'FogZ':
                break;
            case    'Marker':
                break;
            case    'Plague':
                $ArmyAtt[$i]['All']['AttackMagicHP'] = 145 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case    'Guest':
                break;
            case    'Web':
                break;
        }
    }

    /**
     * @param integer $unit_type
     * @return int
     */
    public function manCountVisible($unit_type)
    {
        $units = $this->getUnits();

        $ManCountVisible = 0;
        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            if ($units[$i]['All']['UT'] == $unit_type) {
                $ManCountVisible += $units[$i]['All']['ManCountVisible'];
            }
        }
        return $ManCountVisible;
    }

    /**
     * @param integer $ManCountA
     */
    public function attackNull($ManCountA)
    {
        $units = $this->getUnits();

        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            if ($units[$i]['All']['ManCount'] > 0) {
                if ($units[$i]['All']['AttackAir'] > 0 and $units[$i]['All']['AttackTer'] and $ManCountA > 0) {
                    $units[$i]['All']['AttackTer'] = 0;
                } else {
                    $units[$i]['All']['AttackAir'] = 0;
                }

                if ($units[$i]['All']['ManLock'] > 0) {
                    $units[$i]['All']['AttackAir']           = $units[$i]['All']['AttackAir']            / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManLock']);
                    $units[$i]['All']['AttackTer']           = $units[$i]['All']['AttackTer']            / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManLock']);
                    $units[$i]['All']['AttackMagicAll']      = $units[$i]['All']['AttackMagicAll']       / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManLock']);
                    $units[$i]['All']['AttackMagicShield']   = $units[$i]['All']['AttackMagicShield']    / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManLock']);
                    $units[$i]['All']['AttackMagicHP']       = $units[$i]['All']['AttackMagicHP']        / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManLock']);
                }
                if ($units[$i]['All']['ManPhantom'] > 0) {
                    $units[$i]['All']['AttackAir']           = $units[$i]['All']['AttackAir']            / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManPhantom']);
                    $units[$i]['All']['AttackTer']           = $units[$i]['All']['AttackTer']            / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManPhantom']);
                    $units[$i]['All']['AttackMagicAll']      = $units[$i]['All']['AttackMagicAll']       / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManPhantom']);
                    $units[$i]['All']['AttackMagicShield']   = $units[$i]['All']['AttackMagicShield']    / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManPhantom']);
                    $units[$i]['All']['AttackMagicHP']       = $units[$i]['All']['AttackMagicHP']        / $units[$i]['All']['ManCount'] * ($units[$i]['All']['ManCount'] - $units[$i]['All']['ManPhantom']);
                }
            }
        }

        $this->setUnits($units);
    }

    /**
     * damageTo
     *
     * @param Army $ArmyDef
     */
    public function damageTo(Army &$ArmyDef)
    {
        $unitsAtt = $this->getUnits();
        $unitsDef = $ArmyDef->getUnits();

        $DamageTer = 0;
        $DamageAir = 0;
        $DamageMagicAll = 0;
        $DamageMagicShield = 0;
        $DamageMagicHP = 0;

        $ManCountT_Def = 0;
        $ManCountA_Def = 0;

        $ManDamageT = 0;
        $ManDamageA = 0;

        $HealingLive = 0;
        $HealingTech = 0;


        //	int iRand;
        //	rand;rand;rand;
        //	iRand=ArmyDef.size()-1;
        //	if (iRand>0) iRand=rand()%iRand;
        //	iRand=iRand;

        $count = count($unitsAtt);
        for ($i=0; $i<$count; $i++) {
            $DamageTer            += $unitsAtt[$i]['All']['AttackTer'];
            $DamageAir            += $unitsAtt[$i]['All']['AttackAir'];
            $DamageMagicAll       += $unitsAtt[$i]['All']['AttackMagicAll'];
            $DamageMagicShield    += $unitsAtt[$i]['All']['AttackMagicShield'];
            $DamageMagicHP        += $unitsAtt[$i]['All']['AttackMagicHP'];
        }


        $count = count($unitsDef);
        for ($i = 0; $i < $count; $i++) {
            if ($unitsDef[$i]['All']['UT']==0) {
                $ManCountT_Def += $unitsDef[$i]['All']['ManCountVisible'];
            }
            if ($unitsDef[$i]['All']['UT']==1) {
                $ManCountA_Def += $unitsDef[$i]['All']['ManCountVisible'];
            }
            $HealingLive        += $unitsDef[$i]['All']['HealingLive'];
            $HealingTech        += $unitsDef[$i]['All']['HealingTech'];
        }

        if ($DamageTer==0 || $ManCountT_Def==0) {
            $ManDamageT = 0;
        }
        if ($DamageTer>0 && $ManCountT_Def>0) {
            $ManDamageT = $DamageTer/$ManCountT_Def;
        }

        if ($DamageAir==0 || $ManCountA_Def==0) {
            $ManDamageA = 0;
        }
        if ($DamageAir>0 && $ManCountA_Def>0) {
            $ManDamageA = $DamageAir/$ManCountA_Def;
        }   // среднее на каждгого. воздух

        $count = count($unitsDef);
        for ($i = 0; $i < $count; $i++) {
            if ($unitsDef[$i]['All']['ManCountVisible'] > 0 || $DamageMagicAll > 0 || $DamageMagicShield > 0 || $DamageMagicHP > 0) {
                $ManDamageAll    = 0;
                $ManDamageShield = 0;
                $ManDamageHP     = 0;
                if ($unitsDef[$i]['All']['ManCountVisible'] > 0) {
                    if ($unitsDef[$i]['All']['UT'] == 0) {
                        $ManDamageAll = ($ManDamageT-(($ManDamageT* $unitsDef[$i]['Base']['Armor'])/100))* $unitsDef[$i]['All']['ManCount'];
                    } else {
                        $ManDamageAll = ($ManDamageA-(($ManDamageA* $unitsDef[$i]['Base']['Armor'])/100))* $unitsDef[$i]['All']['ManCount'];
                    }
                }
                if ($DamageMagicAll > 0) {
                    $ManDamageAll   += $DamageMagicAll     -(($DamageMagicAll   * $unitsDef[$i]['Base']['Armor'])/100);
                }
                if ($DamageMagicShield > 0) {
                    $ManDamageShield+= $DamageMagicShield  -(($DamageMagicShield* $unitsDef[$i]['Base']['Armor'])/100);
                }
                if ($DamageMagicHP > 0) {
                    $ManDamageHP    += $DamageMagicHP      -(($DamageMagicHP    * $unitsDef[$i]['Base']['Armor'])/100);
                }

                $unitsDef[$i]['All']['Shield'] -= $ManDamageShield;
                if ($unitsDef[$i]['All']['Shield'] < 0) {
                    $unitsDef[$i]['All']['Shield'] = 0;
                }


                $HPmax = $unitsDef[$i]['Base']['HP'] * $unitsDef[$i]['All']['ManCount'];

                $unitsDef[$i]['All']['HP'] -= $ManDamageHP;
                if ($unitsDef[$i]['All']['HP'] < 0) {
                    $unitsDef[$i]['All']['HP'] = 0;
                }

                $unitsDef[$i]['All']['Shield'] -= $ManDamageAll;
                if ($unitsDef[$i]['All']['Shield'] < 0) {
                    $unitsDef[$i]['All']['HP'] += $unitsDef[$i]['All']['Shield'];
                    $unitsDef[$i]['All']['Shield'] = 0;
                }

                if ($HealingLive > 0) {
                    if ($unitsDef[$i]['All']['Bio']) {
                        if ($unitsDef[$i]['All']['HP'] < $HPmax) {
                            $HPtemp = $HPmax - $unitsDef[$i]['All']['HP'];
                            if ($HealingLive >= $HPtemp) {
                                $HealingLive -= $HPtemp;
                                $unitsDef[$i]['All']['HP'] += $HPtemp;
                            }
                        }
                    }
                }

                if ($HealingTech > 0) {
                    if (!$unitsDef[$i]['All']['Bio']) {
                        if ($unitsDef[$i]['All']['HP'] < $HPmax) {
                            $HPtemp = $HPmax - $unitsDef[$i]['All']['HP'];
                            if ($HealingTech >= $HPtemp) {
                                $HealingTech -= $HPtemp;
                                $unitsDef[$i]['All']['HP'] += $HPtemp;
                            }
                        }
                    }
                }

                if ($unitsDef[$i]['All']['HP'] < 0) {
                    $unitsDef[$i]['All']['HP'] = 0;
                }

                /*
                if ($this->round==5)
                {
                    echo 'AttackTer='.$unitsAtt[0]['All']['AttackTer'].'<br />';

                    echo '$DamageMagicAll='.$DamageMagicAll.'<br />';
                    echo '$DamageMagicShield='.$DamageMagicShield.'<br />';
                    echo '$DamageMagicHP='.$DamageMagicHP.'<br />';

                    echo '$ManDamageAll='.$ManDamageAll.'<br />';
                    echo '$ManDamageShield='.$ManDamageShield.'<br />';
                    echo '$ManDamageHP='.$ManDamageHP.'<br />';
                }
                */
            }
        }

        $this->setUnits($unitsAtt);
        $ArmyDef->setUnits($unitsDef);
    }

    /**
     * count
     * @return int
     */
    public function count()
    {
        $units = $this->getUnits();

        $ManCount = 0;
        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            $ManCountTemp = $units[$i]['All']['ManCount'];
            $units[$i]['All']['ManCount'] = (int) ($units[$i]['All']['HP'] / $units[$i]['Base']['HP']);

            if ($units[$i]['All']['HP'] % $units[$i]['Base']['HP'] > 0) {
                $units[$i]['All']['ManCount']++;
            }
            if ($units[$i]['All']['ManPhantom'] > 0 and $ManCountTemp>$units[$i]['All']['ManCount']) {
                $units[$i]['All']['ManPhantom']--;
            }

            $ManCount += $units[$i]['All']['ManCount'];
        }

        $this->setUnits($units);
        return $ManCount;
    }

    /**
     * color
     */
    public function color()
    {
        $units = $this->getUnits();

        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            if ($units[$i]['All']['AttackAir'] != 0 or $units[$i]['All']['AttackTer'] != 0) {
                $units[$i]['All']['Color'] = 1;
            }
            for ($k = 0; $k < 3; $k++) {
                if ($units[$i]['All']['Magic'][$k] != null) {
                    $units[$i]['All']['Color'] = 2;
                }
            }
        }

        $this->setUnits($units);
    }
}
