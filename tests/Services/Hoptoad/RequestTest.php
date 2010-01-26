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
        $assert .= '<request>';
        $assert .= '<url>/root/exploit -success</url>';
        $assert .= '<component/>';
        $assert .= '<action/>';
        $assert .= '<cgi-data>';
        $assert .= '<var key="foo">bar</var>';
        $assert .= '<var key="ruby">is annoying</var>';
        $assert .= '</cgi-data>';
        $assert .= '</request>';
        $assert .= '</notice>';
        $assert .= "\n";

        unset($_ENV);

        $_ENV         = array();
        $_ENV['foo']  = 'bar';
        $_ENV['ruby'] = 'is annoying';

        unset($_SERVER['argv']);

        $_SERVER['argv']   = array();
        $_SERVER['argv'][] = '/root/exploit';
        $_SERVER['argv'][] = '-success';

        // for a clean unit test
        unset($_REQUEST);
        unset($_SESSION);        

        $request = new Services_Hoptoad_Request;
        $request->setException(new LogicException("Your mom."));


        $this->assertEquals($assert, $request->getRequestData());
    }
}