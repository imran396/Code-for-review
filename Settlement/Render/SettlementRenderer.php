<?php
/**
 * Helping methods for rendering settlement fields
 *
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Render;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\Image\SettlementImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Settings\Layout\Image\Path\LayoutImagePathResolverCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Settlement;

/**
 * Class SettlementRenderer
 * @package Sam\Settlement\Render
 */
class SettlementRenderer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use LayoutImagePathResolverCreateTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementStatus
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeSettlementStatusTranslated(
        int $settlementStatus,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $output = '';
        $langStatuses = [
            Constants\Settlement::SS_PENDING => 'MYSETTLEMENTS_STATUS_PENDING',
            Constants\Settlement::SS_PAID => 'MYSETTLEMENTS_STATUS_PAID',
            Constants\Settlement::SS_DELETED => 'MYSETTLEMENTS_STATUS_DELETED',
            Constants\Settlement::SS_OPEN => 'MYSETTLEMENTS_STATUS_OPEN',
        ];
        if (isset($langStatuses[$settlementStatus])) {
            $output = $this->getTranslator()->translate(
                $langStatuses[$settlementStatus],
                'mysettlements',
                $accountId,
                $languageId
            );
        }
        return $output;
    }

    /**
     * Render payment status based on settlement status
     * @param int $settlementStatus
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makePaymentStatusTranslated(
        int $settlementStatus,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $output = '';
        $langPaymentStatuses = [
            Constants\Settlement::SS_PENDING => 'PAYMENT_PENDING',
            Constants\Settlement::SS_PAID => 'PAYMENT_PAID',
            Constants\Settlement::SS_DELETED => 'PAYMENT_DELETED',
            Constants\Settlement::SS_OPEN => 'PAYMENT_OPEN',
        ];
        if (isset($langPaymentStatuses[$settlementStatus])) {
            $output = $this->getTranslator()->translate(
                $langPaymentStatuses[$settlementStatus],
                'auctions',
                $accountId,
                $languageId
            );
        }
        return $output;
    }

    /**
     * Return settlement logo url
     *
     * @param int $accountId
     * @return string
     */
    public function buildLogoUrl(int $accountId): string
    {
        if (!$this->createLayoutImagePathResolver()->hasSettlementLogo($accountId)) {
            return '';
        }

        return $this->getUrlBuilder()->build(
            SettlementImageUrlConfig::new()->construct($accountId)
        );
    }

    /**
     * @param Settlement $settlement
     * @param array $attributes
     * @return string
     */
    public function renderLogoTag(Settlement $settlement, array $attributes = []): string
    {
        $logoPath = $this->buildLogoUrl($settlement->AccountId);
        $attributes['src'] = $logoPath;
        $attributes['title'] = $attributes['title'] ?? '';
        if (!isset($attributes['alt'])) {
            $account = $this->getAccountLoader()->load($settlement->AccountId);
            $attributes['alt'] = $account->Name ?? '';
        }
        $output = HtmlRenderer::new()->makeImgHtmlTag('img', $attributes);
        return $output;
    }
}
