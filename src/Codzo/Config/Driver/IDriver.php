<?php

/**
 * Codzo/Config/Driver/IDriver.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @package Codzo\Config
 */

namespace Codzo\Config\Driver;

/**
 * interface IDriver
 */
interface IDriver
{
    /**
     * parse the config file
     *
     * @param $file_path string the file path
     * @return array
     */
    public function parse($file_path): array;
}
