<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\UserConsignorCommissionFee;

/**
 *
 * General repository for UserConsignorCommissionFee entity
 *
 * Class UserConsignorCommissionFeeReadRepository
 * @package Sam\Storage\ReadRepository\Entity\UserConsignorCommissionFee
 */
class UserConsignorCommissionFeeReadRepository extends AbstractUserConsignorCommissionFeeReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = uccf.account_id',
        'consignor_commission' => 'JOIN consignor_commission_fee ccf_cc ON ccf_cc.id = uccf.commission_id',
        'consignor_sold_fee' => 'JOIN consignor_commission_fee ccf_csf ON ccf_csf.id = uccf.sold_fee_id',
        'consignor_unsold_fee' => 'JOIN consignor_commission_fee ccf_cuf ON ccf_cuf.id = uccf.unsold_fee_id',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    public function joinAccountFilterActive(bool|array $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    public function joinConsignorCommission(): static
    {
        $this->join('consignor_commission');
        return $this;
    }

    public function joinConsignorCommissionFilterActive(bool|array $active): static
    {
        $this->joinConsignorCommission();
        $this->filterArray('ccf_cc.active', $active);
        return $this;
    }

    public function joinConsignorSoldFee(): static
    {
        $this->join('consignor_sold_fee');
        return $this;
    }

    public function joinConsignorSoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorSoldFee();
        $this->filterArray('ccf_csf.active', $active);
        return $this;
    }

    public function joinConsignorUnsoldFee(): static
    {
        $this->join('consignor_unsold_fee');
        return $this;
    }

    public function joinConsignorUnsoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorUnsoldFee();
        $this->filterArray('ccf_cuf.active', $active);
        return $this;
    }
}
