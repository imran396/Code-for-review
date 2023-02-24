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

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code;

/**
 * Trait ReadRepositoryCodeGeneratorCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code
 */
trait ReadRepositoryCodeGeneratorCreateTrait
{
    /**
     * @var ReadRepositoryCodeGenerator|null
     */
    protected ?ReadRepositoryCodeGenerator $readRepositoryCodeGenerator = null;

    /**
     * @return ReadRepositoryCodeGenerator
     */
    protected function createReadRepositoryCodeGenerator(): ReadRepositoryCodeGenerator
    {
        return $this->readRepositoryCodeGenerator ?: ReadRepositoryCodeGenerator::new();
    }

    /**
     * @param ReadRepositoryCodeGenerator $readRepositoryCodeGenerator
     * @return static
     * @internal
     */
    public function setReadRepositoryCodeGenerator(ReadRepositoryCodeGenerator $readRepositoryCodeGenerator): static
    {
        $this->readRepositoryCodeGenerator = $readRepositoryCodeGenerator;
        return $this;
    }
}
