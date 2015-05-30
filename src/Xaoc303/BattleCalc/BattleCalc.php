<?php namespace Xaoc303\BattleCalc;

use View, Input, Config;

class BattleCalc {

    public $log = '';
    public $round = 0;


    public static function Init()
    {
        $army_1 = Input::get('army-1', []);
        $army_2 = Input::get('army-2', []);
        $race_1 = (int) Input::get('race-1');
        $race_2 = (int) Input::get('race-2');

        return (new BattleCalc())->Calculation($race_1, $army_1, $race_2, $army_2);
    }

    private function Calculation(&$race_1, &$army_1, &$race_2, &$army_2)
    {
        $army1 = $this->createArmy($army_1, $race_1);
        $army2 = $this->createArmy($army_2, $race_2);

        $this->War($army1, $army2);

        //die('<pre>'.print_r($army1,true).'<pre>');
        return $this->log;

    }

    private function getUnits($race)
    {
        return array_map(function($v) { return (object) $v; }, Config::get('battle-calc::units.'.$race));
    }

    private function createArmy(&$input_army, $race)
    {
        $units = $this->getUnits($race);

        $army = array();
        $masUnitTownDistruct = array(104, 204, 308);

        foreach ($units as $unit){
            if ($input_army[$unit->id] > 0)  // количество
            {
                $base = array();
                $base['ID']            = $unit->id;               // id
                $base['ManCount']      = $input_army[$unit->id];  // Количество
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

                $battle['Iniciative'] = $unit->init;	// Инициатива
                $battle['UT'] = $unit->type;         // Тип

                $battle['Bio'] = $unit->bio;         // био-тип

                $base['Shield'] = $unit->shield;  // Щит
                $battle['Shield'] = $base['Shield'] * $base['ManCount'];

                $base['Armor'] = $unit->armor;    // Броня
                $battle['Armor'] = $base['Armor'] * $base['ManCount'];

                $base['HP'] = $unit->hp;    // Здоровье
                $battle['HP'] = $base['HP'] * $base['ManCount'];

                $battle['Magic'][0] = null;
                $battle['Magic'][1] = null;
                $battle['Magic'][2] = null;

                $battle['MagicRound'] = $unit->mround;    // Магическая атака
                $battle['AttackMagicAll'] = 0;
                $battle['AttackMagicShield'] = 0;
                $battle['AttackMagicHP'] = 0;

                $base['AttackCool'] = $unit->cool;    // атака задержка
                $battle['AttackCoolInt'] = 0;
                $battle['AttackCoolDouble'] = 0;

                $base['AttackTer'] = $unit->attack_ter;    // атака земля
                $battle['AttackTer'] = 0;

                $base['AttackAir'] = $unit->attack_air;    // атака воздух
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

                $army[] = array(
                    'Base' => $base,
                    'All'  => $battle
                );
            }
        }

        return $army;

    }

    private function War(&$Army1, &$Army2)
    {
        $MaxIniciative = 0;

        $ManCount1 = $this->ArmyMax($Army1, $MaxIniciative);
        $ManCount2 = $this->ArmyMax($Army2, $MaxIniciative);

        $Round = 0;
        $this->round = $Round;
        $this->Log($Army1, $Army2, $Round);

        while (($ManCount1 > 0) && ($ManCount2 > 0)){

            //die('<pre>'.print_r($Army1,true).'</pre>');
            $this->ArmyShadow($Army1, $Army2, $MaxIniciative);
            $this->ArmyShadow($Army2, $Army1, $MaxIniciative);

            $this->ArmyNull($Army1);
            $this->ArmyNull($Army2);

            $this->ArmyAttack($Army1, $Army2, $MaxIniciative);
            $this->ArmyAttack($Army2, $Army1, $MaxIniciative);

            //$q1 = $this->ArmyVisible($Army2, 1);
            //$q2 = $this->ArmyVisible($Army1, 1);

            //die($q1.' ::: '.$q2);

            $this->ArmyAttackNull($Army1, $this->ArmyVisible($Army2, 1));
            $this->ArmyAttackNull($Army2, $this->ArmyVisible($Army1, 1));

            $this->ArmyColor($Army1);
            $this->ArmyColor($Army2);

            if ($this->ExitRound($Army1, $Army2, $MaxIniciative, $Round))
                break;

            $this->ArmyDamage($Army2, $Army1);
            $this->ArmyDamage($Army1, $Army2);

            $ManCount1 = $this->ArmyCount($Army1);
            $ManCount2 = $this->ArmyCount($Army2);

            if ($Round == 700)
            {
                echo '$Round = '.$Round.'<br />';
                echo '$MaxIniciative = '.$MaxIniciative.'<br />';
                echo '$ManCount1 = '.$ManCount1.'<br />';
                echo '$ManCount2 = '.$ManCount2.'<br />';
                echo '<pre>'.print_r($Army1,true).'<pre>';
                echo '<pre>'.print_r($Army2,true).'<pre>';
                exit;
            }

            $Round++;
            $this->round = $Round;
            $MaxIniciative--;
            $this->Log($Army1, $Army2, $Round);
            //if ($this->round==6) exit;
        }

        //die($ManCount1.' ::: '.$ManCount2);
    }

