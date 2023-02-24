<?php
/**
 * Trait for EntityMakerImpureChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/2/2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate;

/**
 * Trait EntityMakerImpureCheckerCreateTrait
 * @package Sam\EntityMaker\Base\Validate
 */
trait EntityMakerImpureCheckerCreateTrait
{
    /**
     * @var EntityMakerImpureChecker|null
     */
    protected ?EntityMakerImpureChecker $entityMakerImpureChecker = null;

    /**
     * @return EntityMakerImpureChecker
     */
    protected function createEntityMakerImpureChecker(): EntityMakerImpureChecker
    {
        return $this->entityMakerImpureChecker ?: EntityMakerImpureChecker::new();
    }

    /**
     * @param EntityMakerImpureChecker $entityMakerImpureChecker
     * @return static
     * @internal
     */
    public function setEntityMakerImpureChecker(EntityMakerImpureChecker $entityMakerImpureChecker): static
    {
        $this->entityMakerImpureChecker = $entityMakerImpureChecker;
        return $this;
    }
}
