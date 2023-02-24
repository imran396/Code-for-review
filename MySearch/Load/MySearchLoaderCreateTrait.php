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

namespace Sam\MySearch\Load;


/**
 * Trait MySearchLoaderCreateTrait
 * @package Sam\MySearch\Load
 */
trait MySearchLoaderCreateTrait
{
    /**
     * @var MySearchLoader|null
     */
    protected ?MySearchLoader $mySearchLoader = null;

    /**
     * @return MySearchLoader
     */
    protected function createMySearchLoader(): MySearchLoader
    {
        return $this->mySearchLoader ?: MySearchLoader::new();
    }

    /**
     * @param MySearchLoader $mySearchLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setMySearchLoader(MySearchLoader $mySearchLoader): static
    {
        $this->mySearchLoader = $mySearchLoader;
        return $this;
    }
}
