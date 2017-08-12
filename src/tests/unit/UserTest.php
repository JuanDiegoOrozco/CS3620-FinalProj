<?php

require __DIR__.'/../../domain/User.php';

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $harness;

    protected function _before()
    {
        $this->harness = new \App\domain\User();// create
    }

    protected function _after()
    {
        unset($this->harness);// undo
    }

    // tests

    public function testUserNotNull()
    {
        $user = $this->harness;
        $this->assertTrue($user != null,"User was set to null");
    }

    public function testUserAlias()
    {
        $user = $this->harness;
        $user->SetAlias("Bob");
        $this->assertEquals("Bob",$user->GetAlias(), "Failed to set and get alias");
    }

    public function testUserEmail()
    {
        $var = "bob@gmail.com";
        $user = $this->harness;
        $user->SetEmail($var);
        $this->assertEquals($var,$user->GetEmail(),"Failed to set and get Email");
    }

    public function testBadEmail()
    {
        $catched = false;
        $var = "badEmail  @.com";
        $user = $this->harness;
        try{
            $user->SetEmail($var);
        }
        catch (Exception $e){
            $catched = true;
        }
        $this->assertTrue($catched, 'Did not catch bad email');
    }
    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    //public function count()
    //{
        // TODO: Implement count() method.
    //}

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param PHPUnit_Framework_TestResult $result
     *
     * @return PHPUnit_Framework_TestResult
     */
    //public function run(PHPUnit_Framework_TestResult $result = null)
    //{
        // TODO: Implement run() method.
    //}
}