<?php
/**
 * General repository for AdditionalSignupConfirmation entity
 *
 * SAM-3736 : Additional Signup Confirmation repository and manager https://bidpath.atlassian.net/browse/SAM-3736
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           07 may, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of AdditionalSignupConfirmation filtered by criteria
 * $additionalSignupConfirmationRepository = \Sam\Storage\ReadRepository\Entity\AdditionalSignupConfirmation\AdditionalSignupConfirmationReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $additionalSignupConfirmationRepository->exist();
 * $count = $additionalSignupConfirmationRepository->count();
 * $additionalSignupConfirmations = $additionalSignupConfirmationRepository->loadEntities();
 *
 * // Sample2. Load single AdditionalSignupConfirmation
 * $additionalSignupConfirmationRepository = \Sam\Storage\ReadRepository\Entity\AdditionalSignupConfirmation\AdditionalSignupConfirmationReadRepository::new()
 *     ->filterId(1);
 * $additionalSignupConfirmation = $additionalSignupConfirmationRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\AdditionalSignupConfirmation;

/**
 * Class AdditionalSignupConfirmationReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AdditionalSignupConfirmation
 */
class AdditionalSignupConfirmationReadRepository extends AbstractAdditionalSignupConfirmationReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
