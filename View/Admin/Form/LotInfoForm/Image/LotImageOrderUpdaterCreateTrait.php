<?php
/**
 * SAM-7914: Refactor \LotImage_UploadLotImage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoForm\Image;

/**
 * Trait LotImageOrderUpdaterCreateTrait
 * @package Sam\View\Admin\Form\LotInfoForm\Image
 */
trait LotImageOrderUpdaterCreateTrait
{
    protected ?LotImageOrderUpdater $lotImageOrderUpdater = null;

    /**
     * @return LotImageOrderUpdater
     */
    protected function createLotImageOrderUpdater(): LotImageOrderUpdater
    {
        return $this->lotImageOrderUpdater ?: LotImageOrderUpdater::new();
    }

    /**
     * @param LotImageOrderUpdater|null $lotImageOrderUpdater
     * @return static
     * @internal
     */
    public function setLotImageOrderUpdater(?LotImageOrderUpdater $lotImageOrderUpdater): static
    {
        $this->lotImageOrderUpdater = $lotImageOrderUpdater;
        return $this;
    }
}
