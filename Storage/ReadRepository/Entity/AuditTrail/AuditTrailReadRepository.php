<?php
/**
 * General repository for AuditTrail entity
 *
 * SAM-3722 : Statistics related repositories https://bidpath.atlassian.net/browse/SAM-3722
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           3 July, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of accounts filtered by criteria
 * $auditTrailRepository = \Sam\Storage\ReadRepository\Entity\AuditTrail\AuditTrailReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $auditTrailRepository->exist();
 * $count = $auditTrailRepository->count();
 * $auditTrails = $auditTrailRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $auditTrailRepository = \Sam\Storage\ReadRepository\Entity\AuditTrail\AuditTrailReadRepository::new()
 *     ->filterId(1);
 * $auditTrail = $auditTrailRepository->loadEntity();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\AuditTrail;

/**
 * Class AuditTrailReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuditTrail
 */
class AuditTrailReadRepository extends AbstractAuditTrailReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
