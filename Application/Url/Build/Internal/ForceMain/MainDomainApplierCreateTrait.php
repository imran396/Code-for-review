<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\ForceMain;

/**
 * Trait MainDomainApplierCreateTrait
 * @package Sam\Application\Url\Build\Internal\ForceMain
 */
trait MainDomainApplierCreateTrait
{
    /**
     * @var MainDomainApplier|null
     */
    protected ?MainDomainApplier $mainDomainApplier = null;

    /**
     * @return MainDomainApplier
     */
    protected function createMainDomainApplier(): MainDomainApplier
    {
        return $this->mainDomainApplier ?: MainDomainApplier::new();
    }

    /**
     * @param MainDomainApplier $mainDomainApplier
     * @return $this
     * @internal
     */
    public function setMainDomainApplier(MainDomainApplier $mainDomainApplier): static
    {
        $this->mainDomainApplier = $mainDomainApplier;
        return $this;
    }
}
