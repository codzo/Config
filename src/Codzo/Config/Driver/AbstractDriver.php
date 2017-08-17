<?php
/**
 * Codzo/Config/Driver/AbstractDriver.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @version GIT: $Id$
 * @package Codzo\Config
 */
namespace Codzo\Config\Driver;

/**
 * class AbstractDriver
 */
abstract class AbstractDriver
{
    public $name;

    abstract public function parse($file_path);
}
