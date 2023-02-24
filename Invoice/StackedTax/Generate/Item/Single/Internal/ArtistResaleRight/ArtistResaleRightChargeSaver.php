<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight;

use InvoiceAdditional;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput as Input;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;

/**
 * Class InvoiceAdditionalSaver
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight
 */
class ArtistResaleRightChargeSaver extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produceArtistResaleRightCharge(Input $input): ?InvoiceAdditional
    {
        $lotItem = $input->lotItem;
        /**
         * Create InvoiceAdditional record only when "Tax Artist Resale Rights" property is enabled for the lot item.
         */
        if (!$lotItem->LotItemTaxArr) {
            return null;
        }

        $dataProvider = $this->createDataProvider();
        $hammerPrice = (float)$lotItem->HammerPrice;
        $currencyId = $dataProvider->detectDefaultCurrencyId($input->auctionId);
        $sourceExRate = $dataProvider->loadExRateById($currencyId);
        $arrCurrency = $this->cfg()->get('core->vendor->artistResaleRights->currency');
        $destExRate = $dataProvider->loadExRateByCode($arrCurrency);
        if (!$destExRate) {
            return null;
        }

        $convertedHammerPrice = $dataProvider->convertByRates((string)$hammerPrice, $sourceExRate, $destExRate);
        $arrPrice = $this->cfg()->get('core->vendor->artistResaleRights->price');
        if (Floating::lteq($convertedHammerPrice, $arrPrice)) {
            return null;
        }

        $percent = $this->cfg()->get('core->vendor->artistResaleRights->tax');

        $name = "Artist Resale Rights - " . $lotItem->Name;
        $amount = $hammerPrice * ($percent / 100);
        $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
            Constants\Invoice::IA_ARTIST_RESALE_RIGHTS,
            $input->invoiceId,
            $name,
            $amount,
            $input->editorUserId
        );

        return $invoiceAdditional;
    }
}
