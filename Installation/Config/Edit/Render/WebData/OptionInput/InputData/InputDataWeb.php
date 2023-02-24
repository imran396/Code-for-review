<?php
/**
 * Value object stores web-ready input data.
 * SAM-4886?: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData;

use RuntimeException;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\Validation\OptionInputValidationWebData;

/**
 * Class InputDataWeb
 * @package Sam\Installation\Config
 */
class InputDataWeb
{
    protected mixed $value;

    protected ?InputDataWebValueStructured $valueStructured = null;

    protected mixed $valueDefault = null;

    protected ?Descriptor $metaDescriptor;

    protected string $label;

    protected ?OptionInputValidationWebData $validation = null;

    /**
     * InputDataWeb constructor.
     * @param $value
     * @param Descriptor|null $metaDescriptor
     * @param string $label
     */
    public function __construct(mixed $value, ?Descriptor $metaDescriptor, string $label)
    {
        $this->value = $value;
        $this->metaDescriptor = $metaDescriptor;
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return InputDataWebValueStructured|null
     */
    public function getValueStructured(): ?InputDataWebValueStructured
    {
        return $this->valueStructured;
    }

    /**
     * @param InputDataWebValueStructured $valueStructured
     * @return $this
     */
    public function setValueStructured(InputDataWebValueStructured $valueStructured): static
    {
        $this->valueStructured = $valueStructured;
        return $this;
    }

    /**
     * @return Descriptor
     * @throws RuntimeException
     */
    public function getMetaDescriptor(): Descriptor
    {
        if ($this->metaDescriptor === null) {
            $message = sprintf('%s does not contain meta descriptor!', self::class);
            log_error($message);
            throw new RuntimeException($message);
        }

        return $this->metaDescriptor;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return OptionInputValidationWebData
     */
    public function getValidation(): OptionInputValidationWebData
    {
        return $this->validation;
    }

    /**
     * @param OptionInputValidationWebData $validation
     * @return $this
     */
    public function setValidation(OptionInputValidationWebData $validation): static
    {
        $this->validation = $validation;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function hasLocalConfigValue(): ?bool
    {
        return $this->getMetaDescriptor()->hasLocalValue();
    }

    /**
     * @return mixed
     */
    public function getValueDefault(): mixed
    {
        return $this->valueDefault;
    }

    /**
     * @param mixed $valueDefault
     * @return $this
     */
    public function setValueDefault(mixed $valueDefault): static
    {
        $this->valueDefault = $valueDefault;
        return $this;
    }
}
