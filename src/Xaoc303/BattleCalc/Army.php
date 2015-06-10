<?php namespace Xaoc303\BattleCalc;

/**
 * Class Army
 * @package Xaoc303\BattleCalc
 */
class Army
{
    /**
     * @var array
     */
    private $units;

    /**
     * __construct
     *
     * @param array $input_units
     */
    public function __construct($input_units)
    {
        $units = new Squad();
        $this->setUnits($units->createArmy($input_units));
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
            if ($units[$i]['All']['Iniciative'] > $Inic && $units[$i]['All']['ManCount'] > 0) {
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
        $unitsSh = $this->getUnits();
        $unitsDet = $ArmyDet->getUnits();

        // set shadow
        $count = count($unitsSh);
        for ($i = 0; $i < $count; $i++) {
            $unitsSh[$i]['All']['ManCountVisible'] = $unitsSh[$i]['All']['ManCount'];

            if ($unitsSh[$i]['All']['ManCount'] > 0) {
                $this->setShadow($unitsSh, $i, $count, $Round);
            }
        }

        $ManCount = $this->getDetectorsCount($unitsDet);
        $this->unsetShadow($unitsSh, $ManCount);

        $this->setUnits($unitsSh);
        $ArmyDet->setUnits($unitsDet);
    }

    /**
     * setShadow
     *
     * @param array $unitsSh
     * @param int $i
     * @param int $units_count
     * @param int $Round
     */
    private function setShadow(&$unitsSh, &$i, &$units_count, $Round)
    {
        switch ($unitsSh[$i]['Base']['ID']) {
            case 105: // Dark Templar
            case 109: // Observer
                $this->setShadowSquadAll($unitsSh, $i);
                break;

            case 114: // Arbiter
                $this->setShadowSquad114($unitsSh, $i, $units_count);
                break;

            case 204: // Ghost
                $this->setShadowSquadRound($unitsSh, $i, $Round);
                break;

            case 213: // Writh
                $this->setShadowSquadRound($unitsSh, $i, $Round);
                break;

            case 305: // Lurker
                $this->setShadowSquadAllHold($unitsSh, $i, $Round);
                break;
        }
    }

    /**
     * setShadowSquadAll
     *
     * @param array $unitsSh
     * @param int $i
     */
    private function setShadowSquadAll(&$unitsSh, &$i)
    {
        $unitsSh[$i]['All']['ManCountVisible'] = 0;
    }

    /**
     * setShadowSquad114
     *
     * @param array $unitsSh
     * @param int $i
     * @param int $units_count
     */
    private function setShadowSquad114(&$unitsSh, &$i, &$units_count)
    {
        $ManCount = $unitsSh[$i]['All']['MagicManCount'] * $unitsSh[$i]['All']['ManCount'];
        while ($ManCount > 0) {
            $ManCountExists = false;

            for ($i1 = 0; $i1 < $units_count; $i1++) {
                if ($i1 != $i && $unitsSh[$i1]['All']['ManCount'] > 0 && $unitsSh[$i1]['All']['ManCountVisible'] != 0) {
                    $unitsSh[$i1]['All']['ManCountVisible']--;
                    $ManCount--;
                    $ManCountExists = true;
                    if ($ManCount == 0) {
                        $i1 = $units_count;
                    }
                }
            }

            if (!$ManCountExists) {
                $ManCount = 0;
            }
        }
        $i = $units_count;
    }

    /**
     * setShadowSquadRound
     *
     * @param array $unitsSh
     * @param int $i
     * @param int $Round
     */
    private function setShadowSquadRound(&$unitsSh, &$i, &$Round)
    {
        if ($Round > $unitsSh[$i]['All']['Iniciative'] - 20) {
            $unitsSh[$i]['All']['ManCountVisible'] = 0;
        } else {
            $unitsSh[$i]['All']['ManCountVisible'] = $unitsSh[$i]['All']['ManCount'];
        }
    }

    /**
     * setShadowSquadAllHold
     *
     * @param array $unitsSh
     * @param int $i
     * @param null|int $Round
     */
    private function setShadowSquadAllHold(&$unitsSh, &$i, $Round = null)
    {
        if ($Round < $unitsSh[$i]['All']['Iniciative'] - 2) {
            $unitsSh[$i]['All']['ManCountVisible'] = 0;
        }
    }

    /**
     * getDetectorsCount
     *
     * @param array $unitsDet
     * @return int
     */
    private function getDetectorsCount(&$unitsDet)
    {
        $ManCount = 0;
        $count = count($unitsDet);
        for ($i = 0; $i < $count; $i++) {
            if (in_array($unitsDet[$i]['Base']['ID'], [109, 211, 309]) && $unitsDet[$i]['All']['ManCount'] > 0) {
                $ManCount += $unitsDet[$i]['All']['MagicManCount'] * $unitsDet[$i]['All']['ManCount'];
            }
        }
        return $ManCount;
    }

    /**
     * unsetShadow
     *
     * @param array $unitsSh
     * @param int $ManCount
     */
    private function unsetShadow(&$unitsSh, $ManCount)
    {
        while ($ManCount > 0) {
            $ManCountExists = false;
            $count = count($unitsSh);
            for ($i = 0; $i < $count; $i++) {
                if ($unitsSh[$i]['All']['ManCount'] > 0 && $unitsSh[$i]['All']['ManCountVisible'] != $unitsSh[$i]['All']['ManCount']) {
                    $unitsSh[$i]['All']['ManCountVisible']++;
                    $ManCount--;
                    $ManCountExists = true;
                    if ($ManCount == 0) {
                        $i = count($unitsSh);
                    }
                }
            }
            if (!$ManCountExists) {
                $ManCount = 0;
            }
        }
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
     * manCountVisible
     *
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
     * attackNull
     *
     * @param integer $ManCountA
     */
    public function attackNull($ManCountA)
    {
        $units = $this->getUnits();

        $count = count($units);
        for ($i = 0; $i < $count; $i++) {
            if ($units[$i]['All']['ManCount'] > 0) {
                if ($units[$i]['All']['AttackAir'] > 0 && $units[$i]['All']['AttackTer'] && $ManCountA > 0) {
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
     * count
     *
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
            if ($units[$i]['All']['ManPhantom'] > 0 && $ManCountTemp > $units[$i]['All']['ManCount']) {
                $units[$i]['All']['ManPhantom']--;
            }

            $ManCount += $units[$i]['All']['ManCount'];
        }

        $this->setUnits($units);
        return $ManCount;
    }
}
