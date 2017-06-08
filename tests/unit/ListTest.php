<?php

require 'vendor/autoload.php';
use Softremake\Path;

class ListTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

	/**
	 *  simple test for the number of files returned
	 */
    public function testDirectoryListing()
    {
	    $d = new Path(__DIR__);
	    $result = [];
	    foreach($d->files() as $f) {
	    	$result[] = $f;
	    }
	    $this->assertEquals(1, count($result));
    }
}