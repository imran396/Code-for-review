<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\Internal;

/**
 * Trait FieldDataMapperCreateTrait
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Internal
 */
trait FieldDataMapperCreateTrait
{
    protected ?FieldDataMapper $fieldDataMapper = null;

    /**
     * @return FieldDataMapper
     */
    protected function createFieldDataMapper(): FieldDataMapper
    {
        return $this->fieldDataMapper ?: FieldDataMapper::new();
    }

    /**
     * @param FieldDataMapper $fieldDataMapper
     * @return static
     * @internal
     */
    public function setFieldDataMapper(FieldDataMapper $fieldDataMapper): static
    {
        $this->fieldDataMapper = $fieldDataMapper;
        return $this;
    }
}
