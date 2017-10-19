<?php
namespace Codzo\Config;

use PHPUnit\Framework\TestCase;
use Codzo\Config\Driver\Xml;
use Codzo\Config\Exception\InvalidConfigFileException;

/**
 * @coversDefaultClass \Codzo\Config\Driver\Xml
 */
final class XmlTest extends TestCase
{
    private $dir = 'config/' ;

    public function testCanCreateInstance()
    {
        $parser = new Xml();
		$this->assertInstanceOf(
            Xml::class,
            $parser
        );
    }

    public function testCanParseValidFile()
    {
        $parser = new Xml();
        $r = $parser->parse('config/app.xml');

		$this->assertEquals(
            1,
            $r['config']['xml']['enabled']
        );

		$this->assertEquals(
            '1.0-development',
            $r['config']['xml']['version']
        );
    }

    public function testCanParseNoneExistFile()
    {
        $parser = new Xml();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('file-not-exist.xml');
    }

    public function testCanParseInvalidFile()
    {
        $parser = new Xml();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('config-invalid/invalid.xml');
    }


    public function testCanParseEmptyFile()
    {
        $parser = new Xml();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse('config-invalid/empty.file');
    }

}

