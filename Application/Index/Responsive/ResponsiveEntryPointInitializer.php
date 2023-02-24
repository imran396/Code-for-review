<?php
/**
 * Initialize public web application and run it
 *
 * SAM-5677: Extract logic from web entry points index.php
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Responsive;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class ResponsiveEntryPointInitializer
 * @package Sam\Application\Index\Responsive
 */
class ResponsiveEntryPointInitializer extends CustomizableClass
{
    /** @var string */
    protected string $prependFilePath;
    /** @var string */
    protected string $classPath;
    /** @var float */
    protected float $excStartTs;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $prependFilePath
     * @param string $classPath
     * @return static
     */
    public function construct(string $prependFilePath, string $classPath): static
    {
        $this->prependFilePath = $prependFilePath;
        $this->classPath = $classPath;
        $this->excStartTs = microtime(true);
        return $this;
    }

    public function run(): void
    {
        $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper(trim($_SERVER['REQUEST_METHOD'])) : '';
        if ($requestMethod === 'HEAD') {
            exit(0);
        }

        // Initialize framework and auto-loader
        require_once $this->classPath . '/Sam/Core/Constants/Application.php';
        /**
         * Global variable required for prepend file
         */
        $uiType = Constants\Application::UI_RESPONSIVE;
        require_once $this->prependFilePath;

        ResponsiveEntryPointRunner::new()
            ->setExcStartTs($this->excStartTs)
            ->run();
    }
}
