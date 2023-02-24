<?php
/**
 * SAM-3676: Repository approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Sql;

/**
 * Trait QueryBuilderAwareTrait
 * @package Sam\Storage\Sql
 */
trait QueryBuilderAwareTrait
{
    protected ?QueryBuilder $queryBuilder = null;

    /**
     * @return QueryBuilder
     */
    public function qb(): QueryBuilder
    {
        return $this->getQueryBuilder();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = QueryBuilder::new();
        }
        return $this->queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return static
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder): static
    {
        $this->queryBuilder = $queryBuilder;
        return $this;
    }
}
