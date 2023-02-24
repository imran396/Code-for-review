<?php
/**
 * SAM-9891: Get rid of RepositoryBase::delete() usage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile;

/**
 * Trait CustomizableClassFileFactoryCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile
 */
trait CustomizableClassFileFactoryCreateTrait
{
    /**
     * @var CustomizableClassFileFactory|null
     */
    protected ?CustomizableClassFileFactory $customizableClassFileFactory = null;

    /**
     * @return CustomizableClassFileFactory
     */
    protected function createCustomizableClassFileFactory(): CustomizableClassFileFactory
    {
        return $this->customizableClassFileFactory ?: CustomizableClassFileFactory::new();
    }

    /**
     * @param CustomizableClassFileFactory $customizableClassFileFactory
     * @return static
     * @internal
     */
    public function setCustomizableClassFileFactory(CustomizableClassFileFactory $customizableClassFileFactory): static
    {
        $this->customizableClassFileFactory = $customizableClassFileFactory;
        return $this;
    }
}
