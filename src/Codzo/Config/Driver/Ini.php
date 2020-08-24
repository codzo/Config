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

    /**
     * parse the config file
     *
     * @param $file_path string the file path
     * @return array
     */
    public function parse($file_path): array
    {
        $data = @parse_ini_file($file_path);

        if (!is_array($data) || !$data) {
            throw new InvalidConfigFileException($file_path);
        }

        /**
         * translate to array
         * app.mode.debug=true     =>     $app[mode][debug]=true
         */
        foreach ($data as $key => $value) {
            if (strpos($key, '.') !== false) {
                $key_stack = explode('.', $key);
                $p = &$data;
                while ($s = array_shift($key_stack)) {
                    if (!array_key_exists($s, $p)) {
                        $p[$s] = array();
                    }
                    $p = &$p[$s];
                }
                $p = $value;
            }
            unset($data[$key]);
        }

        return $data;
    }
}
