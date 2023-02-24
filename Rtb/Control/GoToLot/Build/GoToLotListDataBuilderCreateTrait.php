<?php
/**
 * SAM-6433: Refactor logic for Go to lot list of rtb clerk console
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\GoToLot\Build;

/**
 * Trait GoToLotListDataBuilderCreateTrait
 * @package Sam\Rtb\Control\GoToLot\Build
 */
trait GoToLotListDataBuilderCreateTrait
{
    /**
     * @var GoToLotListDataBuilder|null
     */
    protected ?GoToLotListDataBuilder $goToLotListDataBuilder = null;

    /**
     * @return GoToLotListDataBuilder
     */
    protected function createGoToLotListDataBuilder(): GoToLotListDataBuilder
    {
        return $this->goToLotListDataBuilder ?: GoToLotListDataBuilder::new();
    }

    /**
     * @param GoToLotListDataBuilder $goToLotListDataBuilder
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setGoToLotListDataBuilder(GoToLotListDataBuilder $goToLotListDataBuilder): static
    {
        $this->goToLotListDataBuilder = $goToLotListDataBuilder;
        return $this;
    }
}
