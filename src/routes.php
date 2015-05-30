<?php

use Xaoc303\BattleCalc\BattleCalc;

Route::group(Config::get('battle-calc::routes.routing'), function(){

    Route::get('/', function(){
        return View::make('battle-calc::views.index')
            ->with('title',__CLASS__)
            ->nest('content', 'battle-calc::views.calc');
    });
    Route::get('GetArmyBase/{id_army}/{race}', function($id_army, $race){

        //dd(Config::get('battle-calc::units.'.$race));
        return View::make('battle-calc::views.getarmybase')
            ->with('army', Config::get('battle-calc::units.'.$race))
            ->with('id_army', (int) $id_army);
    });

    //Route::get('calculation', 'battle-calc::BattleCalc@Calculation');
    Route::get('calculation', function() {
        return BattleCalc::Init();
    });

});


