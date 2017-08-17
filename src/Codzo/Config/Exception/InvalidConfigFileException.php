<?php
/**
 * Codzo/Config/Exception/InvalidConfigFileException.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @version GIT: $Id$
 * @package Codzo\Config
 */
namespace Codzo\Config\Exception;

use Codzo\Config\Exception\Exception;

/**
 * exception InvalidConfigFileException
 */
class InvalidConfigFileException extends Exception
{
    /**
     * constructor
     */
    public function __construct($file_path="", $code=0, $previous=NULL)
    {
        $message = 'Invalid config file';

        if ($file_path) {
            $message = sprintf(
                    '%s: %s',
                    $message,
                    $file_path
                );
        }
        parent::__construct($message, $code, $previous);
    }
}
