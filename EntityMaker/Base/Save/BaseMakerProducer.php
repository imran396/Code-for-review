<?php
/**
 * Base abstract class for producing of entity
 *
 * SAM-3874 Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save;

use Account;
use Auction;
use AuctionLotItem;
use DateTime;
use DateTimeZone;
use Location;
use LotItem;
use QBaseClass;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\Base\Save\Exception\CouldNotExecuteEntityMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\Location\LocationSavingIntegratorCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Delete\LocationDeleterAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use User;

/**
 * Class Producer
 * @package Sam\EntityMaker\Base
 */
abstract class BaseMakerProducer extends CustomizableClass
{
    use ConfigDtoAwareTrait;
    use ConfigRepositoryAwareTrait;
    use InputDtoAwareTrait;
    use LocationDeleterAwareTrait;
    use LocationSavingIntegratorCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;

    // Assign strategies
    public const STRATEGY_ARRAY_SEARCH = 'arraySearch';
    public const STRATEGY_BOOL = 'bool';
    public const STRATEGY_LOT_ITEM_CUSTOM_FIELD = 'customField';
    public const STRATEGY_DATE_TIME = 'dateTime';
    public const STRATEGY_DATE_TIME_CONVERT_TO_UTC = 'dateTimeConvertToUtc';
    public const STRATEGY_PARSE = 'parse';
    public const STRATEGY_REMOVE_FORMAT = 'removeFormat';
    public const STRATEGY_SPECIFIC_NAME = 'specificName';

    /**
     * @var Location[]
     */
    protected array $entityLocations = [];

    public function getEntityLocation(int $type): ?Location
    {
        return $this->entityLocations[$type] ?? null;
    }

    /**
     * Check if input DTO is valid
     */
    protected function assertInputDto(): void
    {
        $validationStatus = $this->getConfigDto()->validationStatus;
        if (!$validationStatus->isValidated()) {
            throw CouldNotExecuteEntityMakerProducer::becauseInputDtoNotValidated();
        }

        if (!$validationStatus->isValid()) {
            throw CouldNotExecuteEntityMakerProducer::becauseInputDtoValidationFailed();
        }
    }

    public function setEntityLocation(int $type, ?Location $value): void
    {
        $this->entityLocations[$type] = $value;
    }

    /**
     * Set entity field if it's assigned in dto and apply specific strategy if present
     * @param Account|Auction|AuctionLotItem|LotItem|User|QBaseClass $entity
     * @param string $field
     * @param string|null $strategy
     * @param string|array<string|int>|null $options
     */
    protected function setIfAssign(
        QBaseClass $entity,
        string $field,
        string $strategy = null,
        string|array $options = null
    ): void {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->$field)) {
            $value = $inputDto->$field;
            switch ($strategy) {
                case self::STRATEGY_ARRAY_SEARCH:
                    $value = array_search($value, $options);
                    break;
                case self::STRATEGY_BOOL:
                    $value = ValueResolver::new()->isTrue((string)$value);
                    break;
                case self::STRATEGY_LOT_ITEM_CUSTOM_FIELD:
                    $value = $this->createLotCustomFieldLoader()->loadByName($value);
                    break;
                case self::STRATEGY_DATE_TIME:
                    $value = $value ? new DateTime($value) : null;
                    break;
                case self::STRATEGY_DATE_TIME_CONVERT_TO_UTC:
                    $timezone = $inputDto->$options;
                    if ($value && $timezone) {
                        $value = new DateTime($value, new DateTimeZone($timezone));
                        $value = $value->setTimezone(new DateTimeZone('UTC'));
                    } else {
                        $value = null;
                    }
                    break;
                case self::STRATEGY_PARSE:
                    $precision = array_key_exists('precision', (array)$options) ? $options['precision'] : 2;
                    if ((string)$value === '') {
                        $value = null;
                        break;
                    }
                    // SAM-11418: Avoid number formatting in API
                    $value = $this->getConfigDto()->mode->isSoap()
                        ? (float)$value
                        : $this->getNumberFormatter()->parse($value, $precision);
                    break;
                case self::STRATEGY_REMOVE_FORMAT:
                    // SAM-11418: Avoid number formatting in API
                    $value = $this->getConfigDto()->mode->isSoap()
                        ? (float)$value
                        : $this->getNumberFormatter()->removeFormat($value);
                    break;
                case self::STRATEGY_SPECIFIC_NAME:
                    $field = $options;
                    break;
            }
            $propertyName = ucfirst($field);
            $entity->{$propertyName} = $value;
        }
    }
}
