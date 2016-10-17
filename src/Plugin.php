<?php
namespace Hostnet\Component\Path;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Composer\EventDispatcher\EventSubscriberInterface;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    public static function getSubscribedEvents()
    {
        return [ScriptEvents::PRE_AUTOLOAD_DUMP => 'onPreAutoloadDump'];
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    public function onPreAutoloadDump()
    {
        $vendor_dir = str_replace('\'', '\\\'', realpath($this->composer->getConfig()->get('vendor-dir')));
        $base_dir   = str_replace('\'', '\\\'', getcwd());

        if (0 === strpos(__DIR__, $vendor_dir) || strlen(__DIR__) - 4 === strpos(__DIR__, '/src')) {
            $path = __DIR__ . '/';
        } else {
            $path = $vendor_dir . '/hostnet/path-composer-plugin-lib/src/';
        }

        file_put_contents(
            $path . 'Path.php',
            <<<EOF
<?php
namespace Hostnet\Component\Path;

class Path
{
    const VENDOR_DIR = '$vendor_dir';
    const BASE_DIR   = '$base_dir';
}

EOF
        );
    }
}
