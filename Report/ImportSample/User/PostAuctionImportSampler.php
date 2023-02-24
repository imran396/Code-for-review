<?php
/**
 * SAM-5221 : FullLotNumber change wasn't applied to post auction import & Post auction sample csv file
 * https://bidpath.atlassian.net/browse/SAM-5221
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\ImportSample\User;

use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\ImportSample\ImportSamplerBase;

/**
 * Class LiveLotImportSampler
 * @package Sam\Report\ImportSample\Lot\Admin
 */
class PostAuctionImportSampler extends ImportSamplerBase
{
    use ConfigRepositoryAwareTrait;

    protected ?string $outputFileName = 'post-auction.csv';
    protected int $bodyRowCount = 3;
    protected array $sampleValues = [
        Constants\Csv\Lot::LOT_NUM => ['1', '2', '3'],
        Constants\Csv\Lot::LOT_FULL_NUMBER => ['1', '2', '3'],
        Constants\Csv\Lot::HAMMER_PRICE => ['100', '200', '300'],
        Constants\Csv\User::EMAIL => ['user1@gmail.com', 'user2@gmail.com', 'user3@gmail.com'],
        Constants\Csv\User::USERNAME => ['user1', 'user2', 'user3'],
        Constants\Csv\User::CUSTOMER_NO => ['', '', ''],
        Constants\Csv\User::FIRST_NAME => ['UserFirstName_01', 'UserFirstName_02', 'UserFirstName_03'],
        Constants\Csv\User::LAST_NAME => ['UserLastName_01', 'UserLastName_02', 'UserLastName_03'],
        Constants\Csv\User::PHONE => ['UserPhone_01', 'UserPhone_02', 'UserPhone_03'],
        Constants\Csv\User::BILLING_FIRST_NAME => ['BillingFirstName_01', 'BillingFirstName_02', 'BillingFirstName_03'],
        Constants\Csv\User::BILLING_LAST_NAME => ['BillingLastName_01', 'BillingLastName_02', 'BillingLastName_03'],
        Constants\Csv\User::BILLING_COMPANY_NAME => ['BillingCompanyName_01', 'BillingCompanyName_02', 'BillingCompanyName_03'],
        Constants\Csv\User::BILLING_PHONE => ['BillingPhone_01', 'BillingPhone_02', 'BillingPhone_03'],
        Constants\Csv\User::BILLING_FAX => ['BillingFax_01', 'BillingFax_02', 'BillingFax_03'],
        Constants\Csv\User::BILLING_COUNTRY => ['United States', 'United States', 'United States'],
        Constants\Csv\User::BILLING_ADDRESS => ['BillingAddress_01', 'BillingAddress_02', 'BillingAddress_03'],
        Constants\Csv\User::BILLING_ADDRESS_2 => ['BillingAddress2_01', 'BillingAddress2_02', 'BillingAddress2_03'],
        Constants\Csv\User::BILLING_ADDRESS_3 => ['BillingAddress3_01', 'BillingAddress3_02', 'BillingAddress3_03'],
        Constants\Csv\User::BILLING_CITY => ['BillingCity_01', 'BillingCity_02', 'BillingCity_03'],
        Constants\Csv\User::BILLING_STATE => ['Utah', 'Utah', 'Utah'],
        Constants\Csv\User::BILLING_ZIP => ['BillingZip_01', 'BillingZip_02', 'BillingZip_03'],
        Constants\Csv\User::SHIPPING_FIRST_NAME => ['ShippingFirstName_01', 'ShippingFirstName_02', 'ShippingFirstName_03'],
        Constants\Csv\User::SHIPPING_LAST_NAME => ['ShippingLastName_01', 'ShippingLastName_02', 'ShippingLastName_03'],
        Constants\Csv\User::SHIPPING_COMPANY_NAME => ['ShippingCompanyName_01', 'ShippingCompanyName_02', 'ShippingCompanyName_03'],
        Constants\Csv\User::SHIPPING_PHONE => ['ShippingPhone_01', 'ShippingPhone_02', 'ShippingPhone_03'],
        Constants\Csv\User::SHIPPING_FAX => ['ShippingFax_01', 'ShippingFax_02', 'ShippingFax_03'],
        Constants\Csv\User::SHIPPING_COUNTRY => ['United States', 'United States', 'United States'],
        Constants\Csv\User::SHIPPING_ADDRESS => ['ShippingAddress_01', 'ShippingAddress_02', 'ShippingAddress_03'],
        Constants\Csv\User::SHIPPING_ADDRESS_2 => ['ShippingAddress2_01', 'ShippingAddress2_02', 'ShippingAddress2_03'],
        Constants\Csv\User::SHIPPING_ADDRESS_3 => ['ShippingAddress3_01', 'ShippingAddress3_02', 'ShippingAddress3_03'],
        Constants\Csv\User::SHIPPING_CITY => ['ShippingCity_01', 'ShippingCity_02', 'ShippingCity_03'],
        Constants\Csv\User::SHIPPING_STATE => ['New Jersey', 'New Jersey', 'New Jersey'],
        Constants\Csv\User::SHIPPING_ZIP => ['ShippingZip_01', 'ShippingZip_02', 'ShippingZip_03'],
        Constants\Csv\Lot::INTERNET_BID => ['N', 'N', 'N'],
        Constants\Csv\Lot::LOT_NOTES => ['lotNotes_01', 'lotNotes_02', 'lotNotes_03'],
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
     * @return string
     */
    public function output(): string
    {
        $this->setTitles($this->cfg()->get('csv->admin->postAuction')->toArray());
        $this->sendHttpHeader();
        $this->outputContent();
        return '';
    }

    /**
     * @return void
     */
    protected function outputContent(): void
    {
        echo $this->produceContent();
    }

    /**
     * @param string[] $titles
     * @return static
     */
    public function setTitles(array $titles): static
    {
        $skipLotTitles = $this->cfg()->get('core->lot->lotNo->concatenated')
            ? [Constants\Csv\Lot::LOT_NUM_PREFIX, Constants\Csv\Lot::LOT_NUM, Constants\Csv\Lot::LOT_NUM_EXT]
            : [Constants\Csv\Lot::LOT_FULL_NUMBER];
        $titles = array_diff_key($titles, array_flip($skipLotTitles));
        parent::setTitles($titles);
        return $this;
    }

    /**
     * @return string
     */
    protected function produceContent(): string
    {
        $titles = $this->getTitles();
        $headerLine = $this->getReportTool()->makeLine($titles, $this->getEncoding());
        $contentRows[] = $headerLine;

        for ($i = 0; $i < $this->bodyRowCount; $i++) {
            $bodyRow = [];
            foreach (array_keys($titles) as $title) {
                $bodyRow[] = $this->produceValue($title, $i);
            }
            $bodyLine = $this->getReportTool()->makeLine($bodyRow, $this->getEncoding());
            $contentRows[] = $bodyLine;
        }

        $contentCsv = implode('', $contentRows);
        return $contentCsv;
    }
}
