<?php
namespace Easilydo\Exceptions;

/**
 * Class SiftRequestException
 *
 * This exception is thrown when a problem occurs during an API request
 *
 * @package Easilydo
 */
class SiftRequestException extends \Exception
{
    public function __construct($message = 'An error occurred during the request', $code = '-1')
    {
        parent::__construct($message, $code);
    }
}
?>
