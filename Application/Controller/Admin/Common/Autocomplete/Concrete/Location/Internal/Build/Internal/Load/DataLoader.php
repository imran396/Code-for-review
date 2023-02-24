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

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build\Internal\Load;

use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\QueryBuildingHelperCreateTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build\Internal\Load
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use QueryBuildingHelperCreateTrait;

    protected const SEARCH_FIELDS = [
        'name',
        'address',
        'country',
        'state',
        'city',
        'zip'
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare conditions and load location data.
     *
     * @param string $searchKeyword
     * @param int $filterAccountId
     * @param int $limit
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(string $searchKeyword, int $filterAccountId, int $limit, bool $isReadOnlyDb = false): array
    {
        $query = $this->makeQuery($searchKeyword, $filterAccountId, $limit);
        $this->query($query, $isReadOnlyDb);
        return $this->fetchAllAssoc();
    }

    /**
     * @param string $searchKeyword
     * @param int $filterAccountId
     * @param int $limit
     * @return string
     */
    protected function makeQuery(string $searchKeyword, int $filterAccountId, int $limit): string
    {
        $searchScoreExpression = $this->createQueryBuildingHelper()->makeFulltextSearchScoreExpression($searchKeyword, self::SEARCH_FIELDS);
        $sql = <<<SQL
SELECT id, name, {$searchScoreExpression}
FROM location
WHERE account_id = {$filterAccountId} 
    AND active = 1
HAVING score > 0
ORDER BY score DESC
LIMIT {$limit}
SQL;
        return $sql;
    }
}
