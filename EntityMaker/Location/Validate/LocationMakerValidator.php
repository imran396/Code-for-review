<?php
/**
 * Class for validating of location input data
 *
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Validate;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerDtoHelperAwareTrait;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\EntityMaker\Location\Validate\Constants\ResultCode;

/**
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getCountryErrorMessage()
 * @method getNameErrorMessage()
 * HasError Methods
 * @method hasCountryError()
 * @method hasNameError()
 *
 * @method LocationMakerInputDto getInputDto()
 * @method LocationMakerConfigDto getConfigDto()
 */
class LocationMakerValidator extends BaseMakerValidator
{
    use LocationMakerDtoHelperAwareTrait;

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::COUNTRY_UNKNOWN => 'Country',
        ResultCode::NAME_EXIST => 'Name',
        ResultCode::NAME_LENGTH_LIMIT => 'Name',
        ResultCode::NAME_REQUIRED => 'Name',
        ResultCode::STATE_UNKNOWN => 'State',
        ResultCode::SYNC_KEY_EXIST => 'SyncKey',
    ];

    /** @var string[] */
    protected array $errorMessages = [
        ResultCode::COUNTRY_UNKNOWN => 'Unknown',
        ResultCode::NAME_EXIST => 'Already exist',
        ResultCode::NAME_LENGTH_LIMIT => 'Length limit reached',
        ResultCode::NAME_REQUIRED => 'Required',
        ResultCode::STATE_UNKNOWN => 'Unknown',
        ResultCode::SYNC_KEY_EXIST => 'Already exist',
    ];

    protected function initColumnNames(): void
    {
        $columnHeaders = $this->cfg()->get('csv->admin->location');
        $this->columnNames = [
            ResultCode::COUNTRY_UNKNOWN => $columnHeaders->{Constants\Csv\Location::COUNTRY},
            ResultCode::NAME_EXIST => $columnHeaders->{Constants\Csv\Location::NAME},
            ResultCode::NAME_LENGTH_LIMIT => $columnHeaders->{Constants\Csv\Location::NAME},
            ResultCode::NAME_REQUIRED => $columnHeaders->{Constants\Csv\Location::NAME},
            ResultCode::STATE_UNKNOWN => $columnHeaders->{Constants\Csv\Location::STATE},
            ResultCode::SYNC_KEY_EXIST => '',
        ];
    }

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LocationMakerInputDto $inputDto
     * @param LocationMakerConfigDto $configDto
     * @return static
     */
    public function construct(
        LocationMakerInputDto $inputDto,
        LocationMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->getLocationMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $inputDto = $this->getLocationMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $configDto = $this->getConfigDto();
        $countryCode = AddressRenderer::new()->normalizeCountry($inputDto->country);

        if (!$configDto->mode->isWebAdmin()) {
            $this->addTagNamesToErrorMessages();
        }

        $this->checkCountry('country', ResultCode::COUNTRY_UNKNOWN);
        if (AddressChecker::new()->isCountryWithStates($countryCode)) {
            $this->checkState('country', 'state', ResultCode::STATE_UNKNOWN);
        }
        $this->checkNotExistLocationName('name', ResultCode::NAME_EXIST);
        if (!$configDto->entityType) {
            $this->checkRequired('name', ResultCode::NAME_REQUIRED);
        }
        $this->checkLength('name', ResultCode::NAME_LENGTH_LIMIT, $this->cfg()->get('core->location->name->lengthLimit'));
        $this->checkSyncKeyUnique('syncKey', ResultCode::SYNC_KEY_EXIST, Constants\EntitySync::TYPE_LOCATION);

        $this->log();
        $isValid = empty($this->errors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    /** GetErrors Methods */

    /**
     * Get country errors
     * @return int[]
     */
    public function getCountryErrors(): array
    {
        return array_intersect($this->errors, [ResultCode::COUNTRY_UNKNOWN]);
    }

    /**
     * Get name errors
     * @return int[]
     */
    public function getNameErrors(): array
    {
        return array_intersect(
            $this->errors,
            [
                ResultCode::NAME_EXIST,
                ResultCode::NAME_LENGTH_LIMIT,
                ResultCode::NAME_REQUIRED,
            ]
        );
    }

    /**
     * Get state errors
     * @return int[]
     */
    public function getStateErrors(): array
    {
        return array_intersect($this->errors, [ResultCode::STATE_UNKNOWN]);
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_trace('Location validation done' . composeSuffix(['loc' => $inputDto->id]));
        } else {
            // detect names of constants for error codes
            [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
                ->construct()
                ->resolveManyFromClass($this->errors, ResultCode::class);

            $foundNamesWithCodes = array_map(
                static function ($v) {
                    return "{$v[1]} ({$v[0]})";
                },
                $foundNamesToCodes
            );
            $logData = [
                'acc' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('Location validation failed' . composeSuffix($logData));
        }
    }

    /* Location validation rules */

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistLocationName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        $skipIds = $inputDto->id ? [(int)$inputDto->id] : [];
        $isFoundByName = $this->getLocationExistenceChecker()->existByName($inputDto->$field, $configDto->serviceAccountId, $skipIds);
        $this->addErrorIfFail($error, !$isFoundByName);
    }
}
