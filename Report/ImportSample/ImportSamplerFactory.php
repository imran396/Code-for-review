<?php
/**
 * SAM-4647: Refactor csv import sample builders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/23/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\ImportSample;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\ServiceAccountAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class ImportSamplerFactory
 * @package Sam\Report\ImportSample
 */
class ImportSamplerFactory extends CustomizableClass
{
    use ServiceAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $type
     * @return ReporterBase
     */
    public function create(?int $type): ReporterBase
    {
        $type = Cast::toInt($type);
        $sampler = null;
        if ($type === Constants\Csv\ImportSampler::ADMIN_TIMED) {
            $sampler = Lot\Admin\TimedLotImportSampler::new()
                ->construct($this->getServiceAccountId());
        } elseif ($type === Constants\Csv\ImportSampler::ADMIN_LIVE) {
            $sampler = Lot\Admin\LiveLotImportSampler::new()
                ->construct($this->getServiceAccountId());
        } elseif ($type === Constants\Csv\ImportSampler::ADMIN_INVENTORY) {
            $sampler = Lot\Admin\InventoryItemImportSampler::new()
                ->construct($this->getServiceAccountId());
        } elseif ($type === Constants\Csv\ImportSampler::ADMIN_USER) {
            $sampler = User\UserImportSampler::new()
                ->setSystemAccountId($this->getServiceAccountId());
        } elseif ($type === Constants\Csv\ImportSampler::ADMIN_POST_AUCTION) {
            $sampler = User\PostAuctionImportSampler::new();
        } elseif ($type === Constants\Csv\ImportSampler::ADMIN_BIDDER) {
            $sampler = Bidder\BidderImportSampler::new();
        } elseif ($type === Constants\Csv\ImportSampler::ADMIN_LOCATION) {
            $sampler = Location\LocationImportSampler::new();
        }
        if (!$sampler) {
            throw new InvalidArgumentException("Unknown type for import sampler" . composeSuffix(['type' => $type]));
        }
        return $sampler;
    }
}
