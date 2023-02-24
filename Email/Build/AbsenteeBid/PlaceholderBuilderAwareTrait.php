<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 13, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email\Build\AbsenteeBid;


/**
 * Trait PlaceholderBuilderAwareTrait
 * @package Sam\Email\Build\AbsenteeBid
 */
trait PlaceholderBuilderAwareTrait
{

    /**
     * @var PlaceholderBuilder|null
     */
    protected ?PlaceholderBuilder $placeholderBuilder = null;

    /**
     * @return PlaceholderBuilder
     */
    protected function getPlaceholderBuilder(): PlaceholderBuilder
    {
        if ($this->placeholderBuilder === null) {
            $this->placeholderBuilder = PlaceholderBuilder::new();
        }
        return $this->placeholderBuilder;
    }

    /**
     * @param PlaceholderBuilder $placeholderBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setPlaceholderBuilder(PlaceholderBuilder $placeholderBuilder): static
    {
        $this->placeholderBuilder = $placeholderBuilder;
        return $this;
    }
}
