<?php
/**
 * SAM logic autoloader
 *
 * SAM-1921: Autoloading optimization
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 19, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Autoload;

use Sam\Core\Service\CustomizableClass;

use Sam\Installation\Config\Repository\ConfigRepository;

use function is_file;
use function preg_replace;

/**
 * Class Autoloader
 * @package Sam\Application
 */
class LegacyAutoloader extends CustomizableClass
{
    private const EXTENSIONS = ".php";

    /** @var string[] */
    protected array|null $customClasses = null;
    /** @var array Collect not found custom classes */
    public static array $customClassFoundStatus = [];
    /** @var string[] */
    protected array $paths = [];
    /** @var bool */
    private bool $shouldSearchCustomClassesFromArray = false;
    /** @var array */
    private array $customPathMap = [];

    /**
     * Return instance of LegacyAutoloader
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        // Store in state for optimization
        $classesPath = path()->classes();
        $customClassesPath = path()->classes(true);
        $appPath = path()->app();
        $customAppPath = path()->app(true);
        $this->customPathMap = [
            $classesPath => $customClassesPath,
            $appPath => $customAppPath,
        ];
        $this->paths = [
            $classesPath,
            path()->zf1(),
            path()->libs(),
            $customClassesPath,     // for new classes, not customized, but in custom dir
            $appPath,
            $customAppPath          // for new classes, not customized, but in custom dir
        ];
        set_include_path(implode(PATH_SEPARATOR, $this->paths));
        spl_autoload_extensions(self::EXTENSIONS);
        $this->shouldSearchCustomClassesFromArray = ConfigRepository::getInstance()->get('core->custom->classSearchWay') === 'array';
        return $this;
    }

    /**
     * Autoloader
     * @param string $className class name to auto-load
     * @return void
     */
    public function autoload(string $className): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $filePathName = $this->normalizeClassPath($className);
        $extension = self::EXTENSIONS;
        foreach ($this->paths as $includePath) {
            $classRootPath = "{$includePath}{$ds}{$filePathName}{$extension}";
            if (is_file($classRootPath)) {
                // TODO: possibly we need to store class names (root paths) of already loaded classes for optimization (and use require instead of require_once)
                require_once $classRootPath;
                if (isset($this->customPathMap[$includePath])) {
                    $customPath = $this->customPathMap[$includePath];
                    $this->requireCustomClass($className, $filePathName, $customPath);
                }
                return;
            }
        }
    }

    /**
     * Search for customized class and require if found
     * @param string $className
     * @param string $filePathName
     * @param string $customPath
     * @return void
     */
    protected function requireCustomClass(string $className, string $filePathName, string $customPath): void
    {
        $isFound = self::$customClassFoundStatus[$className] ?? false;
        if (!$isFound) {
            $ds = DIRECTORY_SEPARATOR;
            $extension = self::EXTENSIONS;
            $customClassRootPath = "{$customPath}{$ds}{$filePathName}{$extension}";
            if ($this->shouldSearchCustomClassesFromArray) {
                $isFound = $this->isCustomClassRegistered($className);
            } else {
                $isFound = is_file($customClassRootPath);
            }
            if ($isFound) {
                require_once $customClassRootPath;
                self::$customClassFoundStatus[$className] = true;
            }
        }
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalizeClassPath(string $path): string
    {
        $path = preg_replace('|[^a-z0-9]+|i', DIRECTORY_SEPARATOR, $path);
        return $path;
    }

    /**
     * Check if customized class exists
     * @param string $className
     * @return bool
     */
    protected function isCustomClassRegistered(string $className): bool
    {
        if ($this->customClasses === null) {
            if (ConfigRepository::getInstance()->get('core->custom->registry')) {
                $this->customClasses = ConfigRepository::getInstance()->get('core->custom->registry')->toArray();
            }
        }
        $isRegistered = $this->customClasses[$className] ?? false;
        return $isRegistered;
    }
}
