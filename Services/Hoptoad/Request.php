<?php
/**
 * Services_Hoptoad
 *
 * @category error
 * @package  Services_Hoptoad
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  GIT: $Id$
 * @link     http://github.com/till/php-hoptoad-notifier
 */

/**
 * Services_Hoptoad
 *
 * @category error
 * @package  Services_Hoptoad
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://github.com/till/php-hoptoad-notifier
 */
class Services_Hoptoad_Request
{
    /**
     * @var string
     * @see self::__construct()
     */
    protected $apiKey = '';

    /**
     * @var string $apiVersion Hoptoad's API version.
     */
    protected $apiVersion = '2.0';

    /**
     * @var string Eyecandy whatevers for Hoptoad.
     */
    protected $clientName    = 'Services_Hoptoad';
    protected $clientUrl     = 'http://github.com/till/php-hoptoad-notifier';
    protected $clientVersion = '@package_version@';

    /**
     * @var string $environment
     * @see self::setEnvironment()
     * @see self::setupEnvironment()
     */
    protected $environment = 'production';

    /**
     * @var Exception $exception The exception that was thrown.
     */
    protected $exception;

    /**
     * @var SimpleXMLElement
     */
    protected $notice;

    /**
     * @var SimpleXMLElement
     */
    protected $xml;

    /**
     * Start a new request object!
     *
     * @param string $apiKey        The API-Key to connect to Hoptoad with.
     * @param string $clientName    The name of the client - Services_Hoptoad most likely.
     * @param string $clientVersion The version of this class.
     *
     * @return $this
     */
    public function __construct($apiKey, $clientName = 'Services_Hoptoad', $clientVersion = '@package_version@')
    {
        $this->apiKey = $apiKey;

        $this->clientName    = $clientName;
        $this->clientVersion = $clientVersion;
    }

    /**
     * Magic method - just a wrapper.
     *
     * @return string
     * @uses   self::getRequestData()
     */
    public function __toString()
    {
        return $this->getRequestData();
    }

    /**
     * Get the XML in a string.
     *
     * @return string
     * @uses self::createNotice()
     */
    public function getRequestData()
    {
        // start from scratch
        $this->setupNotice();
        
        /**
         * 1) Notifier
         * 2) Error
         * 3) Request
         * 4) Server-Environment
         */
        $notifier = $this->notice->addChild('notifier');
        $this->setupNotifier($notifier);

        $error = $this->notice->addChild('error');
        $this->setupError($error);

        $request = $this->notice->addChild('request');
        $this->setupRequest($request);

        $env = $this->notice->addChild('server-environment');
        $this->setupEnvironment($env);

        return $this->notice->asXML();
    }

    /**
     * @param string $env Most likely production, staging or development.
     *
     * @return $this
     */
    public function setEnvironment($env)
    {
        $this->environment = $env;
        return $this;
    }

    /**
     * Set the exception!
     *
     * @param Exception $e The exception to be send to Hoptoad.
     *
     * @return $this
     */
    public function setException(Exception $e)
    {
        $this->exception = $e;
        return $this;
    }

