<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query;

/**
 * Trait QueryBuildingHelperCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query
 */
trait QueryBuildingHelperCreateTrait
{
    /**
     * @var QueryBuildingHelper|null
     */
    protected ?QueryBuildingHelper $queryBuildingHelper = null;

    /**
     * @return QueryBuildingHelper
     */
    protected function createQueryBuildingHelper(): QueryBuildingHelper
    {
        return $this->queryBuildingHelper ?: QueryBuildingHelper::new();
    }

    /**
     * @param QueryBuildingHelper $queryBuildingHelper
     * @return $this
     * @internal
     */
    public function setQueryBuildingHelper(QueryBuildingHelper $queryBuildingHelper): static
    {
        $this->queryBuildingHelper = $queryBuildingHelper;
        return $this;
    }
}
