<?php
/**
 * Codzo/Config/Exception/InvalidConfigDirectoryException.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @version GIT: $Id$
 * @package Codzo\Config
 */
namespace Codzo\Config\Exception;

use Codzo\Config\Exception\Exception;

/**
 * exception InvalidConfigDirectoryException
 */
class InvalidConfigDirectoryException extends Exception
{
    /**
     * constructor
     */
    public function __construct($directory="", $code=0, $previous=NULL)
    {
        $message = 'Invalid config directory';

        if ($directory) {
            $message = sprintf(
                    '%s: %s',
                    $message,
                    $directory
                );
        }
        parent::__construct($message, $code, $previous);
    }
}
