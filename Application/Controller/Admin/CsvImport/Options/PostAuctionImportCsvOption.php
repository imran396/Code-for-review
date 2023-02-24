<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\CsvImport\Options;

use Sam\Application\RequestParam\RequestParamFetcher;
use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class that contains all post auction import options
 *
 * Class PostAuctionImportCsvOption
 * @package Sam\Application\Controller\Admin\CsvImport\Options
 */
class PostAuctionImportCsvOption extends CustomizableClass implements ImportCsvOptionInterface
{
    use NumberFormatterAwareTrait;

    /**
     * @var float
     */
    public float $additionalPremium;
    /**
     * @var int
     */
    public int $auctionId;
    /**
     * @var string
     */
    public string $encoding;
    /**
     * @var bool
     */
    public bool $unassignUnsold;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $auctionId, string $encoding, float $additionalPremium, bool $unassignUnsold): static
    {
        $this->auctionId = $auctionId;
        $this->encoding = $encoding;
        $this->additionalPremium = $additionalPremium;
        $this->unassignUnsold = $unassignUnsold;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fromRequest(RequestParamFetcher $paramFetcher): static
    {
        $additionalPremium = $paramFetcher->getString('additionalPremium');
        return $this->construct(
            $paramFetcher->getIntPositive('auctionId'),
            $paramFetcher->getString('encoding'),
            (float)$this->getNumberFormatter()->removeFormat($additionalPremium),
            $paramFetcher->getBool('unassignUnsold')
        );
    }
}
