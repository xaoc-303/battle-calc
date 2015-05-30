<?php

Route::group(Config::get('battle-calc::routes.routing'), function () {
    Route::get('/',                              ['uses' => 'Xaoc303\BattleCalc\BattleCalcController@getIndex', 'as' => 'bc.index']);
    Route::get('getArmyBase/{id_army?}/{race?}', ['uses' => 'Xaoc303\BattleCalc\BattleCalcController@getArmyBase', 'as' => 'bc.army_base']);
    Route::get('calculation',                    ['uses' => 'Xaoc303\BattleCalc\BattleCalcController@getCalculation', 'as' => 'bc.calc']);
});
