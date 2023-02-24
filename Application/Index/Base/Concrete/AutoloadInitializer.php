<?php
/**
 * SAM-5171: Application layer
 * SAM-1921: Autoloading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\Autoload\LegacyAutoloader;
use Sam\Application\Autoload\QcodoAutoloader;
use Sam\Core\Functions\GlobalFunctionFileLoader;

/**
 * Class AutoloadInitializer
 * @package
 */
class AutoloadInitializer extends CustomizableClass
{
    public const AUTO_COMPOSER = 1;
    public const AUTO_INCLUDES = 2;
    public const AUTO_QCODO = 3;
    public const AUTO_LEGACY = 4;
    public const ALL_AUTOLOADERS = [
        self::AUTO_COMPOSER,
        self::AUTO_INCLUDES,
        self::AUTO_QCODO,
        self::AUTO_LEGACY
    ];
    /** @var int[] */
    private array $requiredAutoloaders = self::ALL_AUTOLOADERS;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        $autoloaders = $this->getRequiredAutoloaders();
        if (in_array(self::AUTO_COMPOSER, $autoloaders, true)) {
            $this->initComposerAutoload();
        }
        if (in_array(self::AUTO_INCLUDES, $autoloaders, true)) {
            $this->initAutoIncludes();
        }
        if (in_array(self::AUTO_QCODO, $autoloaders, true)) {
            $this->initQcodoAutoload();
        }
        if (in_array(self::AUTO_LEGACY, $autoloaders, true)) {
            $this->initLegacyAutoload();
        }
    }

    /**
     * @return static
     */
    public function initComposerAutoload(): static
    {
        require_once path()->sysRoot() . '/vendor/autoload.php';
        return $this;
    }

    /**
     * @return static
     */
    public function initLegacyAutoload(): static
    {
        require_once path()->classes() . "/Sam/Application/Autoload/LegacyAutoloader.php";
        spl_autoload_register([LegacyAutoloader::new()->construct(), 'autoload']);
        return $this;
    }

    /**
     * @return static
     */
    public function initQcodoAutoload(): static
    {
        $classPath = path()->classes() . "/Sam/Application/Autoload/QcodoAutoloader.php";
        require_once $classPath;
        spl_autoload_register([QcodoAutoloader::new(), 'autoload']);
        return $this;
    }

    /**
     * Include global functions
     * @return static
     */
    public function initAutoIncludes(): static
    {
        GlobalFunctionFileLoader::load();
        return $this;
    }

    /**
     * @return int[]
     */
    private function getRequiredAutoloaders(): array
    {
        return $this->requiredAutoloaders;
    }

    /**
     * @param int[] $requestedAutoloaders
     * @return $this
     */
    public function requireAutoloaders(array $requestedAutoloaders): static
    {
        $this->requiredAutoloaders = $requestedAutoloaders;
        return $this;
    }
}
