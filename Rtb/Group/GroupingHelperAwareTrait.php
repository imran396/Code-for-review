<?php

namespace Sam\Rtb\Group;

/**
 * Trait GroupingHelperAwareTrait
 * @package Sam\Rtb\Group
 */
trait GroupingHelperAwareTrait
{
    /**
     * @var GroupingHelper|null
     */
    protected ?GroupingHelper $groupingHelper = null;

    /**
     * @return GroupingHelper
     */
    protected function getGroupingHelper(): GroupingHelper
    {
        if ($this->groupingHelper === null) {
            $this->groupingHelper = GroupingHelper::new();
        }
        return $this->groupingHelper;
    }

    /**
     * @param GroupingHelper $groupingHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setGroupingHelper(GroupingHelper $groupingHelper): static
    {
        $this->groupingHelper = $groupingHelper;
        return $this;
    }
}
