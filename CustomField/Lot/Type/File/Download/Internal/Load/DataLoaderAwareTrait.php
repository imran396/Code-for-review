<?php
/**
 * SAM-5721: Refactor lot custom field file download for web
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Download\Internal\Load;

/**
 * Trait DataLoaderAwareTrait
 * @package Sam\CustomField\Lot\Type\File\Download
 * @internal
 */
trait DataLoaderAwareTrait
{
    /**
     * @var DataLoader|null
     */
    protected ?DataLoader $dataLoader = null;

    /**
     * @return DataLoader
     */
    protected function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new();
        }
        return $this->dataLoader;
    }

    /**
     * @param DataLoader $dataLoader
     * @return $this
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
