<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListPostAuctionImportPanel\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class SoldLotInfoInlineHelpRenderer
 * @package Sam\View\Admin\Form\AuctionLotListPostAuctionImportPanel\Render
 */
class SoldLotInfoImportInlineHelpRenderer extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_LOT_NO_CONCATENATED = 'lotNoConcatenated';
    public const OP_COLUMN_HEADERS = 'columnHeaders';

    protected const COLUMN_INFO = [
        Constants\Csv\Lot::LOT_FULL_NUMBER => 'optional, varchar',
        Constants\Csv\Lot::LOT_NUM_PREFIX => 'optional, varchar',
        Constants\Csv\Lot::LOT_NUM => 'required, int',
        Constants\Csv\Lot::LOT_NUM_EXT => 'optional, varchar',
        Constants\Csv\Lot::HAMMER_PRICE => 'optional, float',
        Constants\Csv\User::EMAIL => 'optional, varchar',
        Constants\Csv\User::USERNAME => 'optional, varchar',
        Constants\Csv\User::CUSTOMER_NO => 'optional, int',
        // User info
        Constants\Csv\User::FIRST_NAME => 'optional, varchar',
        Constants\Csv\User::LAST_NAME => 'optional, varchar',
        Constants\Csv\User::PHONE => 'optional, varchar',
        // User Billing info
        Constants\Csv\User::BILLING_FIRST_NAME => 'optional, varchar',
        Constants\Csv\User::BILLING_LAST_NAME => 'optional, varchar',
        Constants\Csv\User::BILLING_COMPANY_NAME => 'optional, varchar',
        Constants\Csv\User::BILLING_PHONE => 'optional, varchar',
        Constants\Csv\User::BILLING_FAX => 'optional, varchar',
        Constants\Csv\User::BILLING_COUNTRY => 'optional, varchar',
        Constants\Csv\User::BILLING_ADDRESS => 'optional, varchar',
        Constants\Csv\User::BILLING_ADDRESS_2 => 'optional, varchar',
        Constants\Csv\User::BILLING_ADDRESS_3 => 'optional, varchar',
        Constants\Csv\User::BILLING_CITY => 'optional, varchar',
        Constants\Csv\User::BILLING_STATE => 'optional, varchar',
        Constants\Csv\User::BILLING_ZIP => 'optional, varchar',
        // User Shipping info
        Constants\Csv\User::SHIPPING_FIRST_NAME => 'optional, varchar',
        Constants\Csv\User::SHIPPING_LAST_NAME => 'optional, varchar',
        Constants\Csv\User::SHIPPING_COMPANY_NAME => 'optional, varchar',
        Constants\Csv\User::SHIPPING_PHONE => 'optional, varchar',
        Constants\Csv\User::SHIPPING_FAX => 'optional, varchar',
        Constants\Csv\User::SHIPPING_COUNTRY => 'optional, varchar',
        Constants\Csv\User::SHIPPING_ADDRESS => 'optional, varchar',
        Constants\Csv\User::SHIPPING_ADDRESS_2 => 'optional, varchar',
        Constants\Csv\User::SHIPPING_ADDRESS_3 => 'optional, varchar',
        Constants\Csv\User::SHIPPING_CITY => 'optional, varchar',
        Constants\Csv\User::SHIPPING_STATE => 'optional, varchar',
        Constants\Csv\User::SHIPPING_ZIP => 'optional, varchar',
        // Internet Bid
        Constants\Csv\Lot::INTERNET_BID => 'boolean, Y or N',
        // Lot notes
        Constants\Csv\Lot::LOT_NOTES => 'optional, varchar'
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
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $columnInfoCollection = $this->detectColumnsInfo();
        $columnHeaders = $this->fetchOptional(self::OP_COLUMN_HEADERS);
        $columnsInlineHelp = [];
        foreach ($columnInfoCollection as $column => $info) {
            $columnsInlineHelp[] = sprintf('%s (%s)', $columnHeaders[$column], $info);
        }
        return implode(', ', $columnsInlineHelp);
    }

    /**
     * @return string[]
     */
    protected function detectColumnsInfo(): array
    {
        $columnInfo = self::COLUMN_INFO;
        if ($this->fetchOptional(self::OP_LOT_NO_CONCATENATED)) {
            unset(
                $columnInfo[Constants\Csv\Lot::LOT_NUM_PREFIX],
                $columnInfo[Constants\Csv\Lot::LOT_NUM], $columnInfo[Constants\Csv\Lot::LOT_NUM_EXT]
            );
        } else {
            unset($columnInfo[Constants\Csv\Lot::LOT_FULL_NUMBER]);
        }
        return $columnInfo;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_COLUMN_HEADERS] = $optionals[self::OP_COLUMN_HEADERS]
            ?? static function (): array {
                return ConfigRepository::getInstance()->get('csv->admin->postAuction')->toArray();
            };
        $optionals[self::OP_LOT_NO_CONCATENATED] = $optionals[self::OP_LOT_NO_CONCATENATED]
            ?? static function (): bool {
                return ConfigRepository::getInstance()->get('core->lot->lotNo->concatenated');
            };
        $this->setOptionals($optionals);
    }
}
