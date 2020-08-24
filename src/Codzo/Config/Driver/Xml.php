<?php

/**
 * Codzo/Config/Driver/Xml.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @version GIT: $Id$
 * @package Codzo\Config
 */

namespace Codzo\Config\Driver;

use Codzo\Config\Driver\AbstractDriver;
use Codzo\Config\Exception\InvalidConfigFileException;

/**
 * class Xml
 */
class Xml extends AbstractDriver
{
    public $name = 'Xml';

    /**
     * parse the config file
     *
     * @param $file_path string the file path
     * @return array
     */
    public function parse($file_path): array
    {
        $data = array();
        // Note: we do not use `simplexml_load_file()`
        // because of https://bugs.php.net/bug.php?id=62577
        $xml = simplexml_load_string(file_get_contents($file_path));

        if ($xml instanceof \SimpleXMLElement) {
            $data[$xml->getName()] = json_decode(
                json_encode((array)$xml),
                1
            );
        }

        if (!$data) {
            throw new InvalidConfigFileException($file_path);
        }

        return $this->emptyArrayToNull($data);
    }

    /**
     * convert empty array to null value
     * When converting XML to array, you will get
     *     <foo/> => foo=>[]
     * while expecting foo=>null.
     * This function replaces all empty array to NULL
     *
     * @param $item array the value to process
     * @return array with all [] children replaced with null
     */
    protected function emptyArrayToNull($item)
    {
        if (is_array($item)) {
            if (count($item) == 0) {
                return null;
            }
            return array_map(
                array($this, __METHOD__),
                $item
            );
        }
        return $item;
    }
}
