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

namespace Sam\Sms\Template\Placeholder\Auction\Internal\Render;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Sms\Template\Placeholder\Auction\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class DatePlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\Auction\Internal\Render
 * @internal
 */
class DatePlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use DataProviderAwareTrait;
    use DateHelperAwareTrait;

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
        return [PlaceholderKey::START_DATE, PlaceholderKey::END_DATE];
    }

    public function render(SmsTemplatePlaceholder $placeholder, Auction $auction): string
    {
        $date = null;
        switch ($placeholder->key) {
            case PlaceholderKey::START_DATE:
                $date = $auction->detectScheduledStartDate();
                break;
            case PlaceholderKey::END_DATE:
                if ($auction->isTimed()) {
                    $date = $auction->EndDate;
                }
                break;
            default:
                throw new \InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        $dateFormatted = '';
        if ($date) {
            $timezoneLocation = $this->getDataProvider()->loadTimezoneLocation($auction->TimezoneId, true);
            $dateFormatted = $this->getDateHelper()->formatUtcDate($date, $auction->AccountId, $timezoneLocation);
        }
        return $dateFormatted;
    }
}
