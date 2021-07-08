<?php
namespace Hostnet\Component\Path;

use Composer\Composer;
use Composer\Config;
use Composer\IO\NullIO;
use Composer\Script\ScriptEvents;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\Path\Plugin
 */
class PluginTest extends TestCase
{
    /**
     * @var Plugin
     */
    private $plugin;

    protected function setUp(): void
    {
        $this->plugin = new Plugin();
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [ScriptEvents::PRE_AUTOLOAD_DUMP => 'onPreAutoloadDump'],
            $this->plugin->getSubscribedEvents()
        );
    }

    public function testOnPreAutoloadDump(): void
    {
        $config   = new Config(false, __DIR__ . '/../');
        $composer = new Composer();
        $io       = new NullIO();
        $composer->setConfig($config);
        $this->plugin->activate($composer, $io);

        // Test if Path.php will be created with valid contents
        $this->plugin->onPreAutoloadDump();

        self::assertEquals(realpath(__DIR__ . '/..'), Path::BASE_DIR);
        self::assertEquals(realpath(__DIR__ . '/../vendor'), Path::VENDOR_DIR);
    }

    protected function tearDown(): void
    {
        @unlink(__DIR__ . '/../src/Path.php');
    }
}