    private function ArmyMax(&$Army, &$Inic)
    {
        $ManCount = 0;
        $count = count($Army);
        for ($i = 0; $i < $count; $i++)
        {
            $ManCount += $Army[$i]['All']['ManCount'];
            if ($Army[$i]['All']['Iniciative'] > $Inic AND $Army[$i]['All']['ManCount'] > 0) {
                $Inic = $Army[$i]['All']['Iniciative'];
            }
        }

        return $ManCount;
    }

    private function ArmyNull(&$Army)
    {
        $count = count($Army);
        for ($i = 0; $i < $count; $i++)
        {
            $Army[$i]['All']['Color']             = 0;
            $Army[$i]['All']['AttackTer']         = 0;
            $Army[$i]['All']['AttackAir']         = 0;
            $Army[$i]['All']['AttackMagicAll']    = 0;
            $Army[$i]['All']['AttackMagicShield'] = 0;
            $Army[$i]['All']['AttackMagicHP']     = 0;
            $Army[$i]['All']['ManLock']           = 0;
            $Army[$i]['All']['Magic'][0]          = null;
            $Army[$i]['All']['Magic'][1]          = null;
            $Army[$i]['All']['Magic'][2]          = null;
            $Army[$i]['All']['HealingLive']       = 0;
            $Army[$i]['All']['HealingTech']       = 0;
        }
    }

    private function ArmyAttack(&$Army, &$ArmyDef, $Inic)
    {
        $Cool = 0;
        $count = count($Army);

        for ($i = 0; $i < $count; $i++)
        {
            if ($Army[$i]['All']['Iniciative'] >= $Inic AND $Army[$i]['All']['ManCount']>0)
            {
                $Cool = 24.0 / $Army[$i]['Base']['AttackCool'];			// Атак в секунду
                $Cool = round($Cool, 2);								// Округление до сотых
                $Cool += $Army[$i]['All']['AttackCoolDouble'];			// + предыдущий остаток
                $Army[$i]['All']['AttackCoolInt'] = (int) $Cool;		// int атак в секунду
                $Army[$i]['All']['AttackCoolDouble'] = $Cool - $Army[$i]['All']['AttackCoolInt'];	// double остаток атак в секунду
                if ($Army[$i]['All']['AttackCoolInt'] > 0)
                {
                    $Army[$i]['All']['AttackAir'] = ($Army[$i]['Base']['AttackAir'] * $Army[$i]['All']['ManCount']) * $Army[$i]['All']['AttackCoolInt'];
                    $Army[$i]['All']['AttackTer'] = ($Army[$i]['Base']['AttackTer'] * $Army[$i]['All']['ManCount']) * $Army[$i]['All']['AttackCoolInt'];

                    if ($Army[$i]['All']['MagicRound'] > 0)
                        for ($k = 0; $k < 3; $k++)
                        {
                            $Army[$i]['All']['Magic'][$k] = $Army[$i]['Base']['Magic'][$k];
                            $this->ArmyMagicAttack($i, $k, $Army, $ArmyDef);
                        }
                    $Army[$i]['All']['MagicRound']--;
                    $Army[$i]['All']['MagicRound'] = $this->UnitMagicRound($Army[$i]['Base']['ID'], $Army[$i]['All']['MagicRound']);
                }
            }
        }
    }

