<?php
/**
 * Coupon List Data Loader
 *
 * SAM-6441: Refactor coupon list page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\CouponListForm\Load;


use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\Coupon\CouponReadRepository;
use Sam\Storage\ReadRepository\Entity\Coupon\CouponReadRepositoryCreateTrait;
use Sam\View\Admin\Form\CouponListForm\CouponListConstants;

/**
 * Class CouponListDataLoader
 */
class CouponListDataLoader extends CustomizableClass
{
    use CouponReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected ?string $filterStatus = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @param string|null $status null leads to show all coupons except for deleted status coupons
     * @return static
     */
    public function filterStatus(?string $status): static
    {
        $this->filterStatus = Cast::toString($status);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterStatus(): ?string
    {
        return $this->filterStatus;
    }

    /**
     * @return int - return value of Coupons count
     */
    public function count(): int
    {
        return $this->prepareCouponRepository()->count();
    }

    /**
     * @return array - return values for Coupons
     */
    public function load(): array
    {
        $repo = $this->prepareCouponRepository();

        switch ($this->getSortColumn()) {
            case CouponListConstants::ORD_TITLE:
                $repo->orderByTitle($this->isAscendingOrder());
                break;
            case CouponListConstants::ORD_CODE:
                $repo->orderByCode($this->isAscendingOrder());
                break;
            case CouponListConstants::ORD_COUPON_TYPE_NAME:
                $repo->orderByCouponTypeName($this->isAscendingOrder());
                break;
            case CouponListConstants::ORD_MIN_PURCHASE_AMT:
                $repo->orderByMinPurchaseAmt($this->isAscendingOrder());
                break;
            case CouponListConstants::ORD_PER_USER:
                $repo->orderByPerUser($this->isAscendingOrder());
                break;
            default:
                $repo->orderById($this->isAscendingOrder());
                break;
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = CouponListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return CouponReadRepository
     */
    protected function prepareCouponRepository(): CouponReadRepository
    {
        $couponRepository = $this->createCouponReadRepository()
            ->filterAccountId($this->getSystemAccountId())
            ->select(
                [
                    'code',
                    'coupon_status_id',
                    'coupon_type',
                    'end_date',
                    'fixed_amount_off',
                    'id',
                    'title',
                    'min_purchase_amt',
                    'percentage_off',
                    'per_user',
                    'start_date',
                    'timezone_id',
                    'waive_additional_charges',
                ]
            );
        if ($this->getFilterStatus() === 'Active') {
            $couponRepository->filterCouponStatusId(Constants\Coupon::STATUS_ACTIVE);
        } else {
            $couponRepository->skipCouponStatusId(Constants\Coupon::STATUS_DELETED);
        }

        return $couponRepository;
    }
}
