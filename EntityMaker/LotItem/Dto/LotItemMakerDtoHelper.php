<?php
/**
 * Helper for DTO
 *
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 7, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Dto;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\DtoHelper;
use Sam\EntityMaker\Base\Dto\InputDto;
use Sam\EntityMaker\LotItem\Common\LotItemMakerCustomFieldManager;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class LotItemMakerDtoHelper
 * @package Sam\EntityMaker\LotItem
 */
class LotItemMakerDtoHelper extends DtoHelper
{
    use AuctionLoaderAwareTrait;
    use LotFieldConfigProviderAwareTrait;
    use SettingsManagerAwareTrait;

    /** @var LotItemMakerCustomFieldManager */
    protected LotItemMakerCustomFieldManager $lotItemMakerCustomFieldManager;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function constructLotItemMakerDtoHelper(
        Mode $mode,
        LotItemMakerCustomFieldManager $customFieldManager
    ): static {
        $this->construct($mode);
        $this->lotItemMakerCustomFieldManager = $customFieldManager;
        return $this;
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return LotItemMakerInputDto
     */
    public function prepareValues($inputDto, $configDto): LotItemMakerInputDto
    {
        if ($configDto->isInputDtoReady) {
            return $inputDto;
        }

        $inputDto = parent::prepareValues($inputDto, $configDto);
        $inputDto = $this->clearHiddenFields($inputDto, $configDto);
        return $inputDto;
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return LotItemMakerInputDto
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
     * @return bool
     */
    public function shouldAutoCreateCategory(): bool
    {
        return $this->mode->isCsv()
            ? $this->cfg()->get('core->csv->lot->autoCreateCategory')
            : $this->cfg()->get('core->soap->lot->autoCreateCategory');
    }

    /**
     * @return bool
     */
    public function shouldAutoCreateConsignor(): bool
    {
        return $this->mode->isCsv()
            ? $this->cfg()->get('core->csv->lot->autoCreateConsignor')
            : $this->cfg()->get('core->soap->lot->autoCreateConsignor');
    }

    /**
     * Get lot categories names line
     * @param LotItemMakerInputDto $inputDto
     * @return string
     */
    protected function makeCategoryNamesInOneLine(LotItemMakerInputDto $inputDto): string
    {
        $categoriesNames = [];
        if ($inputDto->categoriesNames) {
            foreach ($inputDto->categoriesNames as $name) {
                $categoriesNames[] = $name;
            }
        }
        return implode(' ', $categoriesNames);
    }

    /**
     * Set field default value
     * @param string $field
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return bool @c true if default value was set, @c false otherwise
     */
    protected function populateWithDefault(string $field, $inputDto, $configDto): bool
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        if (!$fieldConfigProvider->isVisible($field, $configDto->serviceAccountId)) {
            return false;
        }
        $value = null;
        switch ($field) {
            case 'name':
                // Allow more characters in lot title for up to LOT_NAME_LENGTH_LIMIT characters
                $value = strip_tags(str_replace('&nbsp;', '', (string)$inputDto->description));

                // Populate lot name from categories names
                if (!$value) {
                    $auction = $this->getAuctionLoader()->load($configDto->auctionId);
                    if (
                        $auction
                        && $auction->AutoPopulateLotFromCategory
                    ) {
                        $value = $this->makeCategoryNamesInOneLine($inputDto);
                    }
                }
                break;
            case 'noTaxOos':
                // SAM-1618: Tax API Service extension
                $value = $this->getSettingsManager()->get(
                    Constants\Setting::DEFAULT_LOT_ITEM_NO_TAX_OOS,
                    $configDto->serviceAccountId
                );
                $value = $value ? 'Y' : 'N';
                break;
            case 'startingBid':
                // Set default value from the first assigned category
                $value = $this->getFirstCategory($inputDto)->StartingBid;
                break;
            default:
                // If field is customField
                if ($this->isCustomField($field)) {
                    if (!isset($inputDto->$field)) {
                        break;
                    }
                    $value = $this->lotItemMakerCustomFieldManager
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
        $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        if ($this->isCustomField($field)) {
            $customFieldId = $this->lotItemMakerCustomFieldManager->findCustomFieldByName($field)->Id ?? null;
            return $customFieldId && $fieldConfigProvider->isVisibleCustomField($customFieldId, $serviceAccountId);
        }
        return $fieldConfigProvider->isVisible($field, $serviceAccountId);
    }
}
