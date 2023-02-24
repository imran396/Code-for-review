<?php
/**
 * Auction Info page details output builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web\Info;

use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class Builder
 * @package Sam\Details
 */
class Builder extends \Sam\Details\Auction\Web\Builder
{
    use SettingsManagerAwareTrait;

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
        if ($this->template === null) {
            $this->template = (string)$this->getSettingsManager()->get(
                Constants\Setting::AUCTION_DETAIL_TEMPLATE,
                $this->getSystemAccountId()
            );
        }
        return $this->template;
    }
}
