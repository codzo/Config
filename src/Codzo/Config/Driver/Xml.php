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

    public function parse($file_path)
    {
        $data = array();
        // Note: we do not use `simplexml_load_file()`
        // because of https://bugs.php.net/bug.php?id=62577
        $xml = simplexml_load_string(file_get_contents($file_path));

        if ($xml instanceof \SimpleXMLElement) {
            $data[$xml->getName()] = (array)$xml;
        }

        if (!$data) {
            throw new InvalidConfigFileException($file_path);
        }

        return $data;
    }
}
