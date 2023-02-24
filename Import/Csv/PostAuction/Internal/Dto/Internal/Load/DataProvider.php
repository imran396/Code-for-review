<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Dto\Internal\Load;

use Sam\AuctionLot\LotNo\Parse\LotNoParser;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoader;
use Sam\User\Password\Generator;
use User;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generateEncryptedPassword(): string
    {
        return Generator::new()
            ->initBySystemParametersOfMainAccount()
            ->generateEncrypted();
    }

    public function loadUserByEmail(string $email, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->loadByEmail($email, $isReadOnlyDb);
    }

    public function parseLotNo(string $lotNo): LotNoParsed
    {
        return LotNoParser::new()->construct()->parse($lotNo);
    }
}
