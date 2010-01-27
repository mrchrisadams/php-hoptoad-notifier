<?php
/**
 * Services_Hoptoad
 *
 * @category error
 * @package  Services_Hoptoad
 * @author   Rich Cavanaugh <no@email>
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  GIT: $Id$
 * @link     http://github.com/till/php-hoptoad-notifier
 */

/**
 * Register autoloader
 */
spl_autoload_register(array('Services_Hoptoad', 'autoload'));

/**
 * Services_Hoptoad
 *
 * @category error
 * @package  Services_Hoptoad
 * @author   Rich Cavanaugh <no@email>
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://github.com/till/php-hoptoad-notifier
 * @todo     This class shouldn't be all static.
 * @todo     Add a unit test, or two.
 * @todo     Allow injection of Zend_Http_Client or HTTP_Request2
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

    public static $client = 'curl'; // PEAR, Zend

    /**
     * @var mixed $apiKey
     */
    public static $apiKey = null;

    /**
     * @var string $environment
     */
    public static $environment = 'production';

    /**
     * @var string $endpoint
     */
    public static $endpoint = 'http://hoptoadapp.com/notices/';

    /**
     * __construct
     *
     * @param mixed $apiKey Optionally!
     *
     * @return $this
     */
    public function __construct($apiKey = null)
    {
        if ($apiKey !== null) {
            self::$apiKey = $apiKey;
        }
    }

    /**
     * Autoloader!
     *
     * @param string $className The class to load.
     *
     * @return bool
     */
    public static function autoload($className)
    {
        static $thisRoot;

        if ($thisRoot == null) {
            $thisRoot = dirname(dirname(__FILE__));
        }

        $file = str_replace('_', '/', $className) . '.php';

        return include $thisRoot . '/' . $file;
    }

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
     * Notify for the 2.0 API. This is a test and will eventually replace
     * {@link self::notify()}.
     *
     * @param Exception $e The exception to report.
     *
     * @return void
     * @uses   Services_Hoptoad_Request
     */
    public static function notifyV2(Exception $e)
    {
        $data = new Services_Hoptoad_Request(self::$apiKey);
        $data->setException($e);
        $data->setEnvironment(self::$environment);

        $endpoint = 'http://hoptoadapp.com/notifier_api/v2/notices';
        $xml      = (string) $data;
        $header   = array("Accept: text/xml, application/xml", "Content-type: text/xml");

        self::makeRequest($header, $xml, $endpoint);
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
     * @todo   Handle response (e.g. errors)
     */
    public static function notify($api_key, $message, $file, $line, $trace, $error_class = null)
    {
        throw new RuntimeException("Unsupport Api.");
    }

    /**
     * @param array  $header
     * @param string $data
     * @param mixed  $endpoint
     */
    protected static function makeRequest(array $header, $data, $endpoint = null)
    {
        if ($endpoint === null) {
            $endpoint = self::$endpoint;
        }

        if (self::$client == 'curl') {

    	    $curlHandle = curl_init(); // init curl

            // cURL options
            // FIXME: replace with HTTP_Request2
            curl_setopt($curlHandle, CURLOPT_URL,            self::$endpoint);
            curl_setopt($curlHandle, CURLOPT_POST,           1);
            curl_setopt($curlHandle, CURLOPT_HEADER,         0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,        self::$timeout);
	        curl_setopt($curlHandle, CURLOPT_POSTFIELDS,     $data);
	        curl_setopt($curlHandle, CURLOPT_HTTPHEADER,     $header);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($curlHandle);
            curl_close($curlHandle);

            var_dump($response); // this sucks

            return;

        }

        if (self::$client == 'Zend') {

            try {
                $client = new Zend_Http_Client($endpoint);
                $client->setHeaders($header);
                $client->setRawData($data, 'text/xml');

                $response = $client->request('POST');

                //var_dump($response->getBody(), $response->getStatus()); exit;

                if ($response->getStatus() == 200) {
                    return true;
                }
                self::handleErrorResponse($response->getStatus(), $data);

            } catch (Zend_Exception $e) {
                // disregard for now
            }

        }

        if (self::$client == 'PEAR') {

            try {

                $client   = new HTTP_Request2($endpoint);
                $response = $client->setMethod(HTTP_Request2::METHOD_POST)
                    ->setHeader($header)
                    ->setBody($data)
                    ->send();

                if ($response->getStatus() == 200) {
                    return true;
                }
                self::handleErrorResponse($response->getStatus(), $data);

            } catch (HTTP_Request2_Exception $e) {
                // disregard
            }
        }

        throw new LogicException("Unknown client: " . self::$client);
    }

    /**
     * Build a trace that is formatted in the way Hoptoad expects
     *
     * @param mixed $trace
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

    /**
     * @param mixed  $code        The HTTP status code from Hoptoad.
     * @param string $requestData The XML sent to Hoptoad.
     * @return void
     * @throws Services_Hoptoad_Exception Error message from hoptoad, translated to a RuntimeException.
     */
    protected static function handleErrorResponse($code, $requestData)
    {
        switch ($code) {

        case '403':
            $msg .= 'The requested project does not support SSL - resubmit in an http request.';
            break;
        case '422':
            $msg .= 'The submitted notice was invalid - check the notice xml against the schema.';
            break;
        case '500':
            $msg .= 'Unexpected errors - submit a bug report at http://help.hoptoadapp.com.';
            break;
        default:
            $msg .= 'Unknown error code from Hoptoad\'s API: ' . $code;
            break;
        }

        $e = new Services_Hoptoad_Exception($msg, $code);
        $e->setRequestData($requestData);
        throw $e;
    }
}
