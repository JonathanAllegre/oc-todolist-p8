<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-25
 * Time: 19:03
 */

class WebContext extends \Behat\MinkExtension\Context\MinkContext
{
    /**
     * @When a demo scenario sends a request to :arg1
     */
    public function aDemoScenarioSendsARequestTo($arg1)
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }


}