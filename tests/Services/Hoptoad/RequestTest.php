<?php
/**
 * Set include_path inside the repo
 * @todo Remove.
 */
set_include_path(dirname(dirname(dirname(dirname(__FILE__)))) . ':' . get_include_path());

/**
 * @ignore
 */
require_once 'Services/Hoptoad/Request.php';

/**
 * @ignore
 */
require_once 'PHPUnit/Framework/TestCase.php';

class Services_Hoptoad_RequestTest extends PHPUnit_Framework_TestCase
{
    public function testXmlHell()
    {
        $assert  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $assert .= '<notice version="2.0">';
        $assert .= '<notifier>';
        $assert .= '<name>Services_Hoptoad</name>';
        $assert .= '<url>http://github.com/till/php-hoptoad-notifier</url>';
        $assert .= '<version>@package_version@</version></notifier>';
        $assert .= '<error><class>LogicException</class><message>Your mom.</message><backtrace/></error>';
        $assert .= '</notice>';
        $assert .= "\n";

        $request = new Services_Hoptoad_Request;
        $request->setException(new LogicException("Your mom."));


        $this->assertEquals($assert, $request->getRequestData());
    }
}