<?php
namespace Codzo\Config;

use PHPUnit\Framework\TestCase;
use Codzo\Config\Driver\Php;
use Codzo\Config\Exception\InvalidConfigFileException;

/**
 * @coversDefaultClass \Codzo\Config\Driver\Php
 */
final class PhpTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $parser = new Php();
		$this->assertInstanceOf(
            Php::class,
            $parser
        );
    }

    public function testCanParseValidFile()
    {
        $parser = new Php();
        $r = $parser->parse('config/app.php');

		$this->assertTrue(
            $r['config']['php']['enabled']
        );

		$this->assertEquals(
            '1.0-beta',
            $r['config']['php']['version']
        );
    }

    public function testCanParseNoneExistFile()
    {
        $parser = new Php();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('file-not-exist.php');
    }

    public function testCanParseInvalidFile()
    {
        $parser = new Php();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('config-invalid/invalid.php');
    }


    public function testCanParseEmptyFile()
    {
        $parser = new Php();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('config-invalid/empty.file');
    }

    public function testCanHandleInjection()
    {
        $parser = new Php();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse(';rm /tmp');
    }
}
