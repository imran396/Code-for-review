<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/5/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb;

/**
 * Trait RtbGeneralHelper
 * @package Sam\Rtb
 */
trait RtbGeneralHelperAwareTrait
{
    /**
     * @var GeneralHelper|null
     */
    protected ?GeneralHelper $rtbGeneralHelper = null;

    /**
     * @return GeneralHelper
     */
    protected function getRtbGeneralHelper(): GeneralHelper
    {
        if ($this->rtbGeneralHelper === null) {
            $this->rtbGeneralHelper = GeneralHelper::new();
        }
        return $this->rtbGeneralHelper;
    }

    /**
     * @param GeneralHelper $rtbGeneralHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbGeneralHelper(GeneralHelper $rtbGeneralHelper): static
    {
        $this->rtbGeneralHelper = $rtbGeneralHelper;
        return $this;
    }
}
