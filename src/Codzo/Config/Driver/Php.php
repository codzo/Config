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

        // detect if the php script has any syntax error
        // redirect stderr to make test output pretty
        $cmd = sprintf(
            'php -l %s 2> /dev/null',
            escapeshellarg(
                realpath($file_path)
            )
        );
        @exec($cmd, $output, $rt);
        if ($rt === 0) {
            $data = @include($file_path);
        }

        if (!is_array($data) || !$data) {
            throw new InvalidConfigFileException($file_path);
        }

        return $data;
    }
}
