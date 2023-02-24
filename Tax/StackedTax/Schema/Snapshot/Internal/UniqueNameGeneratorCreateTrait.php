<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Snapshot\Internal;

/**
 * Trait UniqueNameGeneratorCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Snapshot\Internal
 */
trait UniqueNameGeneratorCreateTrait
{
    protected ?UniqueNameGenerator $uniqueNameGenerator = null;

    /**
     * @return UniqueNameGenerator
     */
    protected function createUniqueNameGenerator(): UniqueNameGenerator
    {
        return $this->uniqueNameGenerator ?: UniqueNameGenerator::new();
    }

    /**
     * @param UniqueNameGenerator $uniqueNameGenerator
     * @return static
     * @internal
     */
    public function setUniqueNameGenerator(UniqueNameGenerator $uniqueNameGenerator): static
    {
        $this->uniqueNameGenerator = $uniqueNameGenerator;
        return $this;
    }
}
