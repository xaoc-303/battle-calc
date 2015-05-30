<?php namespace Xaoc303\BattleCalc;

use View;

/**
 * Class BattleCalc
 * @package Xaoc303\BattleCalc
 */
class BattleCalc
{
    /**
     * @var string
     */
    private $log = '';

    /**
     * @var int
     */
    private $round = 0;

    /**
     * @var Army
     */
    private $army1;

    /**
     * @var Army
     */
    private $army2;

    /**
     * calculation
     *
     * @param integer $race_1
     * @param array $army_1
     * @param integer $race_2
     * @param array $army_2
     * @return string
     */
    public function calculation(&$race_1, &$army_1, &$race_2, &$army_2)
    {
        $this->army1 = new Army($army_1, $race_1);
        $this->army2 = new Army($army_2, $race_2);

        $this->war();

        return $this->log;
    }

    /**
     * war
     */
    private function war()
    {
        $MaxIniciative = 0;

        $ManCount1 = $this->army1->manCount($MaxIniciative);
        $ManCount2 = $this->army2->manCount($MaxIniciative);

        $Round = 0;
        $this->round = $Round;
        $this->addLog($Round);

        while (($ManCount1 > 0) && ($ManCount2 > 0) && ($Round < 50)) {
            $this->battle($ManCount1, $ManCount2, $MaxIniciative, $Round);

            $Round++;
            $this->round = $Round;
            $MaxIniciative--;
            $this->addLog($Round);
        }
    }

    /**
     * battle
     *
     * @param integer $ManCount1
     * @param integer $ManCount2
     * @param integer $MaxIniciative
     * @param integer $Round
     */
    private function battle(&$ManCount1, &$ManCount2, &$MaxIniciative, &$Round)
    {
        $this->army1->shadow($this->army2, $MaxIniciative);
        $this->army2->shadow($this->army1, $MaxIniciative);

        $this->army1->clear();
        $this->army2->clear();

        $this->army1->attackTo($this->army2, $MaxIniciative);
        $this->army2->attackTo($this->army1, $MaxIniciative);

        $this->army1->attackNull($this->army2->manCountVisible(1));
        $this->army2->attackNull($this->army1->manCountVisible(1));

        $this->army1->color();
        $this->army2->color();

        if ($this->exitRound($this->army1, $this->army2, $MaxIniciative, $Round)) {
            ob_start();
            echo '$Round = '.$Round.'<br />';
            echo '$MaxIniciative = '.$MaxIniciative.'<br />';
            echo '$ManCount1 = '.$ManCount1.'<br />';
            echo '$ManCount2 = '.$ManCount2.'<br />';
            echo '<pre>'.print_r($this->army1, true).'</pre>';
            echo '<pre>'.print_r($this->army2, true).'</pre>';
            echo $this->log;
            ob_end_flush();
            exit;
        }

        $this->army2->damageTo($this->army1);
        $this->army1->damageTo($this->army2);

        $ManCount1 = $this->army1->count();
        $ManCount2 = $this->army2->count();
    }

    /**
     * exitRound
     *
     * @param Army $Army1
     * @param Army $Army2
     * @param integer $Inic
     * @param integer $Round
     * @return bool
     */
    private function exitRound(Army &$Army1, Army &$Army2, $Inic, $Round)
    {
        $ERound = false;
        //        int DamageT1 = 0;
        //        int DamageT2 = 0;
        //        int DamageA1 = 0;
        //        int DamageA2 = 0;
        //        int DamageM1 = 0;
        //        int DamageM2 = 0;
        //        int ManCountT1 = 0;
        //        int ManCountT2 = 0;
        //        int ManCountA1 = 0;
        //        int ManCountA2 = 0;
        //
        //        for (int i=0; i<Army1.size(); i++)
        //        {
        //            DamageT1 += Army1[i].AttackTer;
        //            DamageA1 += Army1[i].AttackAir;
        //            DamageM1 += Army1[i].AttackMagic;
        //            if (Army1[i].UT==Ter) ManCountT1 += Army1[i].ManCount;
        //            if (Army1[i].UT==Air) ManCountA1 += Army1[i].ManCount;
        //        }
        //
        //        for (int i=0; i<Army2.size(); i++)
        //        {
        //            DamageT2 += Army2[i].AttackTer;
        //            DamageA2 += Army2[i].AttackAir;
        //            DamageM2 += Army2[i].AttackMagic;
        //            if (Army2[i].UT==Ter) ManCountT2 += Army2[i].ManCount;
        //            if (Army2[i].UT==Air) ManCountA2 += Army2[i].ManCount;
        //        }
        //
        //        if (ManCountT2==0)	DamageT1=0;
        //        if (ManCountA2==0)	DamageA1=0;
        //        if (ManCountT1==0)	DamageT2=0;
        //        if (ManCountA1==0)	DamageA2=0;
        //
        //        if (Inic<0)
        //            if ((DamageT1==0 && DamageA1==0) || (DamageT2==0 && DamageA2==0))
        //                ERound = true;

        if ($Round > 50) {
            $ERound = true;
        }

        return $ERound;
    }

    /**
     * addLog
     *
     * @param integer $Round
     */
    private function addLog($Round)
    {
        $this->log .= '----------------- ROUND   '.$Round.' ----------------';
        $this->log .= View::make('battle-calc::views.log')->with('units', $this->army1->getUnits())->render();
        $this->log .= View::make('battle-calc::views.log')->with('units', $this->army2->getUnits())->render();
        $this->log .= '<br />';
    }
}
