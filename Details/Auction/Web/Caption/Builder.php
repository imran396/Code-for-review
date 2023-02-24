<?php
/**
 * Completes "Auction Caption" content by parsing partially pre-built template stored in AuctionDetailsCache
 *
 * SAM-4304: Editable template for auction pages header
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Mar 30, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web\Caption;

use Sam\Auction\Load\AuctionDetailsCacheLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 */
class Builder extends \Sam\Details\Auction\Web\Builder
{
    use AuctionDetailsCacheLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function shouldHideEmptyFields(): bool
    {
        if ($this->shouldHideEmptyFields === null) {
            $this->shouldHideEmptyFields = (bool)$this->getSettingsManager()->get(
                Constants\Setting::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_ALL_CATEGORIES,
                $this->getSystemAccountId()
            );
        }
        return parent::shouldHideEmptyFields();
    }

    public function getTemplate(): string
    {
        if (!$this->template) {
            /**
             * It contains partially built content with placeholders, that can be parsed at the moment of rendering only.
             * This content is pre-build in background by cron task.
             */
            $this->template = $this->getAuctionDetailsCacheLoader()
                ->loadValue($this->getAuctionId(), Constants\AuctionDetailsCache::CAPTION);
        }
        return $this->template;
    }
}
