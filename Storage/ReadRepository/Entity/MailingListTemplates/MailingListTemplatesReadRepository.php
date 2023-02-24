<?php
/**
 * General repository for MailingListTemplatesReadRepository Parameters entity
 *
 * SAM-3682: Reports related repositories https://bidpath.atlassian.net/browse/SAM-3682
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           07 Apr, 2017
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
 * $mailingListTemplatesRepository = \Sam\Storage\ReadRepository\Entity\MailingListTemplates\MailingListTemplatesReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($auctionIds);
 * $isFound = $mailingListTemplatesRepository->exist();
 * $count = $mailingListTemplatesRepository->count();
 * $item = $mailingListTemplatesRepository->loadEntity();
 * $items = $mailingListTemplatesRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\MailingListTemplates;

/**
 * Class MailingListTemplatesReadRepository
 */
class MailingListTemplatesReadRepository extends AbstractMailingListTemplatesReadRepository
{
    protected array $joins = [
        'auction' => 'JOIN `auction` a ON mlt.auction_id = a.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Left join auction table
     * Define ORDER BY a.name
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionOrderByName(bool $ascending = true): static
    {
        $this->joinAuction();
        $this->order('a.`name`', $ascending);
        return $this;
    }

    /**
     * Left join auction table
     * Define ORDER BY a.sale_num
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionOrderBySaleNo(bool $ascending = true): static
    {
        $this->joinAuction();
        $this->order('a.sale_num', $ascending);
        $this->order('a.sale_num_ext', $ascending);
        return $this;
    }
}
