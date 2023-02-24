<?php
/**
 * SAM-6902: Decompose services from \Sam\Qform\QformHelpersAwareTrait to a separate independant <Servise>AwareTrait
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform;


/**
 * Trait QformHelperAwareTrait
 * @package Sam\Qform
 */
trait QformHelperAwareTrait
{
    protected ?QformHelper $qformHelper = null;

    /**
     * @return QformHelper
     */
    protected function getQformHelper(): QformHelper
    {
        if ($this->qformHelper === null) {
            $this->qformHelper = QformHelper::new();
        }
        return $this->qformHelper;
    }

    /**
     * @param QformHelper $qformHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setQformHelper(QformHelper $qformHelper): static
    {
        $this->qformHelper = $qformHelper;
        return $this;
    }
}