    /**
     * Used to wrap $_SESSION, $_REQUEST, $_ENV, etc..
     *
     * @param array            $array   An array of data to append to the XML structure.
     * @param SimpleXMLElement $element The instance to attach to.
     *
     * @return void
     */
    protected function arrayToXml(array $array, SimpleXMLElement $element)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) { // this is not supported by the API
                continue;
            }
            $var = $element->addChild('var', $value);
            $var->addAttribute('key', $key);
        }
    }

    /**
     * Rebuild arrays in case they are nested.
     *
     * @param array $array Such as $_SESSION, $_POST, ...
     *
     * @return mixed
     */
    protected function cleanArray(array $array = null)
    {
        $keep = array();
        if (($array === null) || (count($array) == 0)) {
            return $keep;
        }
        foreach ($array as $key => $value) {

            if (!is_array($value)) {
                $keep[$key] = $value;
                continue;
            }

            foreach ($value as $subKey => $subValue) {
                $keep["{$key}_{$subKey}"] = $subValue;
            }

        }

        return $keep;
    }

    /**
     * Create the <notice /> element. This wraps around everything.
     */
    protected function setupNotice()
    {
        $this->notice = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<notice />');
        $this->notice->addAttribute('version', $this->apiVersion);
        $this->notice->addChild('api-key', $this->apiKey);
    }

    /**
     * @param SimpleXMLElement $env The element to attach to.
     *
     * @return void
     * @todo   Support Windows?
     */
    protected function setupEnvironment(SimpleXMLElement $env)
    {
        if (php_sapi_name() == 'cli') {
            $root = $_ENV['HOME']; // this assumes *nix
        } else {
            $root = $_ENV['DOCUMENT_ROOT'];
        }
        $env->addChild('project-root', $root);
        $env->addChild('environment-name', $this->environment);
    }

    /**
     * Assemble the <error /> part.
     *
     * @param SimpleXMLElement $error
     *
     * @return void
     * @uses   self::$exception
     * @see    self::getRequestData()
     */
    protected function setupError(SimpleXMLElement $error)
    {
        if (!($this->exception instanceof Exception)) {
            return;
        }
        $error->addChild('class', get_class($this->exception));
        $error->addChild('message', $this->exception->getMessage());

        $backtrace = $error->addChild('backtrace');

        $trace = $this->exception->getTrace();
        if (count($trace) == 0) {
            $trace[] = array(
                'method' => 'unknown',
                'file'   => 'unknown',
                'line'   => 0,
            );
        }

        foreach ($trace as $step) {

            $method = $step['function'];
            if (isset($step['class'])) {
                $method = $step['class'] . $step['type'] . $method;
            }

            $line = $backtrace->addChild('line');
            $line->addAttribute('method', $method);
            $line->addAttribute('file',   @$step['file']);
            $line->addAttribute('number', @$step['line']);
        }
    }

    /**
     * Collect request data.
     *
     * @param SimpleXMLElement $request
     *
     * @return void
     */
    protected function setupRequest(SimpleXMLElement $request)
    {
        $url = '';

        if (php_sapi_name() == 'cli') {

            $url .= implode(" ", $_SERVER['argv']);
            $env  = $_ENV;

        } else { // assume this is HTTP

            $url .= 'http'; // FIXME
            $url .= '://' . $_SERVER['HTTP_HOST'];
            $url .= $_SERVER["REQUEST_URI"];

            if (!empty($_SERVER['QUERY_STRING'])) {
                $url .= '?' . $_SERVER['QUERY_STRING'];
            }

            $env = $_SERVER;
        }
        $request->addChild('url', $url);
        $request->addChild('component');
        $request->addChild('action');

        // optional
        if (isset($_REQUEST)) {
            $_REQUEST = $this->cleanArray($_REQUEST);
            if (count($_REQUEST) > 0) {
                $params = $request->addChild('params');
                $this->arrayToXml($_REQUEST, $params);
            }
        }

        // optional
        if (isset($_SESSION)) {
            $_SESSION = $this->cleanArray($_SESSION);
            if (count($_SESSION) > 0) {
                $session = $request->addChild('session');
                $this->arrayToXml($_SESSION, $session);
            }
        }

        // this is optional as well, but we set it up regardless
        $cgi = $request->addChild('cgi-data');
        $this->arrayToXml($env, $cgi);
    }

    /**
     * Create the notifier part of the XML structure.
     *
     * If you want to override any of the variables, see {@link self::__construct()}
     *
     * @param SimpleXMLElement $notifier
     *
     * @return void
     * @uses   self::$clientName
     * @uses   self::$clientUrl
     * @uses   self::$clientVersion
     * @see    self::getRequestData()
     */
    protected function setupNotifier(SimpleXMLElement $notifier)
    {
        $notifier->addChild('name',    $this->clientName);
        $notifier->addChild('url',     $this->clientUrl);
        $notifier->addChild('version', $this->clientVersion);
    }
}
