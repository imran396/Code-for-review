<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code;

/**
 * Trait WriteRepositoryCodeGeneratorCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code
 */
trait WriteRepositoryCodeGeneratorCreateTrait
{
    /**
     * @var WriteRepositoryCodeGenerator|null
     */
    protected ?WriteRepositoryCodeGenerator $writeRepositoryCodeGenerator = null;

    /**
     * @return WriteRepositoryCodeGenerator
     */
    protected function createWriteRepositoryCodeGenerator(): WriteRepositoryCodeGenerator
    {
        return $this->writeRepositoryCodeGenerator ?: WriteRepositoryCodeGenerator::new();
    }

    /**
     * @param WriteRepositoryCodeGenerator $writeRepositoryCodeGenerator
     * @return static
     * @internal
     */
    public function setWriteRepositoryCodeGenerator(WriteRepositoryCodeGenerator $writeRepositoryCodeGenerator): static
    {
        $this->writeRepositoryCodeGenerator = $writeRepositoryCodeGenerator;
        return $this;
    }
}
