<?php

require dirname(__DIR__) . '/functions.php';

class StatusWidgetTest extends PHPUnit_Framework_TestCase
{

    public function testTemplateGeneration()
    {
        $this->assertTrue(function_exists('returnTopTemplate'));
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testExceptionRaisedIFArgumentsMissing()
    {
        // No arguments
        returnTopTemplate();
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testExceptionRaisedIfFirstArgumentIsNotInteger()
    {
        // First argument is not integer
        returnTopTemplate('William Shakespeare');
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testExceptionRaisedIfSecondArgumentIsNotProvided()
    {
        // Second argument is not provided
        returnTopTemplate(1);
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testExceptionRaisedIfSecondArgumentIsNotString()
    {
        // Second argument is not integer
        returnTopTemplate(1, 1);
    }

    public function testCurrentUserIDIsReturnedInClassList()
    {
        $returned_value = returnTopTemplate(1, 'William Shakespeare');

        $this->assertRegExp('/current-user-id-1/', $returned_value);
    }

    public function testGreetingShowsUserName()
    {
        $returned_value = returnTopTemplate(1, 'William Shakespeare');

        $this->assertRegExp('/Hello William Shakespeare/', $returned_value);
    }

    public function testIfIsMyPage()
    {
        $cls = returnMyPageClass('William', 'William');
        $this->assertEquals($cls, 'my-page');
    }

    public function testIfNotMyPage()
    {
        $cls = returnMyPageClass('William', 'Horatio');
        $this->assertEquals($cls, '');
    }



}
