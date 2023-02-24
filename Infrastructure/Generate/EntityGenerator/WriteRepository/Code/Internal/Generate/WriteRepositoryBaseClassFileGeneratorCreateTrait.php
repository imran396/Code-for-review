<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal\Generate;

/**
 * Trait WriteRepositoryBaseClassFileGeneratorCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal\Generate
 * @internal
 */
trait WriteRepositoryBaseClassFileGeneratorCreateTrait
{
    protected ?WriteRepositoryBaseClassFileGenerator $writeRepositoryBaseClassFileGenerator = null;

    /**
     * @return WriteRepositoryBaseClassFileGenerator
     */
    protected function createWriteRepositoryBaseClassFileGenerator(): WriteRepositoryBaseClassFileGenerator
    {
        return $this->writeRepositoryBaseClassFileGenerator ?: WriteRepositoryBaseClassFileGenerator::new();
    }

    /**
     * @param WriteRepositoryBaseClassFileGenerator $writeRepositoryBaseClassFileGenerator
     * @return static
     * @internal
     */
    public function setWriteRepositoryBaseClassFileGenerator(WriteRepositoryBaseClassFileGenerator $writeRepositoryBaseClassFileGenerator): static
    {
        $this->writeRepositoryBaseClassFileGenerator = $writeRepositoryBaseClassFileGenerator;
        return $this;
    }
}
