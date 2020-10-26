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

    private function getVendorDir()
    {
        return str_replace('\'', '\\\'', realpath($this->composer->getConfig()->get('vendor-dir')));
    }

    private function getBaseDir()
    {
        return str_replace('\'', '\\\'', getcwd());
    }

    private function getPath()
    {
        $vendor_dir = $this->getVendorDir();

        if (0 === strpos(__DIR__, $vendor_dir) || strlen(__DIR__) - 4 === strpos(__DIR__, '/src')) {
            $path = __DIR__ . '/';
        } else {
            $path = $vendor_dir . '/hostnet/path-composer-plugin-lib/src/';
        }

        return $path;
    }

    public function onPreAutoloadDump()
    {
        $path = $this->getPath();
        $vendor_dir = $this->getVendorDir();
        $base_dir = $this->getBaseDir();

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

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        unlink($this->getPath() . 'Path.php');
    }
}