    private function ArmyMagicAttack($i, $k, &$ArmyAtt, &$ArmyDef)
    {

        $ManCount;  //int
        $ManCountB; //bool

        if ($ArmyAtt[$i]['All']['Magic'][$k] != null AND $ArmyAtt[$i]['All']['Magic'][$k] != 'Steam')
        {
            $ArmyAtt[$i]['All']['AttackAir'] = 0;
            $ArmyAtt[$i]['All']['AttackTer'] = 0;
        }

        switch	($ArmyAtt[$i]['All']['Magic'][$k])
        {
            // -------------------------------
            case	'Storm':
                $ArmyAtt[$i]['All']['AttackMagicAll'] = 250 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'FogP':
                $ManCount = $ArmyAtt[$i]['All']['MagicManCount'] * $ArmyAtt[$i]['All']['ManCount'];
                while ($ManCount > 0)
                {
                    $ManCountB = true;
                    $count = count($ArmyDef);
                    for ($i1 = 0; $i1 < $count; $i1++)
                        if ($ArmyDef[$i1]['All']['ManCount'] > 0 AND $ArmyDef[$i1]['All']['ManLock'] != $ArmyDef[$i1]['All']['ManCount'])
                        {
                            $ArmyDef[$i1]['All']['ManLock']++;
                            $ManCount--;
                            $ManCountB = false;
                            if ($ManCount == 0) $i1 = $count;
                        }
                    if ($ManCountB) $ManCount = 0;
                }
                break;
            case	'MindControl':
                break;
            case	'LockP':
                $ManCount = $ArmyAtt[$i]['All']['MagicManCount'] * $ArmyAtt[$i]['All']['ManCount'];
                while ($ManCount > 0)
                {
                    $ManCountB = true;
                    $count = count($ArmyDef);
                    for ($i1 = 0; $i1 < $count; $i1++)
                        if ($ArmyDef[$i1]['All']['ManCount'] > 0 AND $ArmyDef[$i1]['All']['ManLock'] != $ArmyDef[$i1]['All']['ManCount'])
                        {
                            $ArmyDef[$i1]['All']['ManLock']++;
                            $ManCount--;
                            $ManCountB = false;
                            if ($ManCount == 0) $i1 = $count;
                        }
                    if ($ManCountB) $ManCount = 0;
                }
                break;
            case	'Jump':
                break;
            case	'Phantom':
                $ArmyAtt[$i]['All']['ManCount']++;
                $ArmyAtt[$i]['All']['ManCountVisible']++;
                $ArmyAtt[$i]['All']['ManPhantom']++;
                $ArmyAtt[$i]['All']['HP'] += $ArmyAtt[$i]['Base']['HP'];
                $ArmyAtt[$i]['All']['Shield'] += $ArmyAtt[$i]['Base']['Shield'];
                break;
            case	'Scarab':
                $ArmyAtt[$i]['All']['AttackTer'] = 100 * $ArmyAtt[$i]['All']['ManCount'];
                break;


            // -------------------------------
            case	'Remont':
                $ArmyAtt[$i]['All']['HealingTech'] = 50 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'Medicine':
                $ArmyAtt[$i]['All']['HealingLive'] = 80 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'Blind':
                $ManCount = 3 * $ArmyAtt[$i]['All']['ManCount'];
                while ($ManCount > 0)
                {
                    $ManCountB = true;
                    $count = count($ArmyDef);
                    for ($i1 = 0; $i1 < $count; $i1++)
                        if (in_array($ArmyDef[$i1]['All']['ID'], [105, 109, 204, 213, 305]))
                            if ($ArmyDef[$i1]['All']['ManCount'] > 0 && $ArmyDef[$i1]['All']['ManCountVisible'] != $ArmyDef[$i1]['All']['ManCount'])
                            {
                                $ArmyDef[$i1]['All']['ManCountVisible']++;
                                $ManCount--;
                                $ManCountB = false;
                                if ($ManCount == 0) $i1 = $count;
                            }
                    if ($ManCountB) $ManCount = 0;
                }
                break;
            case	'Steam':
                if  ($ArmyAtt[$i]['All']['HP']/$ArmyAtt[$i]['All']['ManCount'] > 16)
                {
                    $ArmyAtt[$i]['All']['AttackAir'] = $ArmyAtt[$i]['All']['AttackAir'] * 2;
                    $ArmyAtt[$i]['All']['AttackTer'] = $ArmyAtt[$i]['All']['AttackTer'] * 2;
                    $ArmyAtt[$i]['All']['HP'] = $ArmyAtt[$i]['All']['HP'] - ( 8 * $ArmyAtt[$i]['All']['ManCount']);
                }
                break;
            case	'Yamato':
                $ArmyAtt[$i]['All']['AttackMagicAll'] = 260 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'Nuclear':
                $ArmyAtt[$i]['All']['AttackMagicAll'] = 300 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'LockT':
                break;
            case	'Matrix':
                break;
            case	'EMI':
                $ArmyAtt[$i]['All']['AttackMagicShield'] = 250 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'Radiation':
                $ArmyAtt[$i]['All']['AttackMagicHP'] = 100 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'Mines':
                $ArmyAtt[$i]['All']['AttackTer'] = 125 * $ArmyAtt[$i]['All']['ManCount'];
                break;

            // -------------------------------
            case	'FogZ':
                break;
            case	'Marker':
                break;
            case	'Plague':
                $ArmyAtt[$i]['All']['AttackMagicHP'] = 145 * $ArmyAtt[$i]['All']['ManCount'];
                break;
            case	'Guest':
                break;
            case	'Web':
                break;
        }

    }

