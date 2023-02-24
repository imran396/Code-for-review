<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
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

/**
 * Trait BuyersPremiumCsvParserCreateTrait
 * @package Sam\BuyersPremium\Csv\Parse
 */
trait BuyersPremiumCsvParserCreateTrait
{
    /**
     * @var BuyersPremiumCsvParser|null
     */
    protected ?BuyersPremiumCsvParser $buyersPremiumCsvParser = null;

    /**
     * @return BuyersPremiumCsvParser
     */
    protected function createBuyersPremiumCsvParser(): BuyersPremiumCsvParser
    {
        return $this->buyersPremiumCsvParser ?: BuyersPremiumCsvParser::new();
    }

    /**
     * @param BuyersPremiumCsvParser $buyersPremiumCsvParser
     * @return static
     * @internal
     */
    public function setBuyersPremiumCsvParser(BuyersPremiumCsvParser $buyersPremiumCsvParser): static
    {
        $this->buyersPremiumCsvParser = $buyersPremiumCsvParser;
        return $this;
    }
}
