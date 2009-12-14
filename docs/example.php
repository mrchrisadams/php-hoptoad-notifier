<?php 
// register Services_Hoptoad for php errors and raised exceptions
require_once 'Services/Hoptoad.php';
Services_Hoptoad::installHandlers("YOUR_HOPTOAD_API_KEY");
?>

<?php
// standalone
require_once 'Services/Hoptoad.php';

Services_Hoptoad::$apiKey = "YOUR_HOPTOAD_API_KEY";

$exception = new Custom_Exception('foobar');
Services_Hoptoad::handleException($exception);
?>

<?php
// use Zend_Http_Client
require_once 'Services/Hoptoad.php';

Services_Hoptoad::$apiKey = "YOUR_HOPTOAD_API_KEY";
Services_Hoptoad::$client = "Zend";

$exception = new Custom_Exception('foobar');
Services_Hoptoad::handleException($exception);
?>
