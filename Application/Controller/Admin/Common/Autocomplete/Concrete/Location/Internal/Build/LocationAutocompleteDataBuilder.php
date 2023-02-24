<?php
/**
 * SAM-10121: Separate location auto-completer end-points per controllers and fix filtering by entity-context account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build;

use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build\Internal\Load\DataLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LocationAutocompleteDataBuilder
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build
 */
class LocationAutocompleteDataBuilder extends CustomizableClass
{
    use DataLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data for location autocomplete filtered by search keyword and ordered by fulltext search score,
     * produce result lines according expected format.
     *
     * @param string $searchKeyword
     * @param int $filterAccountId
     * @param int $limit
     * @return array
     */
    public function build(string $searchKeyword, int $filterAccountId, int $limit): array
    {
        $rows = $this->createDataLoader()->load($searchKeyword, $filterAccountId, $limit, true);
        $results = array_map(
            static function (array $row) {
                return [
                    'value' => $row['id'],
                    'label' => $row['name']
                ];
            },
            $rows
        );
        return $results;
    }
}
