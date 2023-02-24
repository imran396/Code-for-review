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
use Sam\Date\CurrentDateTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class TimeLeftPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render
 * @internal
 */
class TimeLeftPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use CurrentDateTrait;
    use DataProviderAwareTrait;
    use DateRendererCreateTrait;
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
        return [PlaceholderKey::TIME_LEFT];
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
            $endDate = $auction->EndDate;
            if ($auction->StaggerClosing) {
                $endDate = $this->createStaggerClosingHelper()
                    ->calcEndDate(
                        $auction->StartClosingDate,
                        $auction->LotsPerInterval,
                        $auction->StaggerClosing,
                        $auctionLot->Order
                    );
            }
        } else {
            $endDate = $auctionLot->EndDate;
        }
        $seconds = $endDate->getTimestamp() - $this->getCurrentDateUtc()->getTimestamp();
        if ($seconds < 0) {
            return '';
        }
        $timeLeft = $this->createDateRenderer()->renderTimeLeft($seconds, ['extensions' => [' days ', ':', ':', '']]);
        return $timeLeft;
    }
}
