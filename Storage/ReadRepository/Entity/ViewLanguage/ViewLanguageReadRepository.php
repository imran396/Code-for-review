<?php
/**
 * General repository for ViewLanguage entity
 *
 * SAM-3727 Application settings/workflow related repositories https://bidpath.atlassian.net/browse/SAM-3727
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           29 June, 2017
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
 * $viewLanguageRepository = \Sam\Storage\ReadRepository\Entity\ViewLanguage\ViewLanguageReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $viewLanguageRepository->exist();
 * $count = $viewLanguageRepository->count();
 * $viewLanguages = $viewLanguageRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $viewLanguageRepository = \Sam\Storage\ReadRepository\Entity\ViewLanguage\ViewLanguageReadRepository::new()
 *     ->filterId(1);
 * $viewLanguage = $viewLanguageRepository->loadEntity();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\ViewLanguage;

/**
 * Class ViewLanguageReadRepository
 * @package Sam\Storage\ReadRepository\Entity\ViewLanguage
 */
class ViewLanguageReadRepository extends AbstractViewLanguageReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
