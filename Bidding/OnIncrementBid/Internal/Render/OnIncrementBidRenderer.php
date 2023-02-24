<?php
/**
 * On-increment messages rendering logic.
 *
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OnIncrementBid\Internal\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Currency\Load\CurrencyLoader;
use Sam\Lang\Translator;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class OnIncrementBidRenderer
 * @package Sam\Bidding\OnIncrementBid\Render
 */
class OnIncrementBidRenderer extends CustomizableClass
{
    use NumberFormatterAwareTrait;
    use OptionalsTrait;

    // Incoming values

    public const OP_CURRENCY_SIGN = OptionalKeyConstants::KEY_CURRENCY_SIGN; // string
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LINK_TEMPLATE = 'linkTemplate'; // string
    public const OP_MESSAGE_TEMPLATE_HTML = 'messageTemplateHtml'; // string
    public const OP_MESSAGE_TEMPLATE_CLEAN = 'messageTemplateClean'; // string

    // Internal values

    private const LINK_TEMPLATE_DEFAULT = '<a class="off-increment" href="#" data-controlid="%s" data-bid="%s">%s</a>';
    private const MESSAGE_TEMPLATE_DEFAULT = 'Off Increment. Place %s or %s';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Shows error message.
     * @param float $bid1
     * @param float $bid2
     * @param string $controlId
     * @param int $auctionId
     * @param int $accountId
     * @return string
     */
    public function buildErrorMessageHtml(
        float $bid1,
        float $bid2,
        string $controlId,
        int $auctionId,
        int $accountId
    ): string {
        $htmlTpl = $this->fetchMessageTemplateHtml($accountId);
        $linkTpl = (string)$this->fetchOptional(self::OP_LINK_TEMPLATE);
        $nf = $this->getNumberFormatter();
        $nearestFormatted = $nf->formatMoney($bid1);
        $nextNearestFormatted = $nf->formatMoney($bid2);
        $currencySign = $this->fetchCurrencySign($auctionId);
        $nearestLowerBid = sprintf($linkTpl, $controlId, $nearestFormatted, $currencySign . $nearestFormatted);
        $nextHigherBid = sprintf($linkTpl, $controlId, $nextNearestFormatted, $currencySign . $nextNearestFormatted);
        $output = sprintf($htmlTpl, $nearestLowerBid, $nextHigherBid);
        return $output;
    }

    /**
     * @param float $bid1
     * @param float $bid2
     * @return string
     */
    public function buildErrorMessageClean(float $bid1, float $bid2): string
    {
        $tpl = (string)$this->fetchOptional(self::OP_MESSAGE_TEMPLATE_CLEAN);
        $output = sprintf($tpl, $bid1, $bid2);
        return $output;
    }

    /**
     * @param int $accountId
     * @return string
     */
    protected function fetchMessageTemplateHtml(int $accountId): string
    {
        return (string)$this->fetchOptional(self::OP_MESSAGE_TEMPLATE_HTML, [$accountId]);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    protected function fetchCurrencySign(int $auctionId): string
    {
        return (string)$this->fetchOptional(self::OP_CURRENCY_SIGN, [$auctionId]);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;

        $optionals[self::OP_LINK_TEMPLATE] = $optionals[self::OP_LINK_TEMPLATE]
            ?? self::LINK_TEMPLATE_DEFAULT;

        $optionals[self::OP_MESSAGE_TEMPLATE_CLEAN] = $optionals[self::OP_MESSAGE_TEMPLATE_CLEAN]
            ?? self::MESSAGE_TEMPLATE_DEFAULT;

        $optionals[self::OP_CURRENCY_SIGN] = $optionals[self::OP_CURRENCY_SIGN]
            ?? static function (int $auctionId) use ($isReadOnlyDb): string {
                return CurrencyLoader::new()->detectDefaultSign($auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_MESSAGE_TEMPLATE_HTML] = $optionals[self::OP_MESSAGE_TEMPLATE_HTML]
            ?? static function (int $accountId): string {
                return Translator::new()->translate("CATALOG_OFF_INCREMENT_BID", "catalog", $accountId);
            };

        $this->setOptionals($optionals);
    }
}
