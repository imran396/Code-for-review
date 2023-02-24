<?php
/**
 * Initialize admin web application and run it
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

namespace Sam\Application\Index\Admin;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class AdminEntryPointInitializer
 * @package Sam\Application\Index\Admin
 */
class AdminEntryPointInitializer extends CustomizableClass
{
    protected string $prependFilePath;
    protected string $classPath;
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
        // Initialize framework and auto-loader
        require_once $this->classPath . '/Sam/Core/Constants/Application.php';
        /**
         * Global variable required for prepend file
         * @noinspection PhpUnusedLocalVariableInspection
         */
        $uiType = Constants\Application::UI_ADMIN;
        require_once $this->prependFilePath;

        AdminEntryPointRunner::new()
            ->setExcStartTs($this->excStartTs)
            ->run();
    }
}
