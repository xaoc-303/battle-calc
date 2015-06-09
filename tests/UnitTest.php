<?php namespace Xaoc303\BattleCalc;

use Mockery as m;

class UnitTest extends TestCase
{
    /**
     * @var Unit
     */
    private $unit;

    public function prepareForTests()
    {
        $this->unit = new Unit();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->unit = null;
        m::close();
    }

    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testFindById()
    {
        $result = $this->unit->findById(102);
        $this->assertContainsOnlyInstancesOf('Xaoc303\BattleCalc\Unit', [$result]);

        $result = $this->unit->findById(-1);
        $this->assertEmpty($result);
    }

    public function testFindByRaceId()
    {
        $result = $this->unit->findByRaceId(1);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf('Xaoc303\BattleCalc\Unit', [$result[0]]);

        $result = $this->unit->findByRaceId(0);
        $this->assertEmpty($result);
    }

    public function testGetUnits()
    {
        $app = m::mock('AppMock');
        $app->shouldReceive('instance')->once()->andReturn($app);
        \Illuminate\Support\Facades\Facade::setFacadeApplication($app);
        \Illuminate\Support\Facades\Config::swap($config = m::mock('ConfigMock'));
        $config->shouldReceive('get')->once()->with('battle-calc::units')
            ->andReturn($this->app['config']->get('battle-calc::units'));

        $result = $this->unit->getUnits();
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
