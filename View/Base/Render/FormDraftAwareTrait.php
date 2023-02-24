<?php

namespace Sam\View\Base\Render;

/**
 * Trait FormDraftAwareTrait
 * @package Sam\View\Base\Render
 */
trait FormDraftAwareTrait
{
    protected ?FormDraft $formDraft = null;

    /**
     * @return FormDraft
     */
    protected function getFormDraft(): FormDraft
    {
        if ($this->formDraft === null) {
            $this->formDraft = FormDraft::new();
        }
        return $this->formDraft;
    }

    /**
     * @param FormDraft $formDraft
     * @return static
     * @internal
     */
    public function setFormDraft(FormDraft $formDraft): static
    {
        $this->formDraft = $formDraft;
        return $this;
    }
}
