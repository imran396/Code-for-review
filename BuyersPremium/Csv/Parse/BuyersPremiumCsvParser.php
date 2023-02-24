<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Csv\Parse;

use Sam\Core\BuyersPremium\Csv\Parse\BuyersPremiumCsvPureParser;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BuyersPremiumCsvParser
 * @package Sam\BuyersPremium\Csv\Parse
 */
class BuyersPremiumCsvParser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use NumberFormatterAwareTrait;

    protected const PARSE_ERROR = "Buyers premium ranges do not match '%s' pattern";

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parse data from 'bpSetting' column
     * Ex: '2-0->|1000:5-2->|5000:0-7->'
     *
     * @param string $buyersPremiums
     * @param int $entityContextAccountId account of context defined by entity's account
     * @return array of pairs [Amount, Fixed, Percent, Mode]
     */
    public function parse(string $buyersPremiums, int $entityContextAccountId): array
    {
        $dataRows = BuyersPremiumCsvPureParser::new()->parse($buyersPremiums, $this->prepareOptionals());
        $dataRows = $this->removeFormat($dataRows, $entityContextAccountId);
        return $dataRows;
    }

    /**
     * Check if string is a valid CSV representation of buyers premiums
     *
     * @param string $buyersPremiums
     * @return bool
     */
    public function validateSyntax(string $buyersPremiums): bool
    {
        return BuyersPremiumCsvPureParser::new()->validate($buyersPremiums, $this->prepareOptionals());
    }

    public function errorMessage(): string
    {
        return sprintf(self::PARSE_ERROR, BuyersPremiumCsvPureParser::new()->hintPattern());
    }

    protected function removeFormat(array $dataRows, int $entityAccountId): array
    {
        $numberFormatter = $this->getNumberFormatter();
        foreach ($dataRows as $key => [$amount, $fixed, $percent, $mode]) {
            $amount = $numberFormatter->parse($amount, 2, $entityAccountId);
            $fixed = $numberFormatter->parse($fixed, 2, $entityAccountId);
            $percent = $numberFormatter->parsePercent($percent, $entityAccountId);
            $dataRows[$key] = [$amount, $fixed, $percent, $mode];
        }
        return $dataRows;
    }

    protected function prepareOptionals(): array
    {
        return [
            BuyersPremiumCsvPureParser::OP_CLEAR_VALUE_MARKER => $this->cfg()->get('core->csv->clearValue'),
            BuyersPremiumCsvPureParser::OP_DEFAULT_VALUE_MARKER => $this->cfg()->get('core->csv->defaultValue'),
        ];
    }
}
