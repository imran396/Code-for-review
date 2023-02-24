<?php
/**
 *
 * SAM-4680: Coupon entity editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-09
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Coupon\Save;

/**
 * Trait CouponEditorAwareTrait
 * @package Sam\Coupon\Save
 */
trait CouponEditorCreateTrait
{
    /**
     * @var CouponEditor|null
     */
    protected ?CouponEditor $couponEditor = null;

    /**
     * @return CouponEditor
     */
    protected function createCouponEditor(): CouponEditor
    {
        return $this->couponEditor ?: CouponEditor::new();
    }

    /**
     * @param CouponEditor $couponEditor
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCouponEditor(CouponEditor $couponEditor): static
    {
        $this->couponEditor = $couponEditor;
        return $this;
    }
}
