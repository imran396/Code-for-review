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

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal\TraitGenerator;

/**
 * Trait DependencyInjectionTraitFileGeneratorCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\Internal\TraitGenerator
 */
trait DependencyInjectionTraitFileGeneratorCreateTrait
{
    protected ?DependencyInjectionTraitFileGenerator $dependencyInjectionTraitFileGenerator = null;

    /**
     * @return DependencyInjectionTraitFileGenerator
     */
    protected function createDependencyInjectionTraitFileGenerator(): DependencyInjectionTraitFileGenerator
    {
        return $this->dependencyInjectionTraitFileGenerator ?: DependencyInjectionTraitFileGenerator::new();
    }

    /**
     * @param DependencyInjectionTraitFileGenerator $dependencyInjectionTraitFileGenerator
     * @return static
     * @internal
     */
    public function setDependencyInjectionTraitFileGenerator(DependencyInjectionTraitFileGenerator $dependencyInjectionTraitFileGenerator): static
    {
        $this->dependencyInjectionTraitFileGenerator = $dependencyInjectionTraitFileGenerator;
        return $this;
    }
}
