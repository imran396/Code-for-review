<?php
/**
 * This is immutable service, it detects array of paths for configs, ex: jsScripts, cssLinks
 * This object is pure from any dependency. Object state is explicitly defined after construction and cannot be changed.
 *
 * SAM-4586: Refactor CssLinksManager view helper to customized class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/25/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

use InvalidArgumentException;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Web\ControllerAction\UiControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryInterface;

/**
 * Class PathDetector
 * @package Sam\View\Base\Render
 */
class AssetPathDetector extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const EXP_CONFIG_NAME_INCORRECT = 1;
    public const EXP_NO_CONFIG_DATA = 2;

    public const ERR_AREA_ABSENT = 1;
    public const ERR_CONTROLLER_ABSENT = 2;
    public const ERR_ACTION_ABSENT = 3;
    public const ERR_PATH_NOT_ARRAY = 4;

    /**
     * Look, we don't mark these fields as nullable, because
     * we suppose constructor() as mandatory method for this class object instantiating
     */

    /**
     * This service handles single config file
     */
    protected string $configName;
    /**
     * Config data is shared in-process dependency, but it is shared among application process only, so we don't expose it to out-of-process dependencies
     */
    protected UiControllerActionCollection $ucaCollection;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Extracts data from config repository and stores in service immutable state
     * @param ConfigRepositoryInterface $configRepository
     * @param string $configName
     * @return static
     * @throws InvalidArgumentException
     */
    public function construct(ConfigRepositoryInterface $configRepository, string $configName): static
    {
        // Check class invariants
        try {
            $configData = $configRepository->{$configName};
        } catch (InvalidArgumentException $e) { // @phpstan-ignore-line
            throw new InvalidArgumentException(
                'Not supported config name' . composeSuffix(['config' => $configName]),
                self::EXP_CONFIG_NAME_INCORRECT
            );
        }

        if (empty($configData->toArray())) {
            throw new InvalidArgumentException(
                'No Config data for config name' . composeSuffix(['config' => $configName]),
                self::EXP_NO_CONFIG_DATA
            );
        }

        // Initialize immutable object state
        $this->configName = $configName;
        /**
         * Save config data required for the task in constructor.
         */
        $this->ucaCollection = UiControllerActionCollection::new()->fromUiAreaArray($configData->toArray());

        $errorMessages = [
            self::ERR_AREA_ABSENT => 'Config options area cannot be found',
            self::ERR_CONTROLLER_ABSENT => 'Config options for controller cannot be found',
            self::ERR_ACTION_ABSENT => 'Config options for action cannot be found',
            self::ERR_PATH_NOT_ARRAY => 'Config options should be array',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Return array of url paths or routes
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @return array - return empty array, when data not found
     */
    public function detect(
        Ui $ui,
        string $controller,
        string $action
    ): array {
        if (!$this->validatePreconditions($ui, $controller, $action)) {
            log_info($this->getResultStatusCollector()->getConcatenatedErrorMessageSuffixedByPayload("\n"));
            return [];
        }

        return $this->ucaCollection->get($ui, $controller, $action);
    }

    /**
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @return bool
     */
    protected function validatePreconditions(
        Ui $ui,
        string $controller,
        string $action
    ): bool {
        // Ui area is the same as the directory
        $area = $ui->dir();
        $logData = [
            'configName' => $this->configName,
            'ui' => $ui,
            'area' => $area,
            'controller' => $controller,
            'action' => $action,
        ];

        if (!$this->ucaCollection->hasUi($ui)) {
            $this->getResultStatusCollector()->addError(self::ERR_AREA_ABSENT, null, $logData);
            return false;
        }

        if (!$this->ucaCollection->hasUiController($ui, $controller)) {
            $this->getResultStatusCollector()->addError(self::ERR_CONTROLLER_ABSENT, null, $logData);
            return false;
        }

        if (!$this->ucaCollection->has($ui, $controller, $action)) {
            $this->getResultStatusCollector()->addError(self::ERR_ACTION_ABSENT, null, $logData);
            return false;
        }

        $value = $this->ucaCollection->get($ui, $controller, $action);
        if (!is_array($value)) {
            $this->getResultStatusCollector()->addError(self::ERR_PATH_NOT_ARRAY, null, $logData);
            return false;
        }

        return true;
    }
}
