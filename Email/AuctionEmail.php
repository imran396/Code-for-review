<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 10, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email;


use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class AuctionEmail
 * @package Sam\Email
 */
class AuctionEmail extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch email address by invoice id
     *
     * @param int $invoiceId
     * @return string
     */
    public function getByInvoice(int $invoiceId): string
    {
        $rows = $this->createAuctionReadRepository()
            ->joinInvoiceItemFilterInvoiceId($invoiceId)
            ->skipEmail('')
            ->joinInvoiceItemFilterActive(true)
            ->groupByEmail()
            ->select(['a.email'])
            ->loadRows();
        $emails = array_column($rows, 'email');
        $auctionEmail = implode(',', $emails);
        return $auctionEmail;
    }

    /**
     * Fetch email address by settlement id
     * @param int $settlementId
     * @return string
     */
    public function getBySettlement(int $settlementId): string
    {
        $rows = $this->createAuctionReadRepository()
            ->joinSettingsItemFilterSettlementId($settlementId)
            ->skipEmail('')
            ->groupByEmail()
            ->select(['a.email'])
            ->loadRows();
        $emails = array_column($rows, 'email');
        $auctionEmail = implode(',', $emails);
        return $auctionEmail;
    }

}
