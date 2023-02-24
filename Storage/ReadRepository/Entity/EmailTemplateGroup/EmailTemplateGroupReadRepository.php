<?php
/**
 * General repository for EmailTemplateGroupReadRepository Parameters entity
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
 * $emailTemplateGroupRepository = \Sam\Storage\ReadRepository\Entity\EmailTemplateGroup\EmailTemplateGroupReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($auctionIds);
 * $isFound = $emailTemplateGroupRepository->exist();
 * $count = $emailTemplateGroupRepository->count();
 * $item = $emailTemplateGroupRepository->loadEntity();
 * $items = $emailTemplateGroupRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\EmailTemplateGroup;

/**
 * Class EmailTemplateGroupReadRepository
 */
class EmailTemplateGroupReadRepository extends AbstractEmailTemplateGroupReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
