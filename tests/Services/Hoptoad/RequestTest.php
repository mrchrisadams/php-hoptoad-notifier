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
    public static function validProvider()
    {
        $request = new Services_Hoptoad_Request('1234');
        $request->setException(new LogicException("Your mom."));
        $request->setEnvironment('testing');

        $xmhell = (string) $request;

        $example  = '<?xml version="1.0" encoding="UTF-8"?>';
        $example .= '<notice version="2.0">';
        $example .= '<api-key>1234</api-key>';
        $example .= '<notifier>';
        $example .= '<name>Services_Hoptoad</name>';
        $example .= '<url>http://github.com/till/php-hoptoad-notifier</url>';
        $example .= '<version>@package_version@</version>';
        $example .= '</notifier>';
        $example .= '<error>';
        $example .= '<class>Exception</class>';
        $example .= '<message>Unknown error.2</message>';
        $example .= '<backtrace>';
        $example .= '<line method="errorAction" file="/home/till/www/example.org/library/Zend/Controller/Action.php" number="512"/>';
        $example .= '<line method="dispatch" file="/home/till/www/example.org/library/Zend/Controller/Dispatcher/Standard.php" number="288"/>';
        $example .= '<line method="dispatch" file="/home/till/www/example.org/library/Zend/Controller/Front.php" number="945"/>';
        $example .= '<line method="dispatch" file="/home/till/www/example.org/www/index.php" number="28"/>';
        $example .= '</backtrace>';
        $example .= '</error>';
        $example .= '<request>';
        $example .= '<url>http://example.org/error/error</url>';
        $example .= '<component/>';
        $example .= '<action/>';
        $example .= '<params>';
        $example .= '<var key="apaWarning">open</var>';
        $example .= '<var key="__qca">P0-330119248-1255400781182</var>';
        $example .= '<var key="__utmz">91521166.1260292626.6.2.utmccn=(organic)|utmcsr=google|utmctr=easy|utmcmd=organic</var>';
        $example .= '<var key="mla7Bubble">closed</var>';
        $example .= '<var key="__utma">91521166.1285527173.1255400781.1263316165.1263401345.12</var>';
        $example .= '<var key="__utmv">91521166.|1=SchoolId=269=1,</var>';
        $example .= '<var key="_csoot">1263582111310</var>';
        $example .= '<var key="_csuid">491579c73d6e0023</var>';
        $example .= '<var key="BIBSESSID">bc40d9f18bd0f249034afabe0f933896</var>';
        $example .= '<var key="formHelpBar">open</var>';
        $example .= '<var key="__utmc">229238939</var>';
        $example .= '<var key="__utmb">229238939.1.10.1264541972</var>';
        $example .= '</params>';
        $example .= '<session>';
        $example .= '<var key="u">r</var>';
        $example .= '</session>';
        $example .= '<cgi-data>';
        $example .= '<var key="SHELL">/bin/bash</var>';
        $example .= '<var key="TERM">xterm</var>';
        $example .= '<var key="USER">root</var>';
        $example .= '<var key="LS_COLORS">no=00:fi=00:di=01;34:ln=01;36:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:su=37;41:sg=30;43:tw=30;42:ow=34;42:st=37;44:ex=01;32:*.tar=01;31:*.tgz=01;31:*.svgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.lzma=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.dz=01;31:*.gz=01;31:*.bz2=01;31:*.bz=01;31:*.tbz2=01;31:*.tz=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.rar=01;31:*.ace=01;31:*.zoo=01;31:*.cpio=01;31:*.7z=01;31:*.rz=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.svg=01;35:*.mng=01;35:*.pcx=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.m2v=01;35:*.mkv=01;35:*.ogm=01;35:*.mp4=01;35:*.m4v=01;35:*.mp4v=01;35:*.vob=01;35:*.qt=01;35:*.nuv=01;35:*.wmv=01;35:*.asf=01;35:*.rm=01;35:*.rmvb=01;35:*.flc=01;35:*.avi=01;35:*.fli=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.yuv=01;35:*.aac=00;36:*.au=00;36:*.flac=00;36:*.mid=00;36:*.midi=00;36:*.mka=00;36:*.mp3=00;36:*.mpc=00;36:*.ogg=00;36:*.ra=00;36:*.wav=00;36:</var>';
        $example .= '<var key="SUDO_USER">till</var>';
        $example .= '<var key="SUDO_UID">1000</var>';
        $example .= '<var key="USERNAME">root</var>';
        $example .= '<var key="PHP_FCGI_CHILDREN">2</var>';
        $example .= '<var key="PATH">/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/X11R6/bin</var>';
        $example .= '<var key="MAIL">/var/mail/till</var>';
        $example .= '<var key="PWD">/home/till/www/example.org</var>';
        $example .= '<var key="LANG">en_US.UTF-8</var>';
        $example .= '<var key="SHLVL">1</var>';
        $example .= '<var key="SUDO_COMMAND">/etc/init.d/php-fcgid restart</var>';
        $example .= '<var key="HOME">/home/till</var>';
        $example .= '<var key="LOGNAME">root</var>';
        $example .= '<var key="SUDO_GID">1000</var>';
        $example .= '<var key="PHP_FCGI_MAX_REQUESTS">100</var>';
        $example .= '<var key="_">/usr/bin/php-cgi</var>';
        $example .= '<var key="FCGI_ROLE">RESPONDER</var>';
        $example .= '<var key="QUERY_STRING"></var>';
        $example .= '<var key="REQUEST_METHOD">GET</var>';
        $example .= '<var key="CONTENT_TYPE"></var>';
        $example .= '<var key="CONTENT_LENGTH"></var>';
        $example .= '<var key="SCRIPT_NAME">/error/error</var>';
        $example .= '<var key="REQUEST_URI">/error/error</var>';
        $example .= '<var key="DOCUMENT_URI">/error/error</var>';
        $example .= '<var key="DOCUMENT_ROOT">/home/till/www/example.org/www</var>';
        $example .= '<var key="SERVER_PROTOCOL">HTTP/1.1</var>';
        $example .= '<var key="GATEWAY_INTERFACE">CGI/1.1</var>';
        $example .= '<var key="SERVER_SOFTWARE">nginx/0.6.35</var>';
        $example .= '<var key="REMOTE_ADDR">88.130.162.193</var>';
        $example .= '<var key="REMOTE_PORT">46075</var>';
        $example .= '<var key="SERVER_ADDR">209.20.74.4</var>';
        $example .= '<var key="SERVER_PORT">80</var>';
        $example .= '<var key="SERVER_NAME">example.org</var>';
        $example .= '<var key="REDIRECT_STATUS">200</var>';
        $example .= '<var key="SCRIPT_FILENAME">/home/till/www/example.org/www/index.php</var>';
        $example .= '<var key="HTTP_HOST">example.org</var>';
        $example .= '<var key="HTTP_CONNECTION">keep-alive</var>';
        $example .= '<var key="HTTP_USER_AGENT">Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/532.8 (KHTML, like Gecko) Chrome/4.0.302.2 Safari/532.8</var>';
        $example .= '<var key="HTTP_CACHE_CONTROL">max-age=0</var>';
        $example .= '<var key="HTTP_ACCEPT">application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5</var>';
        $example .= '<var key="HTTP_ACCEPT_ENCODING">gzip,deflate,sdch</var>';
        $example .= '<var key="HTTP_COOKIE">apaWarning=open; __qca=P0-330119248-1255400781182; __utmz=91521166.1260292626.6.2.utmccn=(organic)|utmcsr=google|utmctr=easy|utmcmd=organic; __utmz=229238939.1260540106.1.1.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none); mla7Bubble=closed; __utma=91521166.1285527173.1255400781.1263316165.1263401345.12; __utmv=91521166.|1=SchoolId=269=1,; _csoot=1263582111310; _csuid=491579c73d6e0023; BIBSESSID=bc40d9f18bd0f249034afabe0f933896; formHelpBar=open; __utma=229238939.1579803473.1260540106.1264524518.1264541972.23; __utmc=229238939; __utmv=229238939.|1=SchoolId=269=1,; __utmb=229238939.1.10.1264541972</var>';
        $example .= '<var key="HTTP_ACCEPT_LANGUAGE">en-US,en;q=0.8</var>';
        $example .= '<var key="HTTP_ACCEPT_CHARSET">ISO-8859-1,utf-8;q=0.7,*;q=0.3</var>';
        $example .= '<var key="PHP_SELF">/error/error</var>';
        $example .= '<var key="REQUEST_TIME">1264542569</var>';
        $example .= '<var key="argc">0</var>';
        $example .= '</cgi-data>';
        $example .= '</request>';
        $example .= '<server-environment>';
        $example .= '<project-root>/home/till/www/example.org/www</project-root>';
        $example .= '<environment-name>till</environment-name>';
        $example .= '</server-environment>';
        $example .= '</notice>';

        return array(
            array($xmhell),
            array($example),
        );
    }

    /**
     * Validate the request generated.
     *
     * @param string $assert The XML data.
     *
     * @dataProvider validProvider
     *
     * @return void
     */
    public function testIfTheRequestIsValid($assert)
    {
        $schema = dirname(dirname(dirname(__FILE__))) . '/hoptoad_2_0.xsd';

        $dom = new DOMDocument();
        $dom->loadXml($assert);

        // Validate temporary DOMDocument.
        if (!$dom->schemaValidate($schema)) {
            $this->fail('Data is invalid.');
        }

    }

    /**
     * Test XML creation.
     *
     * @return void
     */
    public function testXmHell()
    {
        // This is expensive as hell, but who cares in a test!
        $pearPath = $this->findPath('PHPUnit/Framework/TestCase.php');
        $pearPath = dirname(dirname(dirname($pearPath)));

        $assert  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $assert .= '<notice version="2.0">';
        $assert .= '<api-key>1234</api-key>';
        $assert .= '<notifier>';
        $assert .= '<name>Services_Hoptoad</name>';
        $assert .= '<url>http://github.com/till/php-hoptoad-notifier</url>';
        $assert .= '<version>@package_version@</version></notifier>';
        $assert .= '<error><class>LogicException</class><message>Your mom.</message>';
        $assert .= '<backtrace>';
        $assert .= '<line method="Services_Hoptoad_RequestTest-&gt;testXmHell" file="" number=""/>';
        $assert .= '<line method="ReflectionMethod-&gt;invokeArgs" file="' . $pearPath . '/PHPUnit/Framework/TestCase.php" number="824"/>';
        $assert .= '<line method="PHPUnit_Framework_TestCase-&gt;runTest" file="' . $pearPath . '/PHPUnit/Framework/TestCase.php" number="707"/>';
        $assert .= '<line method="PHPUnit_Framework_TestCase-&gt;runBare" file="' . $pearPath . '/PHPUnit/Framework/TestResult.php" number="687"/>';
        $assert .= '<line method="PHPUnit_Framework_TestResult-&gt;run" file="' . $pearPath . '/PHPUnit/Framework/TestCase.php" number="653"/>';
        $assert .= '<line method="PHPUnit_Framework_TestCase-&gt;run" file="' . $pearPath . '/PHPUnit/Framework/TestSuite.php" number="756"/>';
        $assert .= '<line method="PHPUnit_Framework_TestSuite-&gt;runTest" file="' . $pearPath . '/PHPUnit/Framework/TestSuite.php" number="732"/>';
        $assert .= '<line method="PHPUnit_Framework_TestSuite-&gt;run" file="' . $pearPath . '/PHPUnit/TextUI/TestRunner.php" number="350"/>';
        $assert .= '<line method="PHPUnit_TextUI_TestRunner-&gt;doRun" file="' . $pearPath . '/PHPUnit/TextUI/Command.php" number="214"/>';
        $assert .= '<line method="PHPUnit_TextUI_Command-&gt;run" file="' . $pearPath . '/PHPUnit/TextUI/Command.php" number="147"/>';
        $assert .= '<line method="PHPUnit_TextUI_Command::main" file="/usr/bin/phpunit" number="52"/>';
        $assert .= '</backtrace>';
        $assert .= '</error>';
        $assert .= '<request>';
        $assert .= '<url>/root/exploit -success</url>';
        $assert .= '<component/>';
        $assert .= '<action/>';
        $assert .= '<cgi-data>';
        $assert .= '<var key="foo">bar</var>';
        $assert .= '<var key="ruby">is annoying</var>';
        $assert .= '<var key="HOME">/home/till</var>';
        $assert .= '</cgi-data>';
        $assert .= '</request>';
        $assert .= '<server-environment>';
        $assert .= '<project-root>/home/till</project-root>';
        $assert .= '<environment-name>testing</environment-name>';
        $assert .= '</server-environment>';
        $assert .= '</notice>';
        $assert .= "\n";

        unset($_ENV);

        $_ENV         = array();
        $_ENV['foo']  = 'bar';
        $_ENV['ruby'] = 'is annoying';
        $_ENV['HOME'] = '/home/till';

        unset($_SERVER['argv']);

        $_SERVER['argv']   = array();
        $_SERVER['argv'][] = '/root/exploit';
        $_SERVER['argv'][] = '-success';

        // for a clean unit test
        unset($_REQUEST);
        unset($_SESSION);

        $request = new Services_Hoptoad_Request('1234');
        $request->setException(new LogicException("Your mom."));
        $request->setEnvironment('testing');

        $actual = $request->getRequestData();

        $this->assertXmlStringEqualsXmlString($assert, $actual);
        $this->assertXmlStringEqualsXmlString($actual, (string) $request);
    }

    /**
     * Gets the "real" path from an include directive.
     *
     * @param string $path_to_translate The path to find
     *                                  e.g. from include 'Foo/Bar.php';
     *
     * @return mixed
     */
    protected function findPath($path_to_translate)
    {
        $IncludePath = explode(PATH_SEPARATOR, get_include_path()); 
        foreach($IncludePath as $prefix){ 
            if(substr($prefix,-1) == DIRECTORY_SEPARATOR) {
                $prefix=substr($prefix,0,-1); 
            }
            $try_path=sprintf("%s%s%s", $prefix, DIRECTORY_SEPARATOR, $path_to_translate); 
            if (file_exists($try_path)) {
                return($try_path); 
            }
        }
        return false; 
    }
}
