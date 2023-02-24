<?php
/**
 * SAM-7976: Render identifiable css class names for user custom fields on signup, profile, auction registration pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           04-18, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Render\Css;

/**
 * Trait CustomFieldCssClassMakerCreateTrait
 * @package Sam\CustomField\Base\Render\Css
 */
trait CustomFieldCssClassMakerCreateTrait
{
    /**
     * @var CustomFieldCssClassMaker|null
     */
    protected ?CustomFieldCssClassMaker $customFieldCssClassMaker = null;

    /**
     * @return CustomFieldCssClassMaker
     */
    protected function createCustomFieldCssClassMaker(): CustomFieldCssClassMaker
    {
        return $this->customFieldCssClassMaker ?: CustomFieldCssClassMaker::new();
    }

    /**
     * @param CustomFieldCssClassMaker $customFieldCssClassMaker
     * @return $this
     * @internal
     */
    public function setCustomFieldCssClassMaker(CustomFieldCssClassMaker $customFieldCssClassMaker): static
    {
        $this->customFieldCssClassMaker = $customFieldCssClassMaker;
        return $this;
    }
}
