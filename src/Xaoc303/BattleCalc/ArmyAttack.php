<?php namespace Xaoc303\BattleCalc;

/**
 * Class ArmyAttack
 * @package Xaoc303\BattleCalc
 */
class ArmyAttack
{
    /**
     * @var array
     */
    private $unitsAtt;

    /**
     * @var array
     */
    private $unitsDef;

    /**
     * run
     *
     * @param Army $armyAtt
     * @param Army $armyDef
     * @param int $Inic
     */
    public static function run(Army &$armyAtt, Army &$armyDef, $Inic)
    {
        $class = new ArmyAttack();

        $class->unitsAtt = $armyAtt->getUnits();
        $class->unitsDef = $armyDef->getUnits();

        $class->attack($Inic);

        $armyAtt->setUnits($class->unitsAtt);
        $armyDef->setUnits($class->unitsDef);
    }

    /**
     * attack
     *
     * @param int $Inic
     */
    private function attack($Inic)
    {
        $count = count($this->unitsAtt);
        for ($i = 0; $i < $count; $i++) {
            if ($this->unitsAtt[$i]['All']['Iniciative'] >= $Inic && $this->unitsAtt[$i]['All']['ManCount'] > 0) {
                $Cool = 24.0 / $this->unitsAtt[$i]['Base']['AttackCool'];   // Атак в секунду
                $Cool = round($Cool, 2);                                    // Округление до сотых
                $Cool += $this->unitsAtt[$i]['All']['AttackCoolDouble'];    // + предыдущий остаток
                $this->unitsAtt[$i]['All']['AttackCoolInt'] = (int) $Cool;  // int атак в секунду
                $this->unitsAtt[$i]['All']['AttackCoolDouble'] = $Cool - $this->unitsAtt[$i]['All']['AttackCoolInt'];    // double остаток атак в секунду
                if ($this->unitsAtt[$i]['All']['AttackCoolInt'] > 0) {
                    $this->attackAirOn($i);
                    $this->attackTerOn($i);
                    $this->attackMagicOn($i);
                }
            }
        }
    }

    /**
     * attackAirOn
     *
     * @param int $i
     */
    private function attackAirOn(&$i)
    {
        $this->unitsAtt[$i]['All']['AttackAir'] = ($this->unitsAtt[$i]['Base']['AttackAir'] * $this->unitsAtt[$i]['All']['ManCount']) * $this->unitsAtt[$i]['All']['AttackCoolInt'];
    }

    /**
     * attackTerOn
     *
     * @param int $i
     */
    private function attackTerOn(&$i)
    {
        $this->unitsAtt[$i]['All']['AttackTer'] = ($this->unitsAtt[$i]['Base']['AttackTer'] * $this->unitsAtt[$i]['All']['ManCount']) * $this->unitsAtt[$i]['All']['AttackCoolInt'];
    }

    /**
     * attackMagicOn
     *
     * @param int $i
     */
    private function attackMagicOn(&$i)
    {
        if ($this->unitsAtt[$i]['All']['MagicRound'] > 0) {
            for ($k = 0; $k < 3; $k++) {
                $this->unitsAtt[$i]['All']['Magic'][$k] = $this->unitsAtt[$i]['Base']['Magic'][$k];
                $this->attackMagic($i, $k);
            }
        }
        $this->unitsAtt[$i]['All']['MagicRound']--;
        $this->unitsAtt[$i]['All']['MagicRound'] = $this->unitMagicRound($this->unitsAtt[$i]['Base']['ID'], $this->unitsAtt[$i]['All']['MagicRound']);
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
        switch ($id) {
            case 108:
            case 111:
            case 201:
            case 202:
            case 203:
            case 204:
            case 205:
                return $round == -1 ? 1 : $round;
            case 311:
                return $round == -3 ? 1 : $round;
            case 104:
            case 107:
            case 114:
            case 211:
            case 214:
            case 308:
                return $round == -4 ? 1 : $round;
            default:
                return 0;
        }
    }

    /**
     * attackMagic
     *
     * @param integer $i
     * @param string $k
     */
    private function attackMagic($i, $k)
    {
        if ($this->unitsAtt[$i]['All']['Magic'][$k] == null) {
            return;
        }

        if ($this->unitsAtt[$i]['All']['Magic'][$k] != 'Steam') {
            $this->unitsAtt[$i]['All']['AttackAir'] = 0;
            $this->unitsAtt[$i]['All']['AttackTer'] = 0;
        }

        $methodName = 'attackMagic'.$this->unitsAtt[$i]['All']['Magic'][$k];
        switch ($this->unitsAtt[$i]['All']['Magic'][$k]) {
            // -------------------------------
            case 'Storm':
            case 'FogP':
                $this->$methodName($i);
                break;
            case 'MindControl':
                break;
            case 'LockP':
                $this->$methodName($i);
                break;
            case 'Jump':
                break;
            case 'Phantom':
            case 'Scarab':
                $this->$methodName($i);
                break;

            // -------------------------------
            case 'Remont':
            case 'Medicine':
            case 'Blind':
            case 'Steam':
            case 'Yamato':
            case 'Nuclear':
                $this->$methodName($i);
                break;
            case 'LockT':
                break;
            case 'Matrix':
                break;
            case 'EMI':
            case 'Radiation':
            case 'Mines':
                $this->$methodName($i);
                break;

            // -------------------------------
            case 'FogZ':
                break;
            case 'Marker':
                break;
            case 'Plague':
                $this->$methodName($i);
                break;
            case 'Guest':
                break;
            case 'Web':
                break;
        }
    }

