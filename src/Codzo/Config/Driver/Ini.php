<?php
/**
 * Codzo/Config/Driver/Ini.php
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
class Ini extends AbstractDriver
{
    public $name = 'INI';

    public function parse($file_path)
    {
        $data = @parse_ini_file($file_path);

        if (!is_array($data) || !$data) {
            throw new InvalidConfigFileException($file_path);
        }

        return $data;
    }
}
