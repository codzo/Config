<?php
namespace Codzo\Config;

use PHPUnit\Framework\TestCase;
use Codzo\Config\Driver\Ini;
use Codzo\Config\Exception\InvalidConfigFileException;

/**
 * @coversDefaultClass \Codzo\Config\Driver\Ini
 */
final class IniTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $parser = new Ini();
		$this->assertInstanceOf(
            Ini::class,
            $parser
        );
    }

    public function testCanParseValidFile()
    {
        $parser = new Ini();
        $r = $parser->parse('config/app.ini');

		$this->assertEquals(
            1,
            $r['config.ini.enabled']
        );

		$this->assertEquals(
            '1.0',
            $r['config.ini.version']
        );
    }

    public function testCanParseNoneExistFile()
    {
        $parser = new Ini();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('file-not-exist.ini');
    }

    public function testCanParseInvalidFile()
    {
        $parser = new Ini();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('config-invalid/invalid.ini');
    }


    public function testCanParseEmptyFile()
    {
        $parser = new Ini();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('config-invalid/empty.file');
    }

    public function testCanParseArrayData()
    {
        $parser = new Ini();
        $r = $parser->parse('config/app.ini');

		$this->assertInternalType(
            'array',
            $r['config.ini.notes']
        );
    }

}

