<?php
/**
 * SAM-4676: Encoding helper
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 24, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>*
 */

namespace Sam\Settings\Encoding;

/**
 * Trait EncodingHelperAwareTrait
 */
trait EncodingHelperAwareTrait
{
    /**
     * @var EncodingHelper|null
     */
    protected ?EncodingHelper $encodingHelper = null;

    /**
     * @return EncodingHelper
     */
    protected function getEncodingHelper(): EncodingHelper
    {
        if ($this->encodingHelper === null) {
            $this->encodingHelper = EncodingHelper::new();
        }
        return $this->encodingHelper;
    }

    /**
     * @param EncodingHelper $encodingHelper
     * @return static
     * @internal
     */
    public function setEncodingHelper(EncodingHelper $encodingHelper): static
    {
        $this->encodingHelper = $encodingHelper;
        return $this;
    }
}
