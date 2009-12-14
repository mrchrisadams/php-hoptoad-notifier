<?php 
// register Services_Hoptoad for php errors and raised exceptions
require_once 'Services/Hoptoad.php';
Services_Hoptoad::installHandlers("YOUR_HOPTOAD_API_KEY");
?>

<?php
// standalone
require_once 'Services/Hoptoad.php';

$exception = new Custom_Exception('foobar');
Services_Hoptoad::handleException($exception);
?>