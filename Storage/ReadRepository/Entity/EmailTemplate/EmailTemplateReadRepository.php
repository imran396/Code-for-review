<?php
/**
 * General repository for EmailTemplateReadRepository Parameters entity
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
 * $emailTemplateRepository = \Sam\Storage\ReadRepository\Entity\EmailTemplate\EmailTemplateReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($auctionIds);
 * $isFound = $emailTemplateRepository->exist();
 * $count = $emailTemplateRepository->count();
 * $item = $emailTemplateRepository->loadEntity();
 * $items = $emailTemplateRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\EmailTemplate;

/**
 * Class EmailTemplateReadRepository
 */
class EmailTemplateReadRepository extends AbstractEmailTemplateReadRepository
{
    protected array $joins = [
        'email_template_group' => 'JOIN email_template_group etg ON etg.id = et.email_template_group_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join email_template_group table
     * Define ORDER BY etg.order
     * @param bool $ascending
     * @return static
     */
    public function joinEmailTemplateGroupOrderByOrder(bool $ascending = true): static
    {
        $this->join('email_template_group');
        $this->order('etg.`order`', $ascending);
        return $this;
    }
}
