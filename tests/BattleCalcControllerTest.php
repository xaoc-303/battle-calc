<?php namespace Xaoc303\BattleCalc;

class BattleCalcControllerTest extends TestCase
{
    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testGetIndex()
    {
        $response = $this->call('GET', 'calc');
        $this->assertTrue($response->isOk());
    }

    public function testGetArmyBase()
    {
        $response = $this->call('GET', 'calc/getArmyBase/1/1');
        $this->assertTrue($response->isOk());
    }

    public function testGetCalculation()
    {
        $response = $this->call('GET', 'calc/calculation?army-1%5B101%5D=0&army-1%5B102%5D=10&army-1%5B103%5D=0&army-1%5B104%5D=0&army-1%5B105%5D=0&army-1%5B106%5D=0&army-1%5B107%5D=0&army-1%5B108%5D=0&army-1%5B109%5D=0&army-1%5B110%5D=0&army-1%5B111%5D=0&army-1%5B112%5D=0&army-1%5B113%5D=0&army-1%5B114%5D=0&race-1=1&army-2%5B201%5D=0&army-2%5B202%5D=10&army-2%5B203%5D=0&army-2%5B204%5D=0&army-2%5B205%5D=0&army-2%5B206%5D=0&army-2%5B207%5D=0&army-2%5B208%5D=0&army-2%5B209%5D=0&army-2%5B210%5D=0&army-2%5B211%5D=0&army-2%5B212%5D=0&army-2%5B213%5D=0&army-2%5B214%5D=0&race-2=2');
        $this->assertTrue($response->isOk());
    }
}