    private function ArmyAttackNull(&$Army, $ManCountA)
    {
        //die('ArmyAttackNull::ok1');
        $count = count($Army);
        for ($i = 0; $i < $count; $i++)
        {
            if ($Army[$i]['All']['ManCount'] > 0)
            {
                if	($Army[$i]['All']['AttackAir'] > 0 AND $Army[$i]['All']['AttackTer'] AND $ManCountA > 0){
                    $Army[$i]['All']['AttackTer'] = 0;
                } else {
                    $Army[$i]['All']['AttackAir'] = 0;
                }

                if ($Army[$i]['All']['ManLock'] > 0)
                {
                    $Army[$i]['All']['AttackAir']           = $Army[$i]['All']['AttackAir']			/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManLock']);
                    $Army[$i]['All']['AttackTer']           = $Army[$i]['All']['AttackTer']			/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManLock']);
                    $Army[$i]['All']['AttackMagicAll']      = $Army[$i]['All']['AttackMagicAll']    / $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManLock']);
                    $Army[$i]['All']['AttackMagicShield']   = $Army[$i]['All']['AttackMagicShield']	/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManLock']);
                    $Army[$i]['All']['AttackMagicHP']       = $Army[$i]['All']['AttackMagicHP']		/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManLock']);
                }
                if ($Army[$i]['All']['ManPhantom'] > 0)
                {
                    $Army[$i]['All']['AttackAir']           = $Army[$i]['All']['AttackAir']			/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManPhantom']);
                    $Army[$i]['All']['AttackTer']           = $Army[$i]['All']['AttackTer']			/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManPhantom']);
                    $Army[$i]['All']['AttackMagicAll']      = $Army[$i]['All']['AttackMagicAll']    / $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManPhantom']);
                    $Army[$i]['All']['AttackMagicShield']   = $Army[$i]['All']['AttackMagicShield']	/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManPhantom']);
                    $Army[$i]['All']['AttackMagicHP']       = $Army[$i]['All']['AttackMagicHP']		/ $Army[$i]['All']['ManCount'] * ($Army[$i]['All']['ManCount']-$Army[$i]['All']['ManPhantom']);
                }
            }
        }
    }

    private function ArmyShadow(&$ArmySh, &$ArmyDet, $Round)
    {
        $ManCount;
        $ManCountB;


        // set shadow
        $count = count($ArmySh);
        for ($i = 0; $i < $count; $i++)
        {
            $ArmySh[$i]['All']['ManCountVisible'] = $ArmySh[$i]['All']['ManCount'];

            if ($ArmySh[$i]['All']['ManCount'] > 0){
                switch ($ArmySh[$i]['Base']['ID']){
                    case	105:    // Dark Templar
                    case	109:    // Observer
                        $ArmySh[$i]['All']['ManCountVisible'] = 0;
                        break;
                    case	114:    // Arbiter
                        // todo: проверить
                        $ManCount = $ArmySh[$i]['All']['MagicManCount'] * $ArmySh[$i]['All']['ManCount'];
                        while ($ManCount > 0){
                            $ManCountB = true;

                            for ($i1 = 0; $i1 < count($ArmySh); $i1++){
                                if ($i1 != $i && $ArmySh[$i1]['All']['ManCount'] > 0 && $ArmySh[$i1]['All']['ManCountVisible'] != 0){
                                    $ArmySh[$i1]['All']['ManCountVisible']--;
                                    $ManCount--;
                                    $ManCountB = false;
                                    if ($ManCount == 0) $i1 = count($ArmySh);
                                }
                            }

                            if ($ManCountB) $ManCount = 0;
                        }
                        $i = count($ArmySh);
                        break;
                    case	204:    // Ghost
                        // todo: проверить
                        if ($Round > $ArmySh[$i]['All']['Iniciative'] - 20){
                            $ArmySh[$i]['All']['ManCountVisible'] = 0;
                        } else {
                            $ArmySh[$i]['All']['ManCountVisible'] = $ArmySh[$i]['All']['ManCount'];
                        }
                        break;
                    case	213:    // Writh
                        // todo: проверить
                        if ($Round > $ArmySh[$i]['All']['Iniciative'] - 20){
                            $ArmySh[$i]['All']['ManCountVisible'] = 0;
                        } else {
                            $ArmySh[$i]['All']['ManCountVisible'] = $ArmySh[$i]['All']['ManCount'];
                        }
                        break;
                    case	305:    // Lurker
                        // todo: проверить
                        if ($Round < $ArmySh[$i]['All']['Iniciative'] - 2) {
                            $ArmySh[$i]['All']['ManCountVisible'] = 0;
                        }
                        break;
                }
            }
        }

        $ManCount = 0;
        $count = count($ArmyDet);
        for ($i = 0; $i < $count; $i++)
            if (in_array($ArmyDet[$i]['Base']['ID'], [109, 211, 309]) AND $ArmyDet[$i]['All']['ManCount'] > 0) {
                $ManCount += $ArmyDet[$i]['All']['MagicManCount'] * $ArmyDet[$i]['All']['ManCount'];
            }

        while ($ManCount > 0)
        {
            $ManCountB = true;
            $count = count($ArmySh);
            for ($i = 0; $i < $count; $i++)
                if ($ArmySh[$i]['All']['ManCount'] > 0 && $ArmySh[$i]['All']['ManCountVisible'] != $ArmySh[$i]['All']['ManCount'])
                {
                    $ArmySh[$i]['All']['ManCountVisible']++;
                    $ManCount--;
                    $ManCountB = false;
                    if ($ManCount == 0) $i = count($ArmySh);
                }
            if ($ManCountB) $ManCount = 0;
        }
    }

    private function ArmyVisible(&$Army, $type)
    {
        $ManCountVisible = 0;
        $count = count($Army);
        for ($i = 0; $i < $count; $i++)
            if ($Army[$i]['All']['UT'] == $type)
                $ManCountVisible += $Army[$i]['All']['ManCountVisible'];
        return $ManCountVisible;
    }

    private function ArmyColor(&$Army)
    {
        $count = count($Army);
        for ($i = 0; $i < $count; $i++)
        {
            if ($Army[$i]['All']['AttackAir'] != 0 OR $Army[$i]['All']['AttackTer'] != 0)
                $Army[$i]['All']['Color'] = 1;
            for ($k = 0; $k < 3; $k++)
                if ($Army[$i]['All']['Magic'][$k] != null)
                    $Army[$i]['All']['Color'] = 2;
        }
    }

