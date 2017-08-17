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
        $xml = @simplexml_load_file($file_path);

        if ($xml instanceof \SimpleXMLElement) {
            $data[$xml->getName()] = json_decode(
                json_encode((array)$xml),
                1
            );
        }

        if (!$data) {
            throw new InvalidConfigFileException($file_path);
        }

        return $data;
    }
}
