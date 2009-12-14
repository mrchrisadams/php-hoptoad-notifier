<?php
/**
 * Services_Hoptoad
 *
 * @category error
 * @package  Services_Hoptoad
 * @author   Rich Cavanaugh <no@email>
 * @author   Till Klampaeckel <till@php.net>
 * @license  
 * @version  GIT: $Id$
 * @link     http://github.com/till/php-hoptoad-notifier
 */

/**
 * Spyc Yaml Parser
 * @ignore
 */
require_once dirname(__FILE__) . '/../Yaml/Spyc.php';

/**
 * Services_Hoptoad
 *
 * @category error
 * @package  Services_Hoptoad
 * @author   Rich Cavanaugh <no@email>
 * @author   Till Klampaeckel <till@php.net>
 * @license  
 * @version  Release: @package_version@
 * @link     http://github.com/till/php-hoptoad-notifier
 * @todo     This class shouldn't be all static.
 * @todo     Add a unit test, or two.
 */
class Services_Hoptoad
{
    /**
     * Report E_STRICT
     *
     * @var bool $reportESTRICT
     * @todo Implement set!
     */
    protected static $reportESTRICT = false;

    /**
     * Timeout for cUrl.
     * @var int $timeout
     */
    protected static $timeout = 2;

    public static $client = 'curl';

    /**
     * @var mixed $apiKey
     */
    public static $apiKey = null;

    /**
     * @var string $endpoint
     */
    public static $endpoint = 'http://hoptoadapp.com/notices/';

    /**
     * Install the error and exception handlers that connect to Hoptoad
     *
     * @return void
     * @author Rich Cavanaugh
     */
    public static function installHandlers($api_key = NULL)
    {
        if (isset($api_key)) {
            self::$apiKey = $api_key;
        }
    
        set_error_handler(array("Services_Hoptoad", "errorHandler"));
        set_exception_handler(array("Services_Hoptoad", "exceptionHandler"));
    }
  
    /**
     * Handle a php error
     *
     * @param string $code 
     * @param string $message 
     * @param string $file 
     * @param string $line 
     * @return void
     * @author Rich Cavanaugh
     */
    public static function errorHandler($code, $message, $file, $line)
    {
        if ($code == E_STRICT && self::$reportESTRICT === false) {
            return;
        }

	    $trace = self::tracer();
        self::notify(self::$apiKey, $message, $file, $line, $trace, null);
    }
  
    /**
     * Handle a raised exception
     *
     * @param Exception $exception 
     *
     * @return void
     * @author Rich Cavanaugh
     * @uses   self::tracer()
     * @uses   self::notify()
     */
    public static function exceptionHandler(Exception $exception)
    {
        $trace = self::tracer($exception->getTrace());

        self::notify(
            self::$apiKey,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $trace,
            null
        );
    }
  
    /**
     * Pass the error and environment data on to Hoptoad
     *
     * @param string $api_key
     * @param string $message
     * @param string $file
     * @param string $line
     * @param array  $trace
     * @param mixed  $error_class
     *
     * @author Rich Cavanaugh
     */
    public static function notify($api_key, $message, $file, $line, $trace, $error_class = null)
    {
        array_unshift($trace, "$file:$line");

        $session = array();
        if (isset($_SESSION)) {
            $session = array('key' => session_id(), 'data' => $_SESSION);
        }

        $environment = array();
        if (isset($_SERVER)) {
            $environment['_SERVER'] = $_SERVER;
        }
        if (isset($_ENV)){
            $environment['_ENV'] = $_ENV;
        }

        $url  = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; // FIXME for cli
        $body = array(
            'api_key'         => $api_key,
            'error_class'     => $error_class,
            'error_message'   => $message,
            'backtrace'       => $trace,
            'request'         => array("params" => $_REQUEST, "url" => $url),
            'session'         => $session,
            'environment'     => $environment,
        );

	    $yaml   = Spyc::YAMLDump(array("notice" => $body), 4, 60);
        $header = array("Accept: text/xml, application/xml", "Content-type: application/x-yaml");

        if (self::$client == 'curl') {
    	    $curlHandle = curl_init(); // init curl

            // cURL options
            // FIXME: replace with HTTP_Request2
            curl_setopt($curlHandle, CURLOPT_URL,            self::$endpoint);
            curl_setopt($curlHandle, CURLOPT_POST,           1);
            curl_setopt($curlHandle, CURLOPT_HEADER,         0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,        self::$timeout);
	        curl_setopt($curlHandle, CURLOPT_POSTFIELDS,     $yaml);
	        curl_setopt($curlHandle, CURLOPT_HTTPHEADER,     $header);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

            curl_exec($curlHandle);
            curl_close($curlHandle);

        } elseif (self::$client == 'Zend') {
            $client = new Zend_Http_Client(self::$endpoint);
            $client->setHeaders($header);
            $client->setRawData($yaml, 'application/x-yaml');
            $client->request('POST');
        }
    }

    /**
     * Build a trace that is formatted in the way Hoptoad expects
     *
     * @param string $trace 
     * @return array
     *
     * @author Rich Cavanaugh
     */
    public static function tracer($trace = NULL)
    {
        $lines = array(); 

        $trace = $trace ? $trace : debug_backtrace();
    
        $indent = '';
        $func   = '';
    
        foreach ($trace as $val) {
            if (isset($val['class']) && $val['class'] == 'Services_Hoptoad') {
                continue;
            }
      
            $file        = isset($val['file']) ? $val['file'] : 'Unknown file';
            $line_number = isset($val['line']) ? $val['line'] : '';
            $func        = isset($val['function']) ? $val['function'] : '';
            $class       = isset($val['class']) ? $val['class'] : '';
      
            $line = $file;

            if ($line_number) {
                $line .= ':' . $line_number;
            }
            if ($func) {
                $line .= ' in function ' . $func;
            }
            if ($class) {
                $line .= ' in class ' . $class;
            }
            $lines[] = $line;
        }
        return $lines;
    }
}
