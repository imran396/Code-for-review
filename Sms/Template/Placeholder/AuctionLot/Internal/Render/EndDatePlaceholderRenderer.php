<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render;

use AuctionLotItem;
use InvalidArgumentException;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class EndDatePlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render
 * @internal
 */
class EndDatePlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use DataProviderAwareTrait;
    use DateHelperAwareTrait;
    use StaggerClosingHelperCreateTrait;

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
        return [PlaceholderKey::END_DATE];
    }

    public function render(SmsTemplatePlaceholder $placeholder, AuctionLotItem $auctionLot): string
    {
        if (!in_array($placeholder->key, $this->getApplicablePlaceholderKeys(), true)) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        $auction = $this->getDataProvider()->loadAuction($auctionLot->AuctionId, true);
        if (!$auction) {
            log_error("Available Auction not found" . composeSuffix(['a' => $auctionLot->AuctionId]));
            return '';
        }

        if (!$auction->isTimed()) {
            return '';
        }

        if (
            $auction->ExtendAll
            && $auctionLot->isActive()
        ) {
            $timezoneLocation = $this->getDataProvider()->loadTimezoneLocation($auction->TimezoneId);
            if ($auction->StaggerClosing) {
                $endDate = $this->createStaggerClosingHelper()
                    ->calcEndDate(
                        $auction->StartClosingDate,
                        $auction->LotsPerInterval,
                        $auction->StaggerClosing,
                        $auctionLot->Order
                    );
                $endDateFormatted = $this->getDateHelper()->formatUtcDate($endDate, $auction->AccountId, $timezoneLocation);
            } else {
                $endDateFormatted = $this->getDateHelper()->formatUtcDate($auction->EndDate, $auction->AccountId, $timezoneLocation);
            }
        } else {
            $timezoneLocation = $this->getDataProvider()->loadTimezoneLocation($auctionLot->TimezoneId);
            $endDateFormatted = $this->getDateHelper()->formatUtcDate($auctionLot->EndDate, $auction->AccountId, $timezoneLocation);
        }
        return $endDateFormatted;
    }
}
