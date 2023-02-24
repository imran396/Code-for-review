<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Statistic;

use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Lot\AuctionLot\AuctionLotImportCsvProcessStatistic;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for generating messages about the result of the import lot process
 *
 * Class LotImportCsvFinalStatMaker
 * @package Sam\Import\Csv\Lot\Internal\Statistic
 * @internal
 */
class LotImportCsvFinalStatMaker extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TYPE_ERROR = 'error';
    protected const TYPE_SUCCESS = 'success';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make a messages based on the statistics collected
     *
     * @param AuctionLotImportCsvProcessStatistic|LotImportCsvProcessStatistic $statistic
     * @param bool $isExistImageZip
     * @param bool $isExistFilesZip
     * @return array
     */
    public function make(
        LotImportCsvProcessStatistic $statistic,
        bool $isExistImageZip,
        bool $isExistFilesZip
    ): array {
        $messages = [];
        $translator = $this->getAdminTranslator();

        if ($statistic->rejectedImagesQuantity > 0) {
            $messages[] = $this->makeErrorMessage(
                $translator->trans(
                    'import.csv.lot_item.stat.images_rejected',
                    [
                        'rejectedImagesQty' => $statistic->rejectedImagesQuantity,
                        'addedImagesQty' => $statistic->addedImagesQuantity
                    ],
                    'admin_message'
                )
            );
        }

        $statMessageComponents = [];
        if ($statistic->addedLotsQuantity > 0) {
            $statMessageComponents[] = $translator->trans(
                'import.csv.lot_item.stat.lots_added',
                [
                    'qty' => $statistic->addedLotsQuantity,
                ],
                'admin_message'
            );
        }
        if ($statistic->updatedLotsQuantity > 0) {
            $statMessageComponents[] = $translator->trans(
                'import.csv.lot_item.stat.lots_updated',
                [
                    'qty' => $statistic->updatedLotsQuantity
                ],
                'admin_message'
            );
        }
        if ($isExistImageZip) {
            $statMessageComponents[] = $translator->trans('import.csv.lot_item.stat.zip_images_uploaded', [], 'admin_message');
        }
        if ($isExistFilesZip > 0) {
            $statMessageComponents[] = $translator->trans('import.csv.lot_item.stat.zip_files_uploaded', [], 'admin_message');
        }
        $messages[] = $this->makeSuccessMessage(implode(' ', $statMessageComponents));
        return $messages;
    }

    /**
     * @param string $message
     * @return string[]
     */
    protected function makeSuccessMessage(string $message): array
    {
        return [
            'type' => self::TYPE_SUCCESS,
            'text' => $message
        ];
    }

    /**
     * @param string $message
     * @return string[]
     */
    protected function makeErrorMessage(string $message): array
    {
        return [
            'type' => self::TYPE_ERROR,
            'text' => $message
        ];
    }
}
