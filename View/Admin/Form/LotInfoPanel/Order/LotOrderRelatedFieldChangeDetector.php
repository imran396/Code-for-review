<?php
/**
 * SAM-8087: For manually added lots Staggered Closing Time Not Recalculated even after refreshing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\Order;

use AuctionLotItem;
use DateTime;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Panel\LotInfoPanel;

/**
 * Class LotOrderRelatedFieldChangeDetector
 * @package Sam\View\Admin\Form\LotInfoPanel\Order
 */
class LotOrderRelatedFieldChangeDetector extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldsAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param LotInfoPanel $infoPanel
     * @return bool
     */
    public function isChanged(int $auctionId, int $lotItemId, LotInfoPanel $infoPanel): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error("Available Auction not found" . composeSuffix(['a' => $auctionId]));
            return false;
        }
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if (!$lotItem) {
            log_error("Available LotItem not found" . composeSuffix(['li' => $lotItemId]));
            return false;
        }
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (!$auctionLot) {
            log_error("Available AuctionLot not found" . composeSuffix(['a' => $auctionId, 'li' => $lotItemId]));
            return false;
        }

        $orderTypes = [
            'Primary' => $auction->LotOrderPrimaryType,
            'Secondary' => $auction->LotOrderSecondaryType,
            'Tertiary' => $auction->LotOrderTertiaryType,
            'Quaternary' => $auction->LotOrderQuaternaryType
        ];
        $orderCustomFieldIds = [
            'Primary' => $auction->LotOrderPrimaryCustFieldId,
            'Secondary' => $auction->LotOrderSecondaryCustFieldId,
            'Tertiary' => $auction->LotOrderTertiaryCustFieldId,
            'Quaternary' => $auction->LotOrderQuaternaryCustFieldId
        ];

        foreach ($orderTypes as $priority => $orderType) {
            $isChanged = false;
            if ($orderType === Constants\Auction::LOT_ORDER_BY_LOT_NUMBER) {
                $isChanged = $this->isAuctionLotNumChanged($auctionLot, $infoPanel);
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_ITEM_NUMBER) {
                $isChanged = $this->isLotItemNumChanged($lotItem, $infoPanel);
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_CATEGORY) {
                $isChanged = $this->isCategoriesChanged($lotItem, $infoPanel);
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_CUST_FIELD) {
                $customFieldId = (int)$orderCustomFieldIds[$priority];
                $isChanged = $this->isCustomFieldChanged($lotItem, $customFieldId, $infoPanel);
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_NAME) {
                $isChanged = $this->isNameChanged($lotItem, $infoPanel);
            }
            if ($isChanged) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param LotItem $lotItem
     * @param LotInfoPanel $infoPanel
     * @return bool
     */
    protected function isNameChanged(LotItem $lotItem, LotInfoPanel $infoPanel): bool
    {
        return $lotItem->Name !== $infoPanel->txtName->Text;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param LotInfoPanel $infoPanel
     * @return bool
     */
    protected function isAuctionLotNumChanged(AuctionLotItem $auctionLot, LotInfoPanel $infoPanel): bool
    {
        return $infoPanel->txtNo->Text !== (string)$auctionLot->LotNum
            || $infoPanel->txtLotNumExt->Text !== $auctionLot->LotNumExt
            || $infoPanel->txtLotNumPrefix->Text !== $auctionLot->LotNumPrefix
            || $infoPanel->txtLotFullNum->Text !== $this->getLotRenderer()->makeLotNo($auctionLot->LotNum, $auctionLot->LotNumExt, $auctionLot->LotNumPrefix);
    }

    /**
     * @param LotItem $lotItem
     * @param LotInfoPanel $infoPanel
     * @return bool
     */
    protected function isLotItemNumChanged(LotItem $lotItem, LotInfoPanel $infoPanel): bool
    {
        if ($infoPanel->isConcatenatedItemNo()) {
            return $infoPanel->txtItemFullNum->Text !== $this->getLotRenderer()->makeItemNo($lotItem->ItemNum, $lotItem->ItemNumExt);
        }

        return $infoPanel->txtItemNum->Text !== (string)$lotItem->ItemNum
            || $infoPanel->txtItemNumExt->Text !== $lotItem->ItemNumExt;
    }

    /**
     * @param LotItem $lotItem
     * @param LotInfoPanel $infoPanel
     * @return bool
     */
    protected function isCategoriesChanged(LotItem $lotItem, LotInfoPanel $infoPanel): bool
    {
        $oldCategoryIds = $this->getLotCategoryLoader()->loadIdsForLot($lotItem->Id);
        $newCategoryIds = $this->getCategoryIdsByName($infoPanel->pnlCategory->getNames());
        $isChanged = array_diff($oldCategoryIds, $newCategoryIds) || array_diff($newCategoryIds, $oldCategoryIds);
        return $isChanged;
    }

    /**
     * Get lot category Ids by  categoriesNames
     * @param array $categoryNames
     * @return array
     */
    private function getCategoryIdsByName(array $categoryNames): array
    {
        $categories = [];
        if ($categoryNames) {
            foreach ($categoryNames as $categoryName) {
                $categoryName = trim($categoryName);
                if ($categoryName === '') {
                    continue;
                }
                $category = $this->getLotCategoryLoader()->loadByName($categoryName);
                $categories[] = $category->Id ?? null;
            }
        }

        return array_unique($categories);
    }

    /**
     * @param LotItem $lotItem
     * @param int $lotCustomFieldId
     * @param LotInfoPanel $infoPanel
     * @return bool
     */
    protected function isCustomFieldChanged(LotItem $lotItem, int $lotCustomFieldId, LotInfoPanel $infoPanel): bool
    {
        $lotCustomFieldValues = $infoPanel->getCustomFieldValues();
        if (!array_key_exists($lotCustomFieldId, $lotCustomFieldValues)) {
            return false;
        }

        $newValue = (string)$lotCustomFieldValues[$lotCustomFieldId];
        $oldValue = $this->getLotCustomDataValue($lotCustomFieldId, $lotItem->Id);
        return $newValue !== $oldValue;
    }

    /**
     * @param int $lotCustomFieldId
     * @param int $lotItemId
     * @return string
     */
    protected function getLotCustomDataValue(int $lotCustomFieldId, int $lotItemId): string
    {
        $lotCustomField = $this->getLotCustomFieldById($lotCustomFieldId, true);
        if ($lotCustomField) {
            $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $lotItemId);
            if ($lotCustomData) {
                if ($lotCustomField->isNumeric()) {
                    if ($lotCustomData->Numeric === null) {
                        $value = '';
                    } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                        $value = (new DateTime())->setTimestamp($lotCustomData->Numeric)->format(Constants\Date::ISO);
                    } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                        $precision = (int)$lotCustomField->Parameters;
                        $realValue = $lotCustomData->calcDecimalValue($precision);
                        $value = $this->getNumberFormatter()->formatNto($realValue, $precision);
                    } else {
                        $value = (string)$lotCustomData->Numeric;
                    }
                    return $value;
                }
                return $lotCustomData->Text;
            }
        }
        return '';
    }
}
