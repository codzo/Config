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
    private $dir = __DIR__ . '/../../../config/' ;

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
        $r = $parser->parse($this->dir . 'app.php');

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
        $parser->parse($this->dir . 'invalid/invalid.php');
    }


    public function testCanParseEmptyFile()
    {
        $parser = new Php();
        $this->expectException(InvalidConfigFileException::class);
        $parser->parse($this->dir . 'invalid/empty.file');
    }
}
