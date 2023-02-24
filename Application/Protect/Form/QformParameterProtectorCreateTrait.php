<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Form;

/**
 * Trait QformParameterProtectorCreateTrait
 * @package
 */
trait QformParameterProtectorCreateTrait
{
    /**
     * @var QformParameterProtector|null
     */
    protected ?QformParameterProtector $qformParameterProtector = null;

    /**
     * @return QformParameterProtector
     */
    protected function createQformParameterProtector(): QformParameterProtector
    {
        return $this->qformParameterProtector ?: QformParameterProtector::new();
    }

    /**
     * @param QformParameterProtector $qformParameterProtector
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setQformParameterProtector(QformParameterProtector $qformParameterProtector): static
    {
        $this->qformParameterProtector = $qformParameterProtector;
        return $this;
    }
}
