<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/06/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Collection;

use LogicException;

/**
 * Protected internal accessors only
 * @package Sam\Installation\Config
 */
trait DescriptorCollectionAwareTrait
{
    /**
     * @var DescriptorCollection|null
     */
    protected ?DescriptorCollection $descriptorCollection = null;

    /**
     * @return DescriptorCollection
     */
    protected function getDescriptorCollection(): DescriptorCollection
    {
        if ($this->descriptorCollection === null) {
            throw new LogicException('DescriptorCollection not defined');
        }
        return $this->descriptorCollection;
    }

    /**
     * @param DescriptorCollection $descriptorCollection
     * @return static
     */
    protected function setDescriptorCollection(DescriptorCollection $descriptorCollection): static
    {
        $this->descriptorCollection = $descriptorCollection;
        return $this;
    }
}
