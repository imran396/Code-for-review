<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build;

use Auction;
use AuctionEmailTemplate;
use EmailTemplate;
use InvalidArgumentException;
use Invoice;
use Sam\Account\Image\Path\AccountLogoPathResolverCreateTrait;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResetPasswordUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Image\AccountImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\Config\Invoice\ResponsiveInvoiceViewUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Email\Build\AbsenteeBid\DataProviderAwareTrait;
use Sam\Image\ImageHelper;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\ResetPassword\ResetPasswordWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Password\Reset\ResetLinkBuilderCreateTrait;

/**
 * A builder class, that create an array of placeholders. These placeholders will be replaced in final email.
 * Class PlaceholdersAbstractBuilder
 * @package Sam\Email
 */
abstract class PlaceholdersAbstractBuilder extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AccountLogoPathResolverCreateTrait;
    use BidderInfoRendererAwareTrait;
    use DataProviderAwareTrait;
    use EntityFactoryCreateTrait;
    use LotImageLoaderAwareTrait;
    use ResetLinkBuilderCreateTrait;
    use ResetPasswordWriteRepositoryAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserLoaderAwareTrait;

    protected ?int $accountId = null;
    protected EmailTemplate|AuctionEmailTemplate|null $emailTemplate = null;
    protected ?string $emailKey = null;
    protected ?DataConverterAbstract $dataConverter = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function buildRepeated(): array
    {
        return [];
    }

    /**
     * Generates html tag (img) of an account's logo
     * @return string
     */
    protected function buildAccountLogoImageTag(): string
    {
        $accountLogoImgTag = '';
        if ($this->createAccountLogoPathResolver()->existThumbnail($this->accountId)) {
            $accountImageUrl = $this->getUrlBuilder()->build(
                AccountImageUrlConfig::new()->construct($this->accountId)
            );
            $accountLogoImgTag = "<img src=\"{$accountImageUrl}\" alt=\"\" title=\"\" class=\"account-img\" />";
        }
        return $accountLogoImgTag;
    }

    /**
     * @return array
     */
    protected function getDefaultPlaceholders(): array
    {
        $account = $this->getAccountLoader()->load($this->accountId);
        if (!$account) {
            log_error("Available account not found" . composeSuffix(['acc' => $this->accountId]));
            return [];
        }

        $placeholders = [
            'account_logo_url' => $this->buildAccountLogoImageTag(),
            'account_company_name' => $account->CompanyName,
        ];
        return $placeholders;
    }

    /**
     * Get invoice link urls placeholders: 'invoice_url'
     * @param Invoice $invoice
     * @return string
     */
    protected function buildResponsiveInvoiceUrl(Invoice $invoice): string
    {
        $invoiceUrl = $this->getUrlBuilder()->build(
            ResponsiveInvoiceViewUrlConfig::new()->forDomainRule(
                $invoice->Id,
                [UrlConfigConstants::OP_ACCOUNT_ID => $invoice->AccountId]
            )
        );
        return $invoiceUrl;
    }

    /**
     * Get user's reset password link
     * @param int $userId
     * @param int $accountId
     * @param int $editorUserId
     * @return string
     */
    protected function buildResetPasswordUrl(int $userId, int $accountId, int $editorUserId): string
    {
        $resetPasswordUrl = '';
        if (str_contains($this->emailTemplate->Message, '{reset_password_url}')) {
            $resetCode = $this->createResetLinkBuilder()->generate();
            $resetPasswordUrl = $this->getUrlBuilder()->build(
                ResetPasswordUrlConfig::new()->forDomainRule(
                    [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
                )
            );
            $resetPasswordUrl = $this->getUrlParser()->replaceParams(
                $resetPasswordUrl,
                [
                    Constants\UrlParam::REGEN => $resetCode,
                    Constants\UrlParam::ID => $userId
                ]
            );
            $resetPassword = $this->createEntityFactory()->resetPassword();
            $resetPassword->UserId = $userId;
            $resetPassword->ResetLink = $resetCode;
            $this->getResetPasswordWriteRepository()->saveWithModifier($resetPassword, $editorUserId);
        }
        return $resetPasswordUrl;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    public function buildResponsiveCatalogUrl(Auction $auction): string
    {
        $catalogUrl = $this->getUrlBuilder()->build(
            ResponsiveCatalogUrlConfig::new()->forDomainRule(
                $auction->Id,
                null,
                [UrlConfigConstants::OP_ACCOUNT_ID => $auction->AccountId]
            )
        );
        return $catalogUrl;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId //Unused
     * @param int $accountId
     * @return string
     */
    protected function buildLotImageUrl(int $lotItemId, int $auctionId, int $accountId): string
    {
        $lotImage = $this->getLotImageLoader()->loadDefaultForLot($lotItemId, true);
        $lotImageUrl = '';
        if ($lotImage) {
            $defaultSize = ImageHelper::new()->detectSizeFromMapping();
            $lotImageUrl = $this->getUrlBuilder()->build(
                LotImageUrlConfig::new()->construct($lotImage->Id, $defaultSize, $accountId)
            );
        }
        return $lotImageUrl;
    }

    /**
     * Return url of catalog lot page to responsive side
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $accountId
     * @return string
     */
    protected function buildLotDetailsUrl(int $lotItemId, int $auctionId, int $accountId): string
    {
        $lotDetailsUrl = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                $lotItemId,
                $auctionId,
                null,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
        return $lotDetailsUrl;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId(int $accountId): void
    {
        $this->accountId = $accountId;
    }

    /**
     * @return EmailTemplate|AuctionEmailTemplate
     */
    public function getEmailTemplate(): EmailTemplate|AuctionEmailTemplate
    {
        return $this->emailTemplate;
    }

    /**
     * @param EmailTemplate|AuctionEmailTemplate $emailTemplate
     */
    public function setEmailTemplate(EmailTemplate|AuctionEmailTemplate $emailTemplate): void
    {
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * @return string
     */
    public function getEmailKey(): string
    {
        return $this->emailKey;
    }

    /**
     * @param string $emailKey
     * @return static
     */
    public function setEmailKey(string $emailKey): static
    {
        $this->emailKey = trim($emailKey);
        return $this;
    }

    /**
     * @return DataConverterAbstract
     */
    public function getDataConverter(): DataConverterAbstract
    {
        if ($this->dataConverter === null) {
            throw new InvalidArgumentException("Please, provide Data Converter");
        }
        return $this->dataConverter;
    }

    /**
     * @param DataConverterAbstract $dataConverter
     * @return static
     */
    public function setDataConverter(DataConverterAbstract $dataConverter): static
    {
        $this->dataConverter = $dataConverter;
        return $this;
    }

    /**
     * @param int $userId
     * @param int $auctionId
     * @return string
     */
    protected function getBidderInfo(int $userId, int $auctionId): string
    {
        $bidderInfo = $this->getBidderInfoRenderer()
            ->setUserId($userId)
            ->setAuctionId($auctionId)
            ->enableReadOnlyDb(true)
            ->enableTranslation(true)
            ->render();
        return $bidderInfo;
    }
}
