<?php
/**
 * Codzo/Config/Driver/Php.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @version GIT: $Id$
 * @package Codzo\Config
 */
namespace Codzo\Config\Driver;

use Codzo\Config\Driver\AbstractDriver;
use Codzo\Config\Exception\InvalidConfigFileException;

/**
 * class Ini
 */
class Php extends AbstractDriver
{
    public $name = 'PHP';

    public function parse($file_path)
    {
        $data   = false;
        $output = [];
        $rt     = false;

        @exec('php -l '.escapeshellarg(realpath($file_path)), $output, $rt);
        if ($rt === 0) {
            $data = @include($file_path);
        }

        if (!is_array($data) || !$data) {
            throw new InvalidConfigFileException($file_path);
        }

        return $data;
    }
}
