<?php
/**
 * SAM-9875: Implement a code generator for read repository classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal;

/**
 * Trait ReadRepositoryBaseClassFileGeneratorCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal
 */
trait ReadRepositoryBaseClassFileGeneratorCreateTrait
{
    /**
     * @var ReadRepositoryBaseClassFileGenerator|null
     */
    protected ?ReadRepositoryBaseClassFileGenerator $readRepositoryBaseClassFileGenerator = null;

    /**
     * @return ReadRepositoryBaseClassFileGenerator
     */
    protected function createReadRepositoryBaseClassFileGenerator(): ReadRepositoryBaseClassFileGenerator
    {
        return $this->readRepositoryBaseClassFileGenerator ?: ReadRepositoryBaseClassFileGenerator::new();
    }

    /**
     * @param ReadRepositoryBaseClassFileGenerator $readRepositoryBaseClassFileGenerator
     * @return static
     * @internal
     */
    public function setReadRepositoryBaseClassFileGenerator(ReadRepositoryBaseClassFileGenerator $readRepositoryBaseClassFileGenerator): static
    {
        $this->readRepositoryBaseClassFileGenerator = $readRepositoryBaseClassFileGenerator;
        return $this;
    }
}
