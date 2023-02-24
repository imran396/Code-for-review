<?php
/**
 *
 * SAM-4628: Refactor audit trail report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-03
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\AuditTrail;

use Generator;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\AuditTrail
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SystemAccountAwareTrait;

    protected const CHUNK_SIZE = 200;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return Generator
     */
    public function yieldData(): Generator
    {
        $chunkNum = 0;
        $query = $this->makeQuery();

        do {
            $data = $this->fetchData($query, self::CHUNK_SIZE, $chunkNum * self::CHUNK_SIZE);
            yield from $data;
            $chunkNum++;
        } while ($data);
    }

    /**
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function fetchData(string $query, int $limit, int $offset): array
    {
        $limitExpression = "LIMIT {$limit} OFFSET $offset";
        $this->query($query . ' ' . $limitExpression);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return string
     */
    protected function makeQuery(): string
    {
        $endDateUtcIso = $this->getFilterEndDateUtcIso();
        $filterAccountId = $this->getFilterAccountId();
        $startDateUtcIso = $this->getFilterStartDateUtcIso();
        $systemAccountId = $this->getSystemAccountId();
        $userAccountCond = $systemAccountId ? 'AND u1.account_id = ' . $this->escape($systemAccountId) . ' ' : '';
        $accountCond = $filterAccountId ? 'AND at.account_id = ' . $this->escape($filterAccountId) . ' ' : '';
        $query = <<<SQL
SELECT at.*, u1.username, u2.username AS puname FROM audit_trail at
INNER JOIN user u1 ON at.user_id = u1.id {$userAccountCond}
LEFT JOIN user u2 ON at.proxy_user_id = u2.id
WHERE `timestamp` > {$this->escape($startDateUtcIso)}
  AND `timestamp` < {$this->escape($endDateUtcIso)}
  {$accountCond}
SQL;
        return $query;
    }
}
