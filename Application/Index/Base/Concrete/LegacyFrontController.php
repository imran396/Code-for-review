<?php
/**
 * SAM-4508 : Application class adjustments
 * https://bidpath.atlassian.net/browse/SAM-4508
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Sam\Application\Mvc\Legacy\LegacyMvcCreateTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use RuntimeException;
use Sam\Application\Controller\Dispatch\LegacyControllerDispatcher;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Zend_Controller_Exception;
use Zend_Controller_Front;

/**
 * Class Application
 * @package Sam\Application
 */
class LegacyFrontController extends CustomizableClass
{
    use EditorUserAwareTrait;
    use LegacyMvcCreateTrait;
    use OutputBufferCreateTrait;

    public const ZF1_MODULE_DEFAULT = 'default';
    public const ZF1_MODULE_CUSTOM = 'custom';

    /** @var Ui */
    protected Ui $ui;
    /** @var array */
    protected array $controllerDir = [];

    /**
     * Get instance of Application
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize the Application instance
     * @param Ui $ui
     * @return static
     */
    public function construct(Ui $ui): static
    {
        $this->ui = $ui;
        $this->initializeControllerDir();
        $this->initializeRendering();
        $this->initFrontController();
        return $this;
    }

    /**
     * @throws Zend_Controller_Exception
     */
    public function run(): void
    {
        Zend_Controller_Front::run($this->controllerDir);
    }

    protected function initializeRendering(): void
    {
        $layoutDir = path()->layout($this->ui);
        $viewSuffix = "tpl.php";
        $legacyMvc = $this->createLegacyMvc();
        $legacyMvc->startMvc(
            [
                "layoutPath" => $layoutDir,
                "viewSuffix" => $viewSuffix
            ]
        );
        $legacyMvc->setLayout("default");
        $legacyMvc->addViewRendererHelper($viewSuffix);
    }

    protected function initializeControllerDir(): void
    {
        $this->controllerDir = [
            self::ZF1_MODULE_DEFAULT => path()->controller($this->ui),
            self::ZF1_MODULE_CUSTOM => path()->controller($this->ui, true),
        ];
    }

    /**
     * Initialize ZF front controller
     */
    protected function initFrontController(): void
    {
        /** @var Zend_Controller_Front|null $front */
        $front = Zend_Controller_Front::getInstance();
        if (!$front) {
            throw new RuntimeException("Available zend front controller not found");
        }
        $front->setDispatcher(new LegacyControllerDispatcher());
        $front->throwExceptions(true);
    }
}
