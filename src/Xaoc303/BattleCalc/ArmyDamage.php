<?php namespace Xaoc303\BattleCalc;

/**
 * Class ArmyDamage
 * @package Xaoc303\BattleCalc
 */
class ArmyDamage
{
    /**
     * @var array
     */
    private $unitsAtt;

    /**
     * @var array
     */
    private $unitsDef;

    private $DamageTer = 0;
    private $DamageAir = 0;
    private $DamageMagicAll = 0;
    private $DamageMagicShield = 0;
    private $DamageMagicHP = 0;

    private $ManCountT_Def = 0;
    private $ManCountA_Def = 0;

    private $HealingLive = 0;
    private $HealingTech = 0;

    private $ManDamageT = 0;
    private $ManDamageA = 0;

    /**
     * run
     *
     * @param Army $armyAtt
     * @param Army $armyDef
     */
    public static function run(Army &$armyAtt, Army &$armyDef)
    {
        $class = new ArmyDamage();

        $class->unitsAtt = $armyAtt->getUnits();
        $class->unitsDef = $armyDef->getUnits();

        $class->damage();

        $armyAtt->setUnits($class->unitsAtt);
        $armyDef->setUnits($class->unitsDef);
    }

    /**
     * damage
     */
    private function damage()
    {
        $this->damageSum();
        $this->damageManCount();
        $this->damageHealing();

        $this->setManDamageT();
        $this->setManDamageA();

        $count = count($this->unitsDef);
        for ($i = 0; $i < $count; $i++) {
            if ($this->unitsDef[$i]['All']['ManCountVisible'] > 0 || $this->DamageMagicAll > 0 || $this->DamageMagicShield > 0 || $this->DamageMagicHP > 0) {
                $this->damageSquad($i);
            }
        }
    }

    private function setManDamageT()
    {
        if ($this->DamageTer == 0 || $this->ManCountT_Def == 0) {
            $this->ManDamageT = 0;
        }
        if ($this->DamageTer > 0 && $this->ManCountT_Def > 0) {
            $this->ManDamageT = $this->DamageTer / $this->ManCountT_Def;
        }
    }

    private function setManDamageA()
    {
        if ($this->DamageAir == 0 || $this->ManCountA_Def == 0) {
            $this->ManDamageA = 0;
        }
        if ($this->DamageAir > 0 && $this->ManCountA_Def > 0) {
            $this->ManDamageA = $this->DamageAir / $this->ManCountA_Def;
        }   // среднее на каждгого. воздух
    }

    /**
     * damageSum
     */
    private function damageSum()
    {
        $count = count($this->unitsAtt);
        for ($i = 0; $i < $count; $i++) {
            $this->DamageTer         += $this->unitsAtt[$i]['All']['AttackTer'];
            $this->DamageAir         += $this->unitsAtt[$i]['All']['AttackAir'];
            $this->DamageMagicAll    += $this->unitsAtt[$i]['All']['AttackMagicAll'];
            $this->DamageMagicShield += $this->unitsAtt[$i]['All']['AttackMagicShield'];
            $this->DamageMagicHP     += $this->unitsAtt[$i]['All']['AttackMagicHP'];
        }
    }

    /**
     * damageManCount
     */
    private function damageManCount()
    {
        $count = count($this->unitsDef);
        for ($i = 0; $i < $count; $i++) {
            if ($this->unitsDef[$i]['All']['UT'] == 0) {
                $this->ManCountT_Def += $this->unitsDef[$i]['All']['ManCountVisible'];
            }
            if ($this->unitsDef[$i]['All']['UT'] == 1) {
                $this->ManCountA_Def += $this->unitsDef[$i]['All']['ManCountVisible'];
            }
        }
    }

    /**
     * damageHealing
     */
    private function damageHealing()
    {
        $count = count($this->unitsDef);
        for ($i = 0; $i < $count; $i++) {
            $this->HealingLive += $this->unitsDef[$i]['All']['HealingLive'];
            $this->HealingTech += $this->unitsDef[$i]['All']['HealingTech'];
        }
    }

    private function damageSquad(&$i)
    {
        $unitsDef = $this->unitsDef;
        $ManDamageAll    = 0;
        $ManDamageShield = 0;
        $ManDamageHP     = 0;
        if ($unitsDef[$i]['All']['ManCountVisible'] > 0) {
            if ($unitsDef[$i]['All']['UT'] == 0) {
                $ManDamageAll = ($this->ManDamageT-(($this->ManDamageT* $unitsDef[$i]['Base']['Armor'])/100))* $unitsDef[$i]['All']['ManCount'];
            } else {
                $ManDamageAll = ($this->ManDamageA-(($this->ManDamageA* $unitsDef[$i]['Base']['Armor'])/100))* $unitsDef[$i]['All']['ManCount'];
            }
        }
        if ($this->DamageMagicAll > 0) {
            $ManDamageAll    += $this->DamageMagicAll     -(($this->DamageMagicAll   * $unitsDef[$i]['Base']['Armor'])/100);
        }
        if ($this->DamageMagicShield > 0) {
            $ManDamageShield += $this->DamageMagicShield  -(($this->DamageMagicShield* $unitsDef[$i]['Base']['Armor'])/100);
        }
        if ($this->DamageMagicHP > 0) {
            $ManDamageHP     += $this->DamageMagicHP      -(($this->DamageMagicHP    * $unitsDef[$i]['Base']['Armor'])/100);
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

        $this->healingLive($unitsDef, $i, $HPmax);
        $this->healingTech($unitsDef, $i, $HPmax);

        if ($unitsDef[$i]['All']['HP'] < 0) {
            $unitsDef[$i]['All']['HP'] = 0;
        }

        $this->unitsDef = $unitsDef;
    }

    private function healingLive(&$unitsDef, $i, $HPmax)
    {
        if ($this->HealingLive > 0) {
            if ($unitsDef[$i]['All']['Bio']) {
                if ($unitsDef[$i]['All']['HP'] < $HPmax) {
                    $HPtemp = $HPmax - $unitsDef[$i]['All']['HP'];
                    if ($this->HealingLive >= $HPtemp) {
                        $this->HealingLive -= $HPtemp;
                        $unitsDef[$i]['All']['HP'] += $HPtemp;
                    }
                }
            }
        }
    }

    private function healingTech(&$unitsDef, $i, $HPmax)
    {
        if ($this->HealingTech > 0) {
            if (!$unitsDef[$i]['All']['Bio']) {
                if ($unitsDef[$i]['All']['HP'] < $HPmax) {
                    $HPtemp = $HPmax - $unitsDef[$i]['All']['HP'];
                    if ($this->HealingTech >= $HPtemp) {
                        $this->HealingTech -= $HPtemp;
                        $unitsDef[$i]['All']['HP'] += $HPtemp;
                    }
                }
            }
        }
    }
}
