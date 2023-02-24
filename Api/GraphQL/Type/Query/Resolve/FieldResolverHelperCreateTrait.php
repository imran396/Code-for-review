<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve;

/**
 * Trait FieldResolverHelperCreateTrait
 * @package Sam\Api\GraphQL\Type\Query\Resolve
 */
trait FieldResolverHelperCreateTrait
{
    protected ?FieldResolverHelper $fieldResolverHelper = null;

    /**
     * @return FieldResolverHelper
     */
    protected function createFieldResolverHelper(): FieldResolverHelper
    {
        return $this->fieldResolverHelper ?: FieldResolverHelper::new();
    }

    /**
     * @param FieldResolverHelper $fieldResolverHelper
     * @return static
     * @internal
     */
    public function setFieldResolverHelper(FieldResolverHelper $fieldResolverHelper): static
    {
        $this->fieldResolverHelper = $fieldResolverHelper;
        return $this;
    }
}
