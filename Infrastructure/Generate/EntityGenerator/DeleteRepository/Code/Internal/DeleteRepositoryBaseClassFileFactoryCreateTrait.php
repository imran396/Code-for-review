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

namespace Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code\Internal;

/**
 * Trait DeleteRepositoryBaseClassFileFactoryCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code\Internal
 */
trait DeleteRepositoryBaseClassFileFactoryCreateTrait
{
    protected ?DeleteRepositoryBaseClassFileFactory $deleteRepositoryBaseClassFileFactory = null;

    /**
     * @return DeleteRepositoryBaseClassFileFactory
     */
    protected function createDeleteRepositoryBaseClassFileFactory(): DeleteRepositoryBaseClassFileFactory
    {
        return $this->deleteRepositoryBaseClassFileFactory ?: DeleteRepositoryBaseClassFileFactory::new();
    }

    /**
     * @param DeleteRepositoryBaseClassFileFactory $deleteRepositoryBaseClassFileFactory
     * @return static
     * @internal
     */
    public function setDeleteRepositoryBaseClassFileFactory(DeleteRepositoryBaseClassFileFactory $deleteRepositoryBaseClassFileFactory): static
    {
        $this->deleteRepositoryBaseClassFileFactory = $deleteRepositoryBaseClassFileFactory;
        return $this;
    }
}
