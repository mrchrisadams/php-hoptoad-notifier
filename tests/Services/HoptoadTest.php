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
    protected $apiKey;

    public function setUp()
    {
        $conf = dirname(dirname(__FILE__)) . '/test-config.php';
        if (!file_exists($conf)) {
            $this->fail("test-config.php is required to run this.");
        }
        include $conf;
        if (empty($API_KEY)) {
            $this->fail("The \$API_KEY in test-config.php cannot be empty.");
        }
        $this->apiKey = $API_KEY;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testOldApi()
    {
        Services_Hoptoad::notify($this->apiKey, 'Lorem ipsum', 'foobar.php', 0, array());
    }

    /**
     * Test everything.
     *
     * @return void
     */
    public function testHoptoad()
    {
        // $this->markTestIncomplete("Need a test config, etc..");

        $exception = new Exception("This is a test.");

        $hoptoad = new Services_Hoptoad($this->apiKey);
        try {
            $status = $hoptoad->notifyV2($exception);
            $this->assertTrue($status);
        } catch (Exception $e) {
            var_dump($e->getCode(), $e->getRequestData());
            $this->fail($e->getMessage());
        }
    }
}