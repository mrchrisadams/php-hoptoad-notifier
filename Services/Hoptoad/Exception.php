<?php
/**
 * Services_Hoptoad_Exception
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
 * Services_Hoptoad_Exception
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
class Services_Hoptoad_Exception extends RuntimeException
{
    protected $data;

    public function getRequestData()
    {
        return $this->data;
    }

    public function setRequestData($data)
    {
        $this->data = $data;
        return $this;
    }
}
