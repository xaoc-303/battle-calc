<?php namespace Xaoc303\BattleCalc;

use View;
use Config;
use Lang;
use Input;

/**
 * Class BattleCalcController
 * @package Xaoc303\BattleCalc
 */
class BattleCalcController extends \Controller
{
    /**
     * getIndex
     *
     * @return string
     */
    public function getIndex()
    {
        return View::make('battle-calc::views.index')
            ->with('title', __CLASS__)
            ->nest('content', 'battle-calc::views.calc', ['races' => Lang::get('battle-calc::races')]);
    }

    /**
     * getArmyBase
     *
     * @param integer $id_army
     * @param integer $race
     * @return string
     */
    public function getArmyBase($id_army, $race)
    {
        $units = Config::get('battle-calc::units');
        return View::make('battle-calc::views.getarmybase')
            ->with('units', array_where($units, function($key, $value) use ($race) {
                return $value['race_id'] == $race;
            }))
            ->with('id_army', (int) $id_army);
    }

    /**
     * getCalculation
     *
     * @return string
     */
    public function getCalculation()
    {
        $army_1 = Input::get('army-1', []);
        $army_2 = Input::get('army-2', []);

        return (new BattleCalc())->calculation($army_1, $army_2);
    }
}
