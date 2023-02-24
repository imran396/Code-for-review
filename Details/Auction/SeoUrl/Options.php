<?php
/**
 * Store and prepare options for auction seo url details data provider
 * But values from Options could be used in other parts of code, eg. Options->languageId
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 3, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl;

use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * @property int[]|null $auctionIds
 * @property int $languageId - for translations
 */
class Options extends \Sam\Details\Core\Options
{
    use SettingsManagerAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $systemAccountId): static
    {
        $this->setSystemAccountId($systemAccountId);
        $this->auctionIds = null;
        $this->languageId = (int)$this->getSettingsManager()->get(Constants\Setting::VIEW_LANGUAGE, $this->getSystemAccountId());
        return $this;
    }
}
