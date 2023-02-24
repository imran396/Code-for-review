<?php
/**
 * SAM-9486: Entity factory class generator
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

namespace Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code;

/**
 * Trait EntityFactoryCodeGeneratorCreateTrait
 * @package
 */
trait EntityFactoryCodeGeneratorCreateTrait
{
    /**
     * @var EntityFactoryCodeGenerator|null
     */
    protected ?EntityFactoryCodeGenerator $entityFactoryCodeGenerator = null;

    /**
     * @return EntityFactoryCodeGenerator
     */
    protected function createEntityFactoryCodeGenerator(): EntityFactoryCodeGenerator
    {
        return $this->entityFactoryCodeGenerator ?: EntityFactoryCodeGenerator::new();
    }

    /**
     * @param EntityFactoryCodeGenerator $entityFactoryCodeGenerator
     * @return $this
     * @internal
     */
    public function setEntityFactoryCodeGenerator(EntityFactoryCodeGenerator $entityFactoryCodeGenerator): static
    {
        $this->entityFactoryCodeGenerator = $entityFactoryCodeGenerator;
        return $this;
    }
}
