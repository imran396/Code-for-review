<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Load\Query;

/**
 * Trait MySearchResultQueryBuilderCreateTrait
 * @package Sam\MySearch\Load\Query
 */
trait MySearchResultQueryBuilderCreateTrait
{
    protected ?MySearchResultQueryBuilder $mySearchResultQueryBuilder = null;

    /**
     * @return MySearchResultQueryBuilder
     */
    protected function createMySearchResultQueryBuilder(): MySearchResultQueryBuilder
    {
        return $this->mySearchResultQueryBuilder ?: MySearchResultQueryBuilder::new();
    }

    /**
     * @param MySearchResultQueryBuilder $mySearchResultQueryBuilder
     * @return static
     * @internal
     */
    public function setMySearchResultQueryBuilder(MySearchResultQueryBuilder $mySearchResultQueryBuilder): static
    {
        $this->mySearchResultQueryBuilder = $mySearchResultQueryBuilder;
        return $this;
    }
}
