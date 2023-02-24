<?php
/**
 * SAM-4590: Lot barcode-type custom field generating improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @since           16 Jan, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property int AccountId      account.id
 * @property bool Public         true - public side, false - admin side
 */

namespace Sam\CustomField\Base\TextType\Barcode\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;

/**
 * Class BarcodeGenerator
 * @package Sam\CustomField\Base\TextType\Barcode
 */
class BarcodeGenerator extends CustomizableClass
{
    use LotItemCustFieldReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $customFieldId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function generateBarcode(int $customFieldId, bool $isReadOnlyDb = false): string
    {
        $result = '1';
        $row = $this->createLotItemCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['MAX(licd.text) AS highest_barcode'])
            ->joinLotItemCustDataFilterActive(true)
            ->filterActive(true)
            ->filterId($customFieldId)
            ->loadRow();
        if ($row) {
            $result = $this->incrementBarcode((string)$row['highest_barcode']);
        }
        return $result;
    }

    /**
     * function to increment barcodes
     * - stripping it off its letters but keeping the '0's
     * - adding 1
     * - re-introducing the letters
     *
     * @param string $barcode
     * @return string
     */
    public function incrementBarcode(string $barcode): string
    {
        $output = '1';
        if (preg_match('/^[1-9]+$/', $barcode)) { //1234, 34567
            $barcodeInteger = (int)$barcode;
            $output = (string)++$barcodeInteger;
        } elseif (preg_match('/^\d+$/', $barcode)) { //01234, 001234, 000456 ...
            $length = strlen($barcode);
            $barcodeInteger = (int)$barcode;
            $output = str_pad((string)(++$barcodeInteger), $length, '0', STR_PAD_LEFT);
        } elseif (preg_match('/^\D+$/', $barcode)) { // abcd
            $output = $this->sanitizeAlphaNumericBarcode($barcode) . '1';
        } elseif ($barcode) { //ab12c3d4
            $chars = array_reverse(str_split($barcode));
            preg_match_all('/\d+/', $barcode, $match);
            $incBarcode = array_reverse(str_split($this->incrementBarcode(implode('', $match[0]))));
            $barcode = '';
            foreach ($chars as $char) {
                if (ctype_digit($char)) {
                    $barcode .= array_shift($incBarcode);
                } else {
                    $barcode .= $char;
                }
            }

            if (count($incBarcode)) {
                $barcode .= implode('', $incBarcode);
            }
            $output = strrev($barcode);
        }
        return $output;
    }

    /**
     * @param string $barcode
     * @return string
     */
    protected function sanitizeAlphaNumericBarcode(string $barcode): string
    {
        $sanitizedBarcode = str_replace('"', '', $barcode);
        return $sanitizedBarcode;
    }
}
