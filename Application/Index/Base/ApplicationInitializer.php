<?php
/**
 * SAM-5171: Application layer
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

namespace Sam\Application\Index\Base;

use Exception;
use Sam\Application\Controller\Runtime\RuntimeInitializator;
use Sam\Application\Index\Base\Concrete\NativeSession\PhpSessionInitializer;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use QApplication;
use Sam\Application\Application;
use Sam\Application\Index\Base\Concrete\AuthIdentityInitializer;
use Sam\Application\Index\Base\Concrete\AutoloadInitializer;
use Sam\Application\Index\Base\Concrete\ProcessGuidInitializer;
use Sam\Application\Index\Base\Concrete\RemoteIpInitializer;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepository;

require_once path()->classes() . '/Sam/Application/Index/Base/Concrete/AutoloadInitializer.php';

/**
 * Class ApplicationInitializer
 * @package
 */
class ApplicationInitializer extends CustomizableClass
{
    /**
     * true - for prepend.inc.php
     * false - for prepend_min.inc.php
     * @var bool
     */
    protected bool $isCompletePrepend = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initialize(int $uiType = null): void
    {
        // Initializations for every application mode
        $this->initErrorLog();
        $this->initAutoload();
        $this->initLocale();
        $this->initQcodo();

        $uiType = $uiType ?? Constants\Application::UI_CLI;
        $ui = Ui::new()->construct($uiType);
        RuntimeInitializator::new()->initialize($ui);
        ProcessGuidInitializer::new()->initialize($ui);
        if ($ui->isWeb()) {
            // Initializations for web application modes
            PhpSessionInitializer::new()
                ->construct($ui)
                ->initialize();
            AuthIdentityInitializer::new()->initialize();
            $this->initializeServerSignature();
            $this->initializeXFrameOptionsHeader();
            $this->initializeHttpHeaderForCaching();
            RemoteIpInitializer::new()->initialize();
        }

        Application::getInstance()->construct($ui);
    }

    /**
     * @return bool
     */
    public function isCompletePrepend(): bool
    {
        return $this->isCompletePrepend;
    }

    /**
     * @param bool $isCompletePrepend
     * @return static
     */
    public function enableCompletePrepend(bool $isCompletePrepend): static
    {
        $this->isCompletePrepend = $isCompletePrepend;
        return $this;
    }

    /**
     * @param int[] $autoloaders
     * @return static
     */
    public function initAutoload(array $autoloaders = AutoloadInitializer::ALL_AUTOLOADERS): static
    {
        AutoloadInitializer::new()
            ->requireAutoloaders($autoloaders)
            ->initialize();
        return $this;
    }

    /**
     * @return static
     */
    public function initErrorLog(): static
    {
        ini_set('error_log', path()->log() . '/error.log');
        return $this;
    }

    /**
     * @return static
     */
    public function initLocale(): static
    {
        // Set default timezone UTC
        date_default_timezone_set('UTC');
        // set standard encoding for mb_... functions
        mb_internal_encoding('UTF-8');
        return $this;
    }

    /**
     * Include Qcodo Framework and initialize application and db connection
     * @return static
     * @throws Exception
     */
    protected function initQcodo(): static
    {
        require path()->qcodoCore() . '/qcodo.inc.php';

        // Initialize the Application and DB Connections. TODO: eliminate in SAM-5728
        QApplication::Initialize();

        $this->preloadQcodoClasses();

        if (!$this->isCompletePrepend()) {
            // includes\libs\qcodo\_core\qform_state_handlers\QFormStateHandler.php
            $cfg = ConfigRepository::getInstance();
            $cfg->set('core->app->qform->stateHandler', 'QFormStateHandler');
            $cfg->set('core->app->qform->encryptionKey', null);
        }
        return $this;
    }

    protected function preloadQcodoClasses(): void
    {
        // Preload all required "Pre-load" Class Files
        foreach (QApplication::$PreloadedClassFile as $fileRootPath) {
            require $fileRootPath;
        }
    }

    public function initializeServerSignature(): static
    {
        $cfg = ConfigRepository::getInstance();
        if ($cfg->get('core->app->header->xPoweredBy')) {
            header('X-Powered-By: ' . $cfg->get('core->app->header->xPoweredBy'));
        }
        return $this;
    }

    /**
     * Set X-Frame-Options HTTP response header
     */
    public function initializeXFrameOptionsHeader(): static
    {
        $cfg = ConfigRepository::getInstance();
        if ($cfg->get('core->app->header->xFrameOption')) {
            header("X-Frame-Options: " . $cfg->get('core->app->header->xFrameOption'));
        }
        return $this;
    }

    /**
     * Set X-Frame-Options HTTP response header
     */
    public function initializeHttpHeaderForCaching(): static
    {
        QApplication::$CacheControl = 'private, no-cache, must-revalidate, no-store';
        header('Expires: -1');
        return $this;
    }
}
