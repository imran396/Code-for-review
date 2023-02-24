<?php
/**
 * SAM-5583: Refactor data loader for Assign-ready item list at Auction Lot List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Filter;

use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\AssignReady\AssignReadyLotListConstants;

/**
 * Class AssignReadyOrderingControlHelper
 * @package
 */
class AssignReadyOrderingControlDataProvider extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use LotCustomFieldTranslationManagerAwareTrait;
    use LotCustomFieldsAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Map order field alias to name in column header and order choice list
     * @var string[]
     */
    protected array $assignReadyOrderFieldNames = [
        AssignReadyLotListConstants::ORD_CONSIGNOR => 'Cons.',
        AssignReadyLotListConstants::ORD_CREATED_ON => 'Date Added',
        AssignReadyLotListConstants::ORD_EDITOR => 'Creator/ Modified',
        AssignReadyLotListConstants::ORD_ESTIMATE => 'Est.',
        AssignReadyLotListConstants::ORD_HAMMER_PRICE => 'Price',
        AssignReadyLotListConstants::ORD_ITEM_NO => 'Id',
        AssignReadyLotListConstants::ORD_LOT_NAME => 'Name',
        AssignReadyLotListConstants::ORD_RECENT_AUCTION => 'Auction #',
        AssignReadyLotListConstants::ORD_STARTING_BID => 'Start',
        AssignReadyLotListConstants::ORD_WINNER => 'Winner',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     */
    public function construct(): static
    {
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            $alias = $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
            $customValueLangKey = $this->getLotCustomFieldTranslationManager()->makeKeyForName($lotCustomField->Name);
            $name = $this->getTranslator()->translate($customValueLangKey, 'customfields');
            $this->assignReadyOrderFieldNames[$alias] = $name;
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAssignReadyOrderFieldNames(): array
    {
        return $this->assignReadyOrderFieldNames;
    }

    /**
     * @param string $alias
     * @return string
     */
    public function getNameByAlias(string $alias): string
    {
        return $this->assignReadyOrderFieldNames[$alias];
    }
}
