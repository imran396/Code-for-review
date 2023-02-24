<?php
/**
 * General repository for AuctionEmailTemplate Parameters entity
 *
 * SAM-3681: Email template related repositories https://bidpath.atlassian.net/browse/SAM-3681
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           06 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionEmailTemplateRepository = \Sam\Storage\ReadRepository\Entity\AuctionEmailTemplate\AuctionEmailTemplateReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($auctionIds);
 * $isFound = $auctionEmailTemplateRepository->exist();
 * $count = $auctionEmailTemplateRepository->count();
 * $item = $auctionEmailTemplateRepository->loadEntity();
 * $items = $auctionEmailTemplateRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionEmailTemplate;

/**
 * Class AuctionEmailTemplateReadRepository
 */
class AuctionEmailTemplateReadRepository extends AbstractAuctionEmailTemplateReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
