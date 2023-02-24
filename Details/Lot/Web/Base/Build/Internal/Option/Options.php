<?php
/**
 * Store and prepare options for lot details data provider for public web (Lot Info) page
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
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

namespace Sam\Details\Lot\Web\Base\Build\Internal\Option;

/**
 * @property int|null $auctionId
 * @property int|null $lotItemId - filtering by li.id
 * @property int|null $userId     - filtering by user id
 */
class Options extends \Sam\Details\Core\Options
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $systemAccountId): static
    {
        $this->setSystemAccountId($systemAccountId);
        $this->auctionId = null;
        $this->lotItemId = null;
        $this->userId = null;
        return $this;
    }
}
