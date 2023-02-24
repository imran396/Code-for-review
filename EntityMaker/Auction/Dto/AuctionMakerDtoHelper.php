<?php
/**
 * Helper for DTO
 *
 * SAM-8840: Auction entity-maker module structural adjustments for v3-5
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Dto;

use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Auction\FieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Core\Constants;
use Sam\EntityMaker\Auction\Common\AuctionMakerCustomFieldManager;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\DtoHelper;
use Sam\EntityMaker\Base\Dto\InputDto;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class AuctionMakerDtoHelper
 * @package Sam\EntityMaker\Auction
 */
class AuctionMakerDtoHelper extends DtoHelper
{
    use AuctionFieldConfigProviderAwareTrait;
    use SettingsManagerAwareTrait;

    protected ?AuctionMakerCustomFieldManager $auctionMakerCustomFieldManager;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function constructAuctionMakerDtoHelper(
        Mode $mode,
        AuctionMakerCustomFieldManager $customFieldManager
    ): static {
        $this->construct($mode);
        $this->auctionMakerCustomFieldManager = $customFieldManager;
        return $this;
    }

    /**
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return AuctionMakerInputDto
     */
    public function prepareValues($inputDto, $configDto): AuctionMakerInputDto
    {
        if ($configDto->isInputDtoReady) {
            return $inputDto;
        }

        $inputDto = parent::prepareValues($inputDto, $configDto);
        $inputDto = $this->clearHiddenFields($inputDto, $configDto);
        return $inputDto;
    }

    /**
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return AuctionMakerInputDto
     */
    protected function clearHiddenFields(InputDto $inputDto, ConfigDto $configDto): InputDto
    {
        foreach ($inputDto->keys() as $field) {
            if (!$this->isVisibleField($field, $configDto->serviceAccountId)) {
                if (is_array($inputDto->{$field})) {
                    $inputDto->{$field} = [];
                } else {
                    $inputDto->{$field} = '';
                }
            }
        }
        return $inputDto;
    }

    /**
     * Set field default value
     * @param string $field
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return bool @c true if default value was set, @c false otherwise
     */
    protected function populateWithDefault(string $field, $inputDto, $configDto): bool
    {
        $value = null;
        switch ($field) {
            case 'absenteeBidsDisplay':
                $code = $this->getSettingsManager()->get(
                    Constants\Setting::ABSENTEE_BIDS_DISPLAY,
                    $configDto->serviceAccountId
                );
                if (isset(Constants\SettingAuction::ABSENTEE_BID_DISPLAY_SOAP_VALUES[$code])) {
                    $value = Constants\SettingAuction::ABSENTEE_BID_DISPLAY_SOAP_VALUES[$code];
                }
                break;
            default:
                // If field is customField
                if ($this->isCustomField($field)) {
                    if (!isset($inputDto->$field)) {
                        break;
                    }
                    $value = $this->auctionMakerCustomFieldManager
                        ->setInputDto($inputDto)
                        ->initCustomFieldByName($field);
                }
        }
        if ($value) {
            $inputDto->$field = $value;
        }
        return (bool)$value;
    }

    protected function isVisibleField(string $field, int $serviceAccountId): bool
    {
        $fieldConfigProvider = $this->getAuctionFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        if ($this->isCustomField($field)) {
            $customFieldId = $this->auctionMakerCustomFieldManager->findCustomFieldByName($field)->Id ?? null;
            return $customFieldId && $fieldConfigProvider->isVisibleCustomField($customFieldId, $serviceAccountId);
        }
        return $fieldConfigProvider->isVisible($field, $serviceAccountId);
    }
}
