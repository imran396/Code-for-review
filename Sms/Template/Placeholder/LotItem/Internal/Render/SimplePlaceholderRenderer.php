<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal\Render;

use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\LotItem\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class SimplePlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem
 */
class SimplePlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getApplicablePlaceholderKeys(): array
    {
        return [
            PlaceholderKey::ID,
            PlaceholderKey::DESCRIPTION,
            PlaceholderKey::WARRANTY,
            PlaceholderKey::LOW_ESTIMATE,
            PlaceholderKey::HIGH_ESTIMATE,
            PlaceholderKey::STARTING_BID,
            PlaceholderKey::COST,
            PlaceholderKey::RESERVE_PRICE,
            PlaceholderKey::HAMMER_PRICE,
            PlaceholderKey::REPLACEMENT_PRICE,
            PlaceholderKey::SALES_TAX,
            PlaceholderKey::INTERNET_BID,
            PlaceholderKey::NO_TAX_OUTSIDE_STATE,
            PlaceholderKey::RETURNED,
        ];
    }

    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string
    {
        return match ($placeholder->key) {
            PlaceholderKey::ID => (string)$lotItem->Id,
            PlaceholderKey::NAME => $lotItem->Name,
            PlaceholderKey::DESCRIPTION => $lotItem->Description,
            PlaceholderKey::WARRANTY => $lotItem->Warranty,
            PlaceholderKey::LOW_ESTIMATE => (string)$lotItem->LowEstimate,
            PlaceholderKey::HIGH_ESTIMATE => (string)$lotItem->HighEstimate,
            PlaceholderKey::STARTING_BID => (string)$lotItem->StartingBid,
            PlaceholderKey::COST => (string)$lotItem->Cost,
            PlaceholderKey::RESERVE_PRICE => (string)$lotItem->ReservePrice,
            PlaceholderKey::HAMMER_PRICE => (string)$lotItem->HammerPrice,
            PlaceholderKey::REPLACEMENT_PRICE => (string)$lotItem->ReplacementPrice,
            PlaceholderKey::SALES_TAX => (string)$lotItem->SalesTax,
            PlaceholderKey::INTERNET_BID => $lotItem->InternetBid ? 'Yes' : 'No',
            PlaceholderKey::NO_TAX_OUTSIDE_STATE => $lotItem->NoTaxOos ? 'Yes' : 'No',
            PlaceholderKey::RETURNED => $lotItem->Returned ? 'Yes' : 'No',
            default => throw new \InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'"),
        };
    }
}
