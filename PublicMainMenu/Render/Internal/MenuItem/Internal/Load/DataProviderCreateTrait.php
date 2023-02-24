<?php
/**
 * SAM-6767: Responsive Main Menu rendering module adjustments for v3.5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Render\Internal\MenuItem\Internal\Load;

/**
 * Trait DataProviderCreateTrait
 * @package Sam\PublicMainMenu\Render\Internal\MenuItem\Internal\Load
 */
trait DataProviderCreateTrait
{
    protected ?DataProvider $dataProvider = null;

    /**
     * @return DataProvider
     */
    protected function createDataProvider(): DataProvider
    {
        return $this->dataProvider ?: DataProvider::new();
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
