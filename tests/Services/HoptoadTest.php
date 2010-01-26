<?php
/**
 * Set include_path inside the repo
 * @todo Remove.
 */
set_include_path(dirname(dirname(dirname(__FILE__))) . ':' . get_include_path());

/**
 * @ignore
 */
require_once 'Services/Hoptoad.php';

/**
 * @ignore
 */
require_once 'PHPUnit/Framework/TestCase.php';

class Services_HoptoadTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test everything.
     *
     * @return void
     */
    public function testHoptoad()
    {
        $this->markIncomplete();
    }
}