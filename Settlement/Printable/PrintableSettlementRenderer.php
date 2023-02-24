<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-26, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable;

use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settlement\Printable\Internal\HtmlSections\HtmlSectionsRenderer;
use Sam\Storage\Entity\AwareTrait\SettlementAwareTrait;
use Settlement;

/**
 * Class SettlementPrintableService
 * @package Sam\Settlement\Printable
 *
 * @method Settlement getSettlement() - we check settlement existence at controller layer
 */
class PrintableSettlementRenderer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use OptionalsTrait;
    use SettlementAwareTrait;

    // --- Incoming values ---

    /**
     * @var string (bool value)
     *
     * We need to enable translatable labels for Public and Email contexts of usage this service.
     * Please see usage cases.
     * We disabled translatable labels for Admin context at dev@35029
     */
    public const OP_TRANSLATE_LABELS = 'translateLabels'; // bool
    /**
     * @var string (bool value)
     * We don't need to render lot item custom fields (for settlement lots table) at
     * a. public area (/my-settlements/print/id/<SettlementId>), because we do not render them for regular viewable layout (/my-settlements/view/id/<SettlementId>)
     * b. email 'settlement_html' placeholder
     */
    public const OP_DISPLAY_LOT_CUSTOM_FIELDS = 'displayLotCustomFields'; // bool

    /**
     * We have some rendering issues (from email clients side, not in our code!) where email clients clear many CSS class names (with related css code).
     * For such cases we need to use inline css only applied to certain HTML element!
     * And this option will help us to know that we should do this.
     */
    public const OP_IS_INLINE_CSS = 'isInlineCss'; // bool

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
     * Construct renderer for admin area scope.
     * @param array $optionals
     * @return $this
     */
    public function constructForAdminWeb(array $optionals = []): static
    {
        $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS] = $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS]
            ?? $this->cfg()->get('core->settlements->view->admin->lotCustomField->enabled');
        $optionals[self::OP_TRANSLATE_LABELS] = $optionals[self::OP_TRANSLATE_LABELS]
            ?? $this->cfg()->get('core->settlements->view->admin->translation->enabled');
        return $this->construct($optionals);
    }

    /**
     * Construct renderer for public area scope. (Where we disable rendering for lot custom fields)
     * @param array $optionals
     * @return $this
     */
    public function constructForResponsiveWeb(array $optionals = []): static
    {
        $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS] = $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS]
            ?? $this->cfg()->get('core->settlements->view->responsive->lotCustomField->enabled');
        $optionals[self::OP_TRANSLATE_LABELS] = $optionals[self::OP_TRANSLATE_LABELS]
            ?? $this->cfg()->get('core->settlements->view->responsive->translation->enabled');
        return $this->construct($optionals);
    }

    /**
     * Construct renderer for email scope.
     * @param array $optionals
     * @return $this
     */
    public function constructForEmail(array $optionals = []): static
    {
        $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS] = $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS]
            ?? $this->cfg()->get('core->settlements->view->email->lotCustomField->enabled');
        $optionals[self::OP_TRANSLATE_LABELS] = $optionals[self::OP_TRANSLATE_LABELS]
            ?? $this->cfg()->get('core->settlements->view->email->translation->enabled');
        $optionals[self::OP_IS_INLINE_CSS] = true;
        return $this->construct($optionals);
    }

    /**
     * @param int[] $settlementIds
     * @return string
     */
    public function render(array $settlementIds): string
    {
        return $this->processRender($settlementIds);
    }

    /**
     * @param array $settlementIds
     * @return string
     */
    protected function processRender(array $settlementIds): string
    {
        $countItems = count($settlementIds);
        if (!$countItems) {
            return '';
        }

        $output = '';
        $isRenderLotItemCustomFields = (bool)$this->fetchOptional(self::OP_DISPLAY_LOT_CUSTOM_FIELDS);
        $lotCustomFields = $isRenderLotItemCustomFields
            ? $this->createLotCustomFieldLoader()->loadInSettlements()
            : [];
        foreach ($settlementIds as $settlementId) {
            if ($this->initSettlementData($settlementId)) {
                $singleSettlementHtml = $this->renderSingleSettlementHtml($lotCustomFields);
                if ($countItems === 1) {
                    $output = $singleSettlementHtml;
                } else {
                    $output .= <<<HTML
<div class="multi-print-wrapper">{$singleSettlementHtml}</div>
<div class="clear page-break"></div>
HTML;
                }
            }
        }
        return $output;
    }

    /**
     * @param int $settlementId
     * @return bool
     */
    protected function initSettlementData(int $settlementId): bool
    {
        $this->setSettlementId($settlementId);
        $settlement = $this->getSettlement();
        if (!$settlement) { // @phpstan-ignore-line
            $message = 'Available settlement not found when rendering printable view '
                . composeSuffix(['settlementId' => $settlementId]);
            log_error($message);
            return false;
        }
        return true;
    }

    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return string
     */
    protected function renderSingleSettlementHtml(array $lotCustomFields): string
    {
        $settlement = $this->getSettlement();
        $sectionsRenderer = HtmlSectionsRenderer::new()->construct($settlement);
        $isUseTranslatableLabels = (bool)$this->fetchOptional(self::OP_TRANSLATE_LABELS);
        $isRenderLotItemCustomFields = (bool)$this->fetchOptional(self::OP_DISPLAY_LOT_CUSTOM_FIELDS);
        $isInlineCss = $this->fetchOptional(self::OP_IS_INLINE_CSS);

        $html = $sectionsRenderer->render(
            $lotCustomFields,
            $isUseTranslatableLabels,
            $isRenderLotItemCustomFields,
            $isInlineCss
        );
        return $html;
    }

    /**
     * @param array $optionals
     */
    private function initOptionals(array $optionals): void
    {
        $optionals[self::OP_TRANSLATE_LABELS] = $optionals[self::OP_TRANSLATE_LABELS] ?? false;
        $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS] = $optionals[self::OP_DISPLAY_LOT_CUSTOM_FIELDS] ?? false;
        $optionals[self::OP_IS_INLINE_CSS] = $optionals[self::OP_IS_INLINE_CSS] ?? false;
        $this->setOptionals($optionals);
    }
}