    private function ExitRound(&$Army1, &$Army2, $Inic, $Round)
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

        if ($Round==50)	$ERound = true;

        return $ERound;
    }

    private function UnitMagicRound($id, $round)
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

        return $rounds[$id];
    }

    private function ArmyDamage(&$ArmyAtt, &$ArmyDef)
    {
        //die('<pre>'.print_r($ArmyAtt,true).'<pre>');
        $DamageTer = 0;
        $DamageAir = 0;
        $DamageMagicAll = 0;
        $DamageMagicShield = 0;
        $DamageMagicHP = 0;

        $ManCountT_Def = 0;
        $ManCountA_Def = 0;

        $ManDamageT = 0;
        $ManDamageA = 0;
        $ManDamageAll = 0;
        $ManDamageShield = 0;
        $ManDamageHP = 0;

        $HealingLive = 0;
        $HealingTech = 0;


        //	int iRand;
        //	rand;rand;rand;
        //	iRand=ArmyDef.size()-1;
        //	if (iRand>0) iRand=rand()%iRand;
        //	iRand=iRand;

        $count = count($ArmyAtt);
        for ($i=0; $i<$count; $i++)
        {
            $DamageTer			+= $ArmyAtt[$i]['All']['AttackTer'];
            $DamageAir			+= $ArmyAtt[$i]['All']['AttackAir'];
            $DamageMagicAll		+= $ArmyAtt[$i]['All']['AttackMagicAll'];
            $DamageMagicShield	+= $ArmyAtt[$i]['All']['AttackMagicShield'];
            $DamageMagicHP		+= $ArmyAtt[$i]['All']['AttackMagicHP'];
        }


        $count = count($ArmyDef);
        for ($i = 0; $i < $count; $i++)
        {
            if ($ArmyDef[$i]['All']['UT']==0)		$ManCountT_Def += $ArmyDef[$i]['All']['ManCountVisible'];
            if ($ArmyDef[$i]['All']['UT']==1)		$ManCountA_Def += $ArmyDef[$i]['All']['ManCountVisible'];
            $HealingLive        += $ArmyDef[$i]['All']['HealingLive'];
            $HealingTech        += $ArmyDef[$i]['All']['HealingTech'];
        }

        if ($DamageTer==0 || $ManCountT_Def==0)	{$ManDamageT = 0;}
        if ($DamageTer>0 && $ManCountT_Def>0)	{$ManDamageT = $DamageTer/$ManCountT_Def;}

        if ($DamageAir==0 || $ManCountA_Def==0)	{$ManDamageA = 0;}
        if ($DamageAir>0 && $ManCountA_Def>0)	{$ManDamageA = $DamageAir/$ManCountA_Def;}   // среднее на каждгого. воздух

        $count = count($ArmyDef);
        for ($i = 0; $i < $count; $i++)
        {
            if ($ArmyDef[$i]['All']['ManCountVisible'] > 0 || $DamageMagicAll > 0 || $DamageMagicShield > 0 || $DamageMagicHP > 0)
            {

                $ManDamageAll    = 0;
                $ManDamageShield = 0;
                $ManDamageHP     = 0;
                if ($ArmyDef[$i]['All']['ManCountVisible']>0)
                {
                    if ($ArmyDef[$i]['All']['UT']==0)
                    {$ManDamageAll = ($ManDamageT-(($ManDamageT* $ArmyDef[$i]['Base']['Armor'])/100))* $ArmyDef[$i]['All']['ManCount'];}
                    else
                    {$ManDamageAll = ($ManDamageA-(($ManDamageA* $ArmyDef[$i]['Base']['Armor'])/100))* $ArmyDef[$i]['All']['ManCount'];}
                }
                if ($DamageMagicAll > 0)       $ManDamageAll   += $DamageMagicAll     -(($DamageMagicAll   * $ArmyDef[$i]['Base']['Armor'])/100);
                if ($DamageMagicShield > 0)    $ManDamageShield+= $DamageMagicShield  -(($DamageMagicShield* $ArmyDef[$i]['Base']['Armor'])/100);
                if ($DamageMagicHP > 0)        $ManDamageHP    += $DamageMagicHP      -(($DamageMagicHP    * $ArmyDef[$i]['Base']['Armor'])/100);

                $ArmyDef[$i]['All']['Shield'] -= $ManDamageShield;
                if ($ArmyDef[$i]['All']['Shield'] < 0)	$ArmyDef[$i]['All']['Shield'] = 0;


                $HPmax = $ArmyDef[$i]['Base']['HP'] * $ArmyDef[$i]['All']['ManCount'];

                $ArmyDef[$i]['All']['HP'] -= $ManDamageHP;
                if ($ArmyDef[$i]['All']['HP'] < 0)	$ArmyDef[$i]['All']['HP'] = 0;

                $ArmyDef[$i]['All']['Shield'] -= $ManDamageAll;
                if ($ArmyDef[$i]['All']['Shield'] < 0)
                {
                    $ArmyDef[$i]['All']['HP'] += $ArmyDef[$i]['All']['Shield'];
                    $ArmyDef[$i]['All']['Shield'] = 0;
                }

                if ($HealingLive > 0)
                    if ($ArmyDef[$i]['All']['bio'])
                        if ($ArmyDef[$i]['All']['HP'] < $HPmax)
                        {
                            $HPtemp = $HPmax - $ArmyDef[$i]['All']['HP'];
                            if ($HealingLive >= $HPtemp)
                            {
                                $HealingLive -= $HPtemp;
                                $ArmyDef[$i]['All']['HP'] += $HPtemp;
                            }
                        }

                if ($HealingTech > 0)
                    if (!$ArmyDef[$i]['All']['Bio'])
                        if ($ArmyDef[$i]['All']['HP'] < $HPmax)
                        {
                            $HPtemp = $HPmax - $ArmyDef[$i]['All']['HP'];
                            if ($HealingTech >= $HPtemp)
                            {
                                $HealingTech -= $HPtemp;
                                $ArmyDef[$i]['All']['HP'] += $HPtemp;
                            }
                        }


                if ($ArmyDef[$i]['All']['HP'] < 0) $ArmyDef[$i]['All']['HP'] = 0;


                //                if ($this->round==5)
                //                {
                //                    echo 'AttackTer='.$ArmyAtt[0]['All']['AttackTer'].'<br />';
                //
                //                    echo '$DamageMagicAll='.$DamageMagicAll.'<br />';
                //                    echo '$DamageMagicShield='.$DamageMagicShield.'<br />';
                //                    echo '$DamageMagicHP='.$DamageMagicHP.'<br />';
                //
                //                    echo '$ManDamageAll='.$ManDamageAll.'<br />';
                //                    echo '$ManDamageShield='.$ManDamageShield.'<br />';
                //                    echo '$ManDamageHP='.$ManDamageHP.'<br />';
                //                }
            }
        }

    }

    private function ArmyCount(&$Army)
    {
        $ManCount = 0;
        $count = count($Army);
        for ($i = 0; $i < $count; $i++)
        {
            $ManCountTemp = $Army[$i]['All']['ManCount'];
            $Army[$i]['All']['ManCount'] = (int) ($Army[$i]['All']['HP'] / $Army[$i]['Base']['HP']);

            if ($Army[$i]['All']['HP'] % $Army[$i]['Base']['HP'] > 0) $Army[$i]['All']['ManCount']++;
            if ($Army[$i]['All']['ManPhantom'] > 0 AND $ManCountTemp>$Army[$i]['All']['ManCount']) $Army[$i]['All']['ManPhantom']--;

            $ManCount += $Army[$i]['All']['ManCount'];
        }
        return $ManCount;
    }

    private function Log(&$Army1, &$Army2, $Round)
    {
        $this->log .= '----------------- ROUND   '.$Round.' ----------------';
        $this->log .= View::make('battle-calc::views.log')->with('Army', $Army1)->render();
        $this->log .= View::make('battle-calc::views.log')->with('Army', $Army2)->render();
        $this->log .= '<br />';
    }

}