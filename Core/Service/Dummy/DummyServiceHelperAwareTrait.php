<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service\Dummy;

/**
 * Trait DummyServiceHelperAwareTrait
 * @package \Sam\Core\Service\Dummy
 */
trait DummyServiceHelperAwareTrait
{
    /**
     * @var DummyServiceHelper|null
     */
    protected ?DummyServiceHelper $dummyServiceHelper = null;

    /**
     * @return DummyServiceHelper
     */
    protected function getDummyServiceHelper(): DummyServiceHelper
    {
        if ($this->dummyServiceHelper === null) {
            $this->dummyServiceHelper = DummyServiceHelper::new();
        }
        return $this->dummyServiceHelper;
    }

    /**
     * @param DummyServiceHelper $dummyServiceHelper
     * @return $this
     * @internal
     */
    public function setDummyServiceHelper(DummyServiceHelper $dummyServiceHelper): static
    {
        $this->dummyServiceHelper = $dummyServiceHelper;
        return $this;
    }
}
