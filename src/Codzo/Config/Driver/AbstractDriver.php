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

    /**
     * parse the config file
     *
     * @param $file_path string the file path
     * @return array
     */
    abstract public function parse($file_path): array;
}