    /**
     * attackMagicStorm
     *
     * @param int $i
     */
    private function attackMagicStorm($i)
    {
        $this->unitsAtt[$i]['All']['AttackMagicAll'] = 250 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicLockP
     *
     * @param int $i
     */
    private function attackMagicLockP($i)
    {
        $ManCount = $this->unitsAtt[$i]['All']['MagicManCount'] * $this->unitsAtt[$i]['All']['ManCount'];
        while ($ManCount > 0) {
            $ManCountExists = false;
            $count = count($this->unitsDef);
            for ($i1 = 0; $i1 < $count; $i1++) {
                if ($this->unitsDef[$i1]['All']['ManCount'] > 0 && $this->unitsDef[$i1]['All']['ManLock'] != $this->unitsDef[$i1]['All']['ManCount']) {
                    $this->unitsDef[$i1]['All']['ManLock']++;
                    $ManCount--;
                    $ManCountExists = true;
                    if ($ManCount == 0) {
                        $i1 = $count;
                    }
                }
            }
            if (!$ManCountExists) {
                $ManCount = 0;
            }
        }
    }

    /**
     * attackMagicPhantom
     *
     * @param int $i
     */
    private function attackMagicPhantom($i)
    {
        $this->unitsAtt[$i]['All']['ManCount']++;
        $this->unitsAtt[$i]['All']['ManCountVisible']++;
        $this->unitsAtt[$i]['All']['ManPhantom']++;
        $this->unitsAtt[$i]['All']['HP'] += $this->unitsAtt[$i]['Base']['HP'];
        $this->unitsAtt[$i]['All']['Shield'] += $this->unitsAtt[$i]['Base']['Shield'];
    }

    /**
     * attackMagicScarab
     *
     * @param int $i
     */
    private function attackMagicScarab($i)
    {
        $this->unitsAtt[$i]['All']['AttackTer'] = 100 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicRemont
     *
     * @param int $i
     */
    private function attackMagicRemont($i)
    {
        $this->unitsAtt[$i]['All']['HealingTech'] = 50 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicMedicine
     *
     * @param int $i
     */
    private function attackMagicMedicine($i)
    {
        $this->unitsAtt[$i]['All']['HealingLive'] = 80 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicBlind
     *
     * @param int $i
     */
    private function attackMagicBlind($i)
    {
        $ManCount = 3 * $this->unitsAtt[$i]['All']['ManCount'];
        while ($ManCount > 0) {
            $ManCountExists = false;
            $count = count($this->unitsDef);
            for ($i1 = 0; $i1 < $count; $i1++) {
                if (in_array($this->unitsDef[$i1]['Base']['ID'], [105, 109, 204, 213, 305])) {
                    if ($this->unitsDef[$i1]['All']['ManCount'] > 0 && $this->unitsDef[$i1]['All']['ManCountVisible'] != $this->unitsDef[$i1]['All']['ManCount']) {
                        $this->unitsDef[$i1]['All']['ManCountVisible']++;
                        $ManCount--;
                        $ManCountExists = true;
                        if ($ManCount == 0) {
                            $i1 = $count;
                        }
                    }
                }
            }
            if (!$ManCountExists) {
                $ManCount = 0;
            }
        }
    }

    /**
     * attackMagicSteam
     *
     * @param int $i
     */
    private function attackMagicSteam($i)
    {
        if ($this->unitsAtt[$i]['All']['HP'] / $this->unitsAtt[$i]['All']['ManCount'] > 16) {
            $this->unitsAtt[$i]['All']['AttackAir'] = $this->unitsAtt[$i]['All']['AttackAir'] * 2;
            $this->unitsAtt[$i]['All']['AttackTer'] = $this->unitsAtt[$i]['All']['AttackTer'] * 2;
            $this->unitsAtt[$i]['All']['HP'] = $this->unitsAtt[$i]['All']['HP'] - (8 * $this->unitsAtt[$i]['All']['ManCount']);
        }
    }

    /**
     * attackMagicYamato
     *
     * @param int $i
     */
    private function attackMagicYamato($i)
    {
        $this->unitsAtt[$i]['All']['AttackMagicAll'] = 260 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicNuclear
     *
     * @param int $i
     */
    private function attackMagicNuclear($i)
    {
        $this->unitsAtt[$i]['All']['AttackMagicAll'] = 300 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicEMI
     *
     * @param int $i
     */
    private function attackMagicEMI($i)
    {
        $this->unitsAtt[$i]['All']['AttackMagicShield'] = 250 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicRadiation
     *
     * @param int $i
     */
    private function attackMagicRadiation($i)
    {
        $this->unitsAtt[$i]['All']['AttackMagicHP'] = 100 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicMines
     *
     * @param int $i
     */
    private function attackMagicMines($i)
    {
        $this->unitsAtt[$i]['All']['AttackTer'] = 125 * $this->unitsAtt[$i]['All']['ManCount'];
    }

    /**
     * attackMagicPlague
     *
     * @param int $i
     */
    private function attackMagicPlague($i)
    {
        $this->unitsAtt[$i]['All']['AttackMagicHP'] = 145 * $this->unitsAtt[$i]['All']['ManCount'];
    }
}
