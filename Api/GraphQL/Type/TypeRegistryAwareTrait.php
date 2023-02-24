<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type;

use RuntimeException;

/**
 * Default realisation for TypeRegistryAwareInterface
 *
 * Trait TypeRegistryAwareTrait
 * @package Sam\Api\GraphQL
 */
trait TypeRegistryAwareTrait
{
    protected ?TypeRegistry $typeRegistry = null;

    public function getTypeRegistry(): TypeRegistry
    {
        if (!$this->typeRegistry) {
            throw new RuntimeException('TypeRegistry is not set');
        }
        return $this->typeRegistry;
    }

    public function setTypeRegistry(TypeRegistry $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
}
