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

namespace Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code;

/**
 * Trait DeleteRepositoryCodeGeneratorCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code
 */
trait DeleteRepositoryCodeGeneratorCreateTrait
{
    /**
     * @var DeleteRepositoryCodeGenerator|null
     */
    protected ?DeleteRepositoryCodeGenerator $deleteRepositoryCodeGenerator = null;

    /**
     * @return DeleteRepositoryCodeGenerator
     */
    protected function createDeleteRepositoryCodeGenerator(): DeleteRepositoryCodeGenerator
    {
        return $this->deleteRepositoryCodeGenerator ?: DeleteRepositoryCodeGenerator::new();
    }

    /**
     * @param DeleteRepositoryCodeGenerator $deleteRepositoryCodeGenerator
     * @return static
     * @internal
     */
    public function setDeleteRepositoryCodeGenerator(DeleteRepositoryCodeGenerator $deleteRepositoryCodeGenerator): static
    {
        $this->deleteRepositoryCodeGenerator = $deleteRepositoryCodeGenerator;
        return $this;
    }
}
