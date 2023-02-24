<?php
/**
 * Store and prepare options for auction details data provider in general
 * But values from Options could be used in other parts of code, eg. Options->languageId
 * See available properties below and in child classes.
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

namespace Sam\Details\Auction\Web;

use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * @property int|null $auctionId - filtering by auction id
 * @property int|null $languageId - for translations
 * @property int|null $userId
 */
class Options extends \Sam\Details\Core\Options
{
    use SettingsManagerAwareTrait;
    use ParamFetcherForGetAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(int $systemAccountId): static
    {
        $this->setSystemAccountId($systemAccountId);
        $this->auctionId = null;
        $this->languageId = null;
        $this->userId = null;
        return $this;
    }

    /**
     * Produce option values based on GET request params
     */
    public function initByRequest(): static
    {
        $this->languageId = $this->getParamFetcherForGet()->getIntPositive('language')
            ?: (int)$this->getSettingsManager()->get(Constants\Setting::VIEW_LANGUAGE, $this->getSystemAccountId());
        return $this;
    }
}
