<?php
/**
 * SAM-6489: Rtb console control rendering at server side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\Render;

/**
 * Trait RtbControlCollectionAwareTrait
 * @package Sam\Rtb\Control\Render
 */
trait RtbControlCollectionAwareTrait
{
    /**
     * @var RtbControlCollection|null
     */
    protected ?RtbControlCollection $rtbControlCollection = null;

    /**
     * @return RtbControlCollection
     */
    protected function getRtbControlCollection(): RtbControlCollection
    {
        if ($this->rtbControlCollection === null) {
            $this->rtbControlCollection = RtbControlCollection::new();
        }
        return $this->rtbControlCollection;
    }

    /**
     * @param RtbControlCollection $rtbControlCollection
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbControlCollection(RtbControlCollection $rtbControlCollection): static
    {
        $this->rtbControlCollection = $rtbControlCollection;
        return $this;
    }
}
