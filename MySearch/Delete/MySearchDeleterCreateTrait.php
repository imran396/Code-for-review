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

namespace Sam\MySearch\Delete;


/**
 * Trait MySearchDeleterCreateTrait
 * @package Sam\MySearch\Delete
 */
trait MySearchDeleterCreateTrait
{
    /**
     * @var MySearchDeleter|null
     */
    protected ?MySearchDeleter $mySearchDeleter = null;

    /**
     * @return MySearchDeleter
     */
    protected function createMySearchDeleter(): MySearchDeleter
    {
        return $this->mySearchDeleter ?: MySearchDeleter::new();
    }

    /**
     * @param MySearchDeleter $mySearchDeleter
     * @return static
     * @internal
     * @noinspection
     */
    public function setMySearchDeleter(MySearchDeleter $mySearchDeleter): static
    {
        $this->mySearchDeleter = $mySearchDeleter;
        return $this;
    }
}
