<?php
/**
 * SAM-9730: Refactor SMS notification module
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render;

use AuctionLotItem;
use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class QuantityPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render
 */
class QuantityPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use LotAmountRendererFactoryCreateTrait;

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
        return [PlaceholderKey::QUANTITY];
    }

    public function render(SmsTemplatePlaceholder $placeholder, AuctionLotItem $auctionLot): string
    {
        if ($placeholder->key !== PlaceholderKey::QUANTITY) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }
        return $this->createLotAmountRendererFactory()
            ->create($auctionLot->AccountId)
            ->renderQuantity($auctionLot);
    }
}
