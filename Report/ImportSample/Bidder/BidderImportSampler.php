<?php
/**
 * SAM-3796 : Bidder upload into auction
 * https://bidpath.atlassian.net/browse/SAM-3796
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Feb 14, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Report\ImportSample\Bidder;

use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\ImportSample\ImportSamplerBase;

/**
 * Class BidderImportSampler
 * @package Sam\Report\ImportSample\Bidder
 */
class BidderImportSampler extends ImportSamplerBase
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    protected int $bodyRowCount = 4;
    protected ?string $outputFileName = 'bidder.csv';

    /**
     * @return static
     */
    public function initInstance(): static
    {
        /**
         * When array values - render each value per csv line, if not enough values in array, we render empty cell.
         * When single value - render the same value for each row.
         */
        $this->sampleValues = [
            Constants\Csv\User::BIDDER_NO => [1, 2, 3, 4],
            Constants\Csv\User::USERNAME => ['user1', 'user2', 'user3', 'user4'],
            Constants\Csv\User::COMPANY_NAME => ['company1', 'company2', 'company3', 'company4'],
            Constants\Csv\User::EMAIL => ['user1@gmail.com', 'user2@gmail.com', 'user3@gmail.com', 'user4@gmail.com'],
            Constants\Csv\User::FIRST_NAME => ['firstName1', 'firstName2', 'firstName3', 'firstName3'],
            Constants\Csv\User::LAST_NAME => ['LastName1', 'LastName2', 'LastName3', 'LastName4'],
            Constants\Csv\User::CUSTOMER_NO => [1, 2, 3, 4],
            Constants\Csv\User::BILLING_FIRST_NAME => ['BillingFirstName_01', 'BillingFirstName_02', 'BillingFirstName_03', 'BillingFirstName_04'],
            Constants\Csv\User::BILLING_LAST_NAME => ['BillingLastName_01', 'BillingLastName_02', 'BillingLastName_03', 'BillingLastName_04'],
            Constants\Csv\User::BILLING_COMPANY_NAME => ['BillingCompanyName_01', 'BillingCompanyName_02', 'BillingCompanyName_03', 'BillingCompanyName_04'],
            Constants\Csv\User::BILLING_PHONE => ['BillingPhone_01', 'BillingPhone_02', 'BillingPhone_03', 'BillingPhone_04'],
            Constants\Csv\User::BILLING_FAX => ['BillingFax_01', 'BillingFax_02', 'BillingFax_03', 'BillingFax_04'],
            Constants\Csv\User::BILLING_COUNTRY => ['United States', 'United States', 'United States', 'United States'],
            Constants\Csv\User::BILLING_ADDRESS => ['BillingAddress_01', 'BillingAddress_02', 'BillingAddress_03', 'BillingAddress_04'],
            Constants\Csv\User::BILLING_ADDRESS_2 => ['BillingAddress2_01', 'BillingAddress2_02', 'BillingAddress2_03', 'BillingAddress2_04'],
            Constants\Csv\User::BILLING_ADDRESS_3 => ['BillingAddress3_01', 'BillingAddress3_02', 'BillingAddress3_03', 'BillingAddress3_04'],
            Constants\Csv\User::BILLING_CITY => ['BillingCity_01', 'BillingCity_02', 'BillingCity_03', 'BillingCity_04'],
            Constants\Csv\User::BILLING_STATE => ['Utah', 'Utah', 'Utah', 'Utah'],
            Constants\Csv\User::BILLING_ZIP => ['BillingZip_01', 'BillingZip_02', 'BillingZip_03', 'BillingZip_04'],
            Constants\Csv\User::SHIPPING_FIRST_NAME => ['ShippingFirstName_01', 'ShippingFirstName_02', 'ShippingFirstName_03', 'ShippingFirstName_04'],
            Constants\Csv\User::SHIPPING_LAST_NAME => ['ShippingLastName_01', 'ShippingLastName_02', 'ShippingLastName_03', 'ShippingLastName_04'],
            Constants\Csv\User::SHIPPING_COMPANY_NAME => ['ShippingCompanyName_01', 'ShippingCompanyName_02', 'ShippingCompanyName_03', 'ShippingCompanyName_04'],
            Constants\Csv\User::SHIPPING_PHONE => ['ShippingPhone_01', 'ShippingPhone_02', 'ShippingPhone_03', 'ShippingPhone_04'],
            Constants\Csv\User::SHIPPING_FAX => ['ShippingFax_01', 'ShippingFax_02', 'ShippingFax_03', 'ShippingFax_04'],
            Constants\Csv\User::SHIPPING_COUNTRY => ['United States', 'United States', 'United States', 'United States'],
            Constants\Csv\User::SHIPPING_ADDRESS => ['ShippingAddress_01', 'ShippingAddress_02', 'ShippingAddress_03', 'ShippingAddress_04'],
            Constants\Csv\User::SHIPPING_ADDRESS_2 => ['ShippingAddress2_01', 'ShippingAddress2_02', 'ShippingAddress2_03', 'ShippingAddress2_04'],
            Constants\Csv\User::SHIPPING_ADDRESS_3 => ['ShippingAddress3_01', 'ShippingAddress3_02', 'ShippingAddress3_03', 'ShippingAddress3_04'],
            Constants\Csv\User::SHIPPING_CITY => ['ShippingCity_01', 'ShippingCity_02', 'ShippingCity_03', 'ShippingCity_04'],
            Constants\Csv\User::SHIPPING_STATE => ['New Jersey', 'New Jersey', 'New Jersey', 'New Jersey'],
            Constants\Csv\User::SHIPPING_ZIP => ['ShippingZip_01', 'ShippingZip_02', 'ShippingZip_03', 'ShippingZip_04'],
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function output(): string
    {
        $this->setTitles($this->cfg()->get('csv->admin->bidder')->toArray());
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
