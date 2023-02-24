<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build;

use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;


class MessageBuilder extends CustomizableClass
{
    use DataProviderCreateTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    public function build(
        string $url,
        int $auctionId,
        int $userId,
        int $systemAccountId,
        ?int $languageId,
        bool $isReadOnlyDb
    ): string {
        $dataProvider = $this->createDataProvider();

        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb);
        $accountId = $auction->AccountId;
        if (
            $dataProvider->isAdminRoute($url)
            || $dataProvider->isRegConfirmPageEnabled($accountId)
        ) {
            return '';
        }

        $auctionBidder = $dataProvider->loadAuctionBidder($userId, $auctionId, $isReadOnlyDb);

        $translator = $this->getTranslator();
        if ($dataProvider->isBidderApproved($auctionBidder)) {
            $message = $translator->translate(
                "GENERAL_REG_THANKS",
                "general",
                $systemAccountId,
                $languageId
            );
        } else {
            $message = $translator->translate(
                "GENERAL_REG_THANKS_REVIEWING",
                "general",
                $systemAccountId,
                $languageId
            );
        }

        return $message;
    }
}
