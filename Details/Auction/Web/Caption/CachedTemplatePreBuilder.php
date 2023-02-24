<?php
/**
 * Pre-builds AuctionCache caption content, where we replace all observable placeholders, but keep untouched non-observable ones.
 * Non-observable placeholders should be parsed on-the-fly right before rendering.
 *
 * SAM-4304 : Editable template for auction pages header
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

use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 */
class CachedTemplatePreBuilder extends \Sam\Details\Auction\Web\Builder
{
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;

    /**
     * Enable pre-building mode to avoid parsing of non-observable placeholders
     */
    protected bool $isPreBuilding = true;

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
            $auctionCaption = $this->getTermsAndConditionsManager()->load(
                $this->getSystemAccountId(),
                Constants\TermsAndConditions::AUCTION_CAPTION,
                true
            );
            $this->template = $auctionCaption->Content ?? '';
        }
        return $this->template;
    }
}
