<?php
/**
 * Store and prepare options for lot seo url details data provider
 * But values from Options could be used in other parts of code, eg. Options->languageId
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Build\Internal\Option;

use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * @property int[]|null $auctionLotIds
 * @property int $languageId - for translations
 */
class Options extends \Sam\Details\Core\Options
{
    use SettingsManagerAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $serviceAccountId): static
    {
        $this->setSystemAccountId($serviceAccountId);
        $this->auctionLotIds = null;
        $this->languageId = (int)$this->getSettingsManager()
            ->get(Constants\Setting::VIEW_LANGUAGE, $serviceAccountId);
        return $this;
    }
}
