<?php
namespace Codzo\Config;

use PHPUnit\Framework\TestCase;
use Codzo\Config\Config;
use Codzo\Config\Exception\InvalidConfigDirectoryException;

/**
 * @coversDefaultClass \Codzo\Config\Config
 */
final class ConfigTest extends TestCase
{
    public function testCanUseNullAsParameterForConstructor()
    {
        $config = new Config();
        $this->assertInstanceOf(
            Config::class,
            $config
        );

        $this->assertEquals(
            realpath(Config::DEFAULT_CONFIG_DIRECTORY),
            realpath($config->getConfigDirectory())
        );
    }

    public function testCanUseDirectoryAsParameterForConstructor()
    {
        $config = new Config('config-another');
        $this->assertInstanceOf(
            Config::class,
            $config
        );

        $this->assertEquals(
            realpath('config-another'),
            realpath($config->getConfigDirectory())
        );
    }

    public function testExceptionOnInvalidDirectory()
    {
        $this->expectException(InvalidConfigDirectoryException::class);
        new Config('./invalid-directory-name');
    }

    public function testCanGetDriverClassName()
    {
        $config = new Config();

        $this->assertEquals(
            'Codzo\Config\Driver\Php',
            $config->getDriverClassName('config/app.php')
        );

        $this->assertEquals(
            'Codzo\Config\Driver\Ini',
            $config->getDriverClassName('config/app.ini')
        );

        /**
         * Invalid file ext will map to a blank string
         */
        $this->assertEquals(
            '',
            $config->getDriverClassName('config/app.invalid-file-ext')
        );
    }

    public function testGetConfigFileList()
    {
        $config = new Config(__DIR__ . "/../../config");
        $this->assertEmpty(
            array_diff(
                ['app.php', 'app.ini', 'app.xml'],
                $config->getConfigFileList()
            )
        );
    }

    public function testLoad()
    {
        $config = new Config(__DIR__ . "/../../config");
        $this->assertEquals(
            '1.0-development',
            $config->get('config.xml.version')
        );
        $this->assertNull(
            $config->get('config.xml.invalid-setting-name')
        );
        $this->assertNull(
            $config->get('config.invalid-middle-path.version')
        );
        $this->assertEquals(
            [
                'enabled' => '1',
                'version' => '1.0-development'
            ],
            $config->get('config.xml')
        );
    }

    public function testSet()
    {
        $config = new Config(__DIR__ . "/../../config");
        $config->set('config.xml.version', '0.001');
        $this->assertEquals(
            '0.001',
            $config->get('config.xml.version')
        );

        $config->set('config.invalid-setting', true);
        $this->assertEquals(
            true,
            $config->get('config.invalid-setting')
        );
    }
}
