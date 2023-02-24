<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Create;

/**
 * Trait EntityCreatorCreateTrait
 * @package Sam\Core\Entity\Create
 */
trait EntityFactoryCreateTrait
{
    /**
     * @var EntityFactory|null
     */
    protected ?EntityFactory $entityFactory = null;

    /**
     * @return EntityFactory
     */
    protected function createEntityFactory(): EntityFactory
    {
        return $this->entityFactory ?: EntityFactory::new();
    }

    /**
     * @param EntityFactory $entityFactory
     * @return $this
     * @internal
     */
    public function setEntityFactory(EntityFactory $entityFactory): static
    {
        $this->entityFactory = $entityFactory;
        return $this;
    }
}
