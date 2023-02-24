<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform;

/**
 * Trait PublicErrorCollectionAwareTrait
 * @package Sam\Qform
 */
trait PublicErrorCollectionAwareTrait
{
    protected ?PublicErrorCollection $publicErrorCollection = null;

    /**
     * @return PublicErrorCollection
     */
    public function getPublicErrorCollection(): PublicErrorCollection
    {
        if ($this->publicErrorCollection === null) {
            $this->publicErrorCollection = PublicErrorCollection::new();
        }
        return $this->publicErrorCollection;
    }

    /**
     * @param PublicErrorCollection $publicErrorCollection
     * @return static
     * @internal
     */
    public function setPublicErrorCollection(PublicErrorCollection $publicErrorCollection): static
    {
        $this->publicErrorCollection = $publicErrorCollection;
        return $this;
    }
}
