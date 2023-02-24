<?php
/**
 * Helper for DTO of Auction Lot entity-maker.
 *
 * SAM-8839: Auction Lot entity-maker module structural adjustments for v3-5
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 17, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Dto;

use Sam\Core\Constants;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\DtoHelper;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;

/**
 * Class AuctionLotMakerDtoHelper
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotMakerDtoHelper extends DtoHelper
{
    use LotFieldConfigProviderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotMakerInputDto $inputDto
     * @param AuctionLotMakerConfigDto $configDto
     * @return AuctionLotMakerInputDto
     */
    public function prepareValues($inputDto, $configDto): AuctionLotMakerInputDto
    {
        /** @var AuctionLotMakerInputDto $inputDto */
        $inputDto = parent::prepareValues($inputDto, $configDto);
        $inputDto = $this->normalizeBulkWinBidDistribution($inputDto);
        $inputDto = $this->clearHiddenFields($inputDto, $configDto);
        return $inputDto;
    }

    /**
     * @param AuctionLotMakerInputDto $inputDto
     * @return AuctionLotMakerInputDto
     */
    protected function normalizeBulkWinBidDistribution(AuctionLotMakerInputDto $inputDto): AuctionLotMakerInputDto
    {
        if (isset($inputDto->bulkWinBidDistribution)) {
            $bulkWinBidDistribution = array_search($inputDto->bulkWinBidDistribution, Constants\LotBulkGroup::$bulkWinBidDistributionNames, true);
            if ($bulkWinBidDistribution !== false) {
                $inputDto->bulkWinBidDistribution = $bulkWinBidDistribution;
            }
        }
        return $inputDto;
    }

    protected function clearHiddenFields(AuctionLotMakerInputDto $inputDto, ConfigDto $configDto): AuctionLotMakerInputDto
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        foreach ($inputDto->keys() as $field) {
            if (!$fieldConfigProvider->isVisible($field, $configDto->serviceAccountId)) {
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
     * @param AuctionLotMakerInputDto $inputDto
     * @param AuctionLotMakerConfigDto $configDto
     * @return bool @c true if default value was set, @c false otherwise
     */
    protected function populateWithDefault(string $field, $inputDto, $configDto): bool
    {
        $value = null;
        switch ($field) {
            case 'buyNowAmount':
                $value = $this->getFirstCategory($inputDto)->BuyNowAmount;
                break;
        }
        if ($value) {
            $inputDto->$field = $value;
        }
        return (bool)$value;
    }
}
