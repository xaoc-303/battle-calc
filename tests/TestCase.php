<?php namespace Xaoc303\BattleCalc;

//class TestCase extends \Illuminate\Foundation\Testing\TestCase
class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->prepareForTests();
    }

    public function createApplication()
    {
        ini_set('memory_limit', '256M');
        $unitTesting = true;
        $testEnvironment = 'testing';
        return require __DIR__.'/../../../../bootstrap/start.php';
    }

    public function prepareForTests()
    {
    }

    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }
}
