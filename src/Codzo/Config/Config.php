<?php

/**
 * Codzo/Config.php
 * @author Neil Fan<neil.fan@codzo.com>
 * @version GIT: $Id$
 * @package Codzo\Config
 */

namespace Codzo\Config;

use Codzo\Config\Exception\InvalidConfigDirectoryException;
use Codzo\Config\Driver\IDriver;

/**
 * Config class
 * Class Codzo\Config\Config will load supported files from a directory and
 * allow retrieve of the settings by a string presenting the setting name.
 */
class Config
{
    /**
     * The default config directory
     */
    const DEFAULT_CONFIG_DIRECTORY = 'config' ;

    /**
     * file-ext to handler mapping
     * @TODO new driver .db which contains a connection string. Data table stores settings
     */
    const DRIVER_MAPPING = [
        'ini' => 'Codzo\Config\Driver\Ini',
        'php' => 'Codzo\Config\Driver\Php',
        'xml' => 'Codzo\Config\Driver\Xml',
        'db'  => 'Codzo\Config\Driver\Db'
    ];

    /**
     * The config directory
     */
    protected $config_directory ;

    /**
     * All settings loaded from config directory
     * This variable has an array data type, with the config directory as key
     * and loaded configurations as value. Data will be shared within multiple
     * Config instances to minimise the payload of reuse.
     */
    protected static $master_settings = [];

    /**
     * loaded configuration for current directory
     */
    protected $settings = null;

    /**
     * constructor
     * @parama string $directory config directory, if null use DEFAULT_CONFIG_DIRECTORY
     */
    public function __construct($directory = null)
    {
        if (is_null($directory)) {
            $directory = self::DEFAULT_CONFIG_DIRECTORY ;
        }
        if (! is_dir($directory)) {
            throw new InvalidConfigDirectoryException();
        }
        $this->config_directory = realpath($directory);

        if (!isset(static::$master_settings[$this->config_directory])) {
            static::$master_settings[$this->config_directory] = [];
        }
        $this->settings = &static::$master_settings[$this->config_directory];

        if (!$this->settings) {
            $this->load();
        }
    }

    /**
     * get the config directory
     * @return string config directory
     */
    public function getConfigDirectory()
    {
        return $this->config_directory;
    }

    /**
     * get driver class for a file type
     * @param  string $filename
     * @return string handler class name, or blank string if not mapped
     */
    public function getDriverClassName($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (isset(self::DRIVER_MAPPING[$ext])) {
            return self::DRIVER_MAPPING[$ext];
        }

        return '';
    }

    /**
     * Get driver class name
     * @param  string      name of config file
     * @return string|null driver class name to handle the config file
     */
    public function getDriver($driver_class_name)
    {
        static $driver_list = array();
        if (!isset($driver_list[$driver_class_name])) {
            if (class_exists($driver_class_name)) {
                $driver = new $driver_class_name();
                if ($driver instanceof IDriver) {
                    $driver_list[$driver_class_name] = $driver;
                }
            }
        }

        if (isset($driver_list[$driver_class_name])) {
            return $driver_list[$driver_class_name];
        }

        return null;
    }

    /**
     * get a list of valid config files.
     * This method will only look for files in dest dir, no subdirectories.
     * @return array list of config files
     */
    public function getConfigFileList()
    {
        $list = [];
        if (
            is_readable($this->config_directory)
            && $handle = opendir($this->config_directory)
        ) {
            while (false !== ($file = readdir($handle))) {
                // do something with the file
                // note that '.' and '..' is returned even
                if ($file[0] == '.') {
                    continue;
                }

                if (!is_file($this->config_directory . '/' . $file)) {
                    continue;
                }

                $driver_class_name = $this->getDriverClassName($file);
                if ($driver_class_name && class_exists($driver_class_name)) {
                    $list[] = $file;
                }
            }
            closedir($handle);
        }

        return $list;
    }

    /**
     * load the settings
     * @param  bool $force if TRUE will force to reload
     */
    public function load($force = false)
    {
        if ($force) {
            $this->settings = [];
        }

        if (!$this->settings) {
            foreach ($this->getConfigFileList() as $file) {
                $settings = $this->loadConfigFile($file);
                $this->settings = array_merge_recursive(
                    $this->settings,
                    $settings
                );
            }
        }
    }

    /**
     * load the settings from a config file
     * @param string $file the name of config file
     * @return string[] settings
     */
    protected function loadConfigFile($file)
    {
        $file = $this->config_directory . '/' . basename($file);
        if ($driver = $this->getDriver($this->getDriverClassName($file))) {
            $data = $driver->parse($file);
            return $data;
        }

        return [];
    }


    /**
     * get setting
     * Search setting name "app.database.driver" may return
     * # a setting with exact name of "app.database.driver"
     * # an array string who has [app][database][driver]
     * whichever comes first.
     * @param  string $name    the setting name
     * @param  mixed  $default the default value
     * @return mixed  $value   the setting value
     */
    public function get($name, $default = null)
    {
        // do we have an exact match?
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        }

        // do we have an array setting can match the name?
        $path = explode('.', $name);
        $settings = &$this->settings;

        while ($k = array_shift($path)) {
            if (isset($settings[$k])) {
                $settings = &$settings[$k];
            } else {
                break;
            }

            if (sizeof($path) === 0) {
                // we are at the end of search
                return $settings;
            }
        }
        return $default;
    }

    /**
     * set an item manually
     * @param  string $name    the setting name
     * @param  mixed  $value   the setting value
     */
    public function set($name, $value)
    {
        $this->settings[$name] = $value ;
    }

    /**
     * get all settings
     */
    public function toArray()
    {
        return $this->settings;
    }
}
