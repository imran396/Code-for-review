<?php
/**
 * Render menu tabs
 *
 * SAM-9573: Refactor admin secondary menu for v3-6
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 28, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Feature\SettlementCheckFeatureAvailabilityCheckerCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu\AuctionMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BarcodeOperationMenu\BarcodeOperationMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidderLanguageMenu\BidderLanguageMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidIncrementMenu\BidIncrementMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu\BuyerPremiumMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomFieldMenu\CustomFieldMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomTemplateMenu\CustomTemplateMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InventoryMenu\InventoryMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InvoiceMenu\InvoiceMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu\ReportMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu\SettingsMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettlementMenu\SettlementMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\StackedTaxMenu\StackedTaxMenuItemBuilderCreateTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu\SystemParameterMenuItemBuilderCreateTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class MenuTabs
 * @package Sam\View\Admin\ViewHelper
 */
class SecondaryMenuRenderer extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use AuctionMenuItemBuilderCreateTrait;
    use BarcodeOperationMenuItemBuilderCreateTrait;
    use BidIncrementMenuItemBuilderCreateTrait;
    use BidderLanguageMenuItemBuilderCreateTrait;
    use BuyerPremiumMenuItemBuilderCreateTrait;
    use CustomFieldMenuItemBuilderCreateTrait;
    use CustomTemplateMenuItemBuilderCreateTrait;
    use EditorUserAwareTrait;
    use InventoryMenuItemBuilderCreateTrait;
    use InvoiceMenuItemBuilderCreateTrait;
    use ParamFetcherForRouteAwareTrait;
    use ReportMenuItemBuilderCreateTrait;
    use ServerRequestReaderAwareTrait;
    use SettingsMenuItemBuilderCreateTrait;
    use SettlementCheckFeatureAvailabilityCheckerCreateTrait;
    use SettlementMenuItemBuilderCreateTrait;
    use StackedTaxMenuItemBuilderCreateTrait;
    use SystemAccountAwareTrait;
    use SystemParameterMenuItemBuilderCreateTrait;

    private const MENU_TEMPLATE_FILE = 'Internal/Common/view.tpl.php';

    /** @var ?array{
     *     items?: array{label: string, url: string},
     *     position?: string,
     *     class?: string,
     *     show-item-id?: bool
     * }
     */
    protected ?array $menu = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function renderAuctionMenu(): string
    {
        $this->menu = $this->buildAuctionMenu();
        $output = $this->render();
        return $output;
    }

    public function renderBarcodeOperationMenu(): string
    {
        $this->menu = $this->buildBarcodeOperationMenu();
        $output = $this->render();
        return $output;
    }

    public function renderBidderLanguageMenu(): string
    {
        $this->menu = $this->buildBidderLanguageMenu();
        $output = $this->render();
        return $output;
    }

    public function renderBidIncrementMenu(): string
    {
        $this->menu = $this->buildBidIncrementMenu();
        $output = $this->render();
        return $output;
    }

    public function renderBuyerPremiumMenu(): string
    {
        $this->menu = $this->buildBuyerPremiumMenu();
        $output = $this->render();
        return $output;
    }

    public function renderCustomFieldMenu(): string
    {
        $this->menu = $this->buildCustomFieldMenu();
        $output = $this->render();
        return $output;
    }

    public function renderCustomTemplateMenu(): string
    {
        $this->menu = $this->buildCustomTemplateMenu();
        $output = $this->render();
        return $output;
    }

    public function renderInventoryMenu(): string
    {
        $this->menu = $this->buildInventoryMenu();
        $output = $this->render();
        return $output;
    }

    public function renderInvoiceMenu(): string
    {
        $this->menu = $this->buildInvoiceMenu();
        $output = $this->render();
        return $output;
    }

    public function renderReportMenu(): string
    {
        $this->menu = $this->buildReportMenu();
        $output = $this->render();
        return $output;
    }

    public function renderSettlementMenu(int $settlementId): string
    {
        $isSettlementCheckFeatureAvailable = $this->createSettlementCheckFeatureAvailabilityChecker()
            ->isEnabled($this->getSystemAccountId());
        if (!$isSettlementCheckFeatureAvailable) {
            $this->menu = [];
            return '';
        }

        $this->menu = $this->buildSettlementMenu($settlementId);
        $output = $this->render();
        return $output;
    }

    public function renderSettingMenu(): string
    {
        $this->menu = $this->buildSettingMenu();
        $output = $this->render();
        return $output;
    }

    public function renderSystemParameterMenu(): string
    {
        $this->menu = $this->buildSystemParameterMenu();
        $output = $this->render();
        return $output;
    }

    public function renderStackedTaxMenu(): string
    {
        $this->menu = $this->buildStackedTaxMenu();
        return $this->render();
    }

    /**
     * @return string
     */
    protected function render(): string
    {
        if (
            !$this->menu
            || empty($this->menu['items'])
        ) {
            return '';
        }

        $serverRequestReader = $this->getServerRequestReader();
        $left = isset($this->menu['position']) && $this->menu['position'] === SecondaryMenuConstants::POSITION_LEFT;
        $templateData = [
            'customClass' => $this->menu['class'] ?? null,
            'left' => $left,
            'menu' => $this->menu['items'],
            'selectedItem' => $serverRequestReader->scheme() . '://'
                . $serverRequestReader->httpHost()
                . $serverRequestReader->requestUri(),
            'showItemId' => isset($this->menu['show-item-id']) && $this->menu['show-item-id'],
        ];

        $output = HtmlRenderer::new()->getLocalTemplate(self::MENU_TEMPLATE_FILE, $templateData, __DIR__);
        return $output;
    }

    /**
     * build menu
     * 'class':optional
     * 'items':required
     *      'label':required => ['subUrls':optional, 'title':optional, 'url':required]
     * 'position':optional - left|center, default: center
     * 'show-item-id':optional - create id for each item, default: not show
     */

    /**
     * @return array
     */
    protected function buildAuctionMenu(): array
    {
        $auctionId = $this->getParamFetcherForRoute()->getIntPositive(Constants\UrlParam::R_ID);
        return $this->createAuctionMenuItemBuilder()->build($auctionId, $this->getEditorUserId());
    }

    /**
     * @return array
     */
    protected function buildBarcodeOperationMenu(): array
    {
        return $this->createBarcodeOperationMenuItemBuilder()->build();
    }

    /**
     * @return array
     */
    protected function buildBidIncrementMenu(): array
    {
        return $this->createBidIncrementMenuItemBuilder()->build($this->getSystemAccountId());
    }

    /**
     * @return array
     */
    protected function buildBidderLanguageMenu(): array
    {
        $languageId = $this->getParamFetcherForRoute()->getIntPositiveOrZero(Constants\UrlParam::R_ID);
        return $this->createBidderLanguageMenuItemBuilder()->build($languageId);
    }

    /**
     * @return array
     */
    protected function buildBuyerPremiumMenu(): array
    {
        return $this->createBuyerPremiumMenuItemBuilder()->build($this->getSystemAccountId());
    }

    /**
     * @return array
     */
    protected function buildCustomFieldMenu(): array
    {
        return $this->createCustomFieldMenuItemBuilder()->build();
    }

    public function buildCustomTemplateMenu(): array
    {
        return $this->createCustomTemplateMenuItemBuilder()->build();
    }

    /**
     * @return array
     */
    protected function buildInventoryMenu(): array
    {
        return $this->createInventoryMenuItemBuilder()->build();
    }

    protected function buildInvoiceMenu(): array
    {
        return $this->createInvoiceMenuItemBuilder()->build();
    }

    /**
     * @return array
     */
    protected function buildReportMenu(): array
    {
        return $this->createReportMenuItemBuilder()->build($this->getEditorUserId());
    }

    protected function buildSettlementMenu(int $settlementId): array
    {
        return $this->createSettlementMenuItemBuilder()->build($settlementId);
    }

    /**
     * @return array
     */
    protected function buildSettingMenu(): array
    {
        return $this->createSettingsMenuItemBuilder()->build($this->getSystemAccountId(), $this->getEditorUserId());
    }

    /**
     * @return array
     */
    protected function buildSystemParameterMenu(): array
    {
        return $this->createSystemParameterMenuItemBuilder()->build($this->getSystemAccount());
    }

    /**
     * @return array
     */
    protected function buildStackedTaxMenu(): array
    {
        return $this->createStackedTaxMenuItemBuilder()->build();
    }
}
