<?php
/**
 * Initialize runtime
 *
 * SAM-6397: Runtime config options
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 5, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Runtime;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\ApplicationAwareTrait;
use Sam\Installation\Config\Repository\DefaultedConfigRepository;

/**
 * Class JsScriptsManager
 * @package Sam\View\Base\Render
 */
class RuntimeInitializator extends CustomizableClass
{
    use ApplicationAwareTrait;
    use PathResolverCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initialize(Ui $ui): void
    {
        if ($ui->isCli()) {
            $this->setupCli();
        } elseif ($ui->isWeb()) {
            $this->setupWeb($ui);
        }
    }

    protected function setupCli(): void
    {
        $rootFilePath = realpath($_SERVER['SCRIPT_FILENAME']);
        $filePath = mb_substr($rootFilePath, mb_strlen($this->path()->sysRoot()));
        $dirs = explode(DIRECTORY_SEPARATOR, trim($filePath, DIRECTORY_SEPARATOR));
        if ($dirs[0] !== Constants\Application::UIDIR_CLI) {
            log_debug('Script is out of CLI UI-context' . composeSuffix(['file' => $rootFilePath]));
            return;
        }
        $script = array_pop($dirs);
        $params = DefaultedConfigRepository::new()->detectCliRuntimeParams($script, $dirs);
        $this->applyIniSetParams($params);
    }

    protected function setupWeb(Ui $ui): void
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return;
        }

        $parts = preg_split('/\/|\?/', ltrim($_SERVER['REQUEST_URI'], '/'));
        $parts = array_filter($parts);
        if ($ui->isWebResponsive()) {
            array_unshift($parts, $ui->dir());
        }
        $controller = $parts[1] ?? '';
        $action = $parts[2] ?? '';
        $params = DefaultedConfigRepository::new()->detectRuntimeParams($ui->dir(), $controller, $action);
        $this->applyIniSetParams($params);
    }

    protected function applyIniSetParams(array $params): void
    {
        $iniSetParams = $params[Constants\Runtime::KEY_INI_SET] ?? [];
        foreach ($iniSetParams as $key => $value) {
            if ($key === 'error_log') {
                $value = path()->log() . DIRECTORY_SEPARATOR . $value;
            }
            ini_set($key, $value);
        }
    }
}
