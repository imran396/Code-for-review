<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch;


/**
 * Trait MySearchLinkBuilderCreateTrait
 * @package Sam\MySearch
 */
trait MySearchLinkBuilderCreateTrait
{
    /**
     * @var MySearchLinkBuilder|null
     */
    protected ?MySearchLinkBuilder $mySearchLinkBuilder = null;

    /**
     * @return MySearchLinkBuilder
     */
    protected function createMySearchLinkBuilder(): MySearchLinkBuilder
    {
        return $this->mySearchLinkBuilder ?: MySearchLinkBuilder::new();
    }

    /**
     * @param MySearchLinkBuilder $mySearchLinkBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setMySearchLinkBuilder(MySearchLinkBuilder $mySearchLinkBuilder): static
    {
        $this->mySearchLinkBuilder = $mySearchLinkBuilder;
        return $this;
    }
}
