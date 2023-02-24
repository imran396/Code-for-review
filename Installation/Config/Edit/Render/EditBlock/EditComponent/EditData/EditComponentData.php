<?php
/**
 * Temporary value object for edit component data
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/6/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;

/**
 * Class EditComponentData
 * @package
 */
final class EditComponentData
{
    private string $inputId;

    private InputDataWeb $inputData;

    private ?string $buildType;

    private ?string $labelHtml;

    private ?string $descriptionHtml;

    private ?string $infoHtml;

    private ?string $errorHtml;

    private ?string $inputName;

    public function __construct(
        string $inputId,
        InputDataWeb $inputData,
        ?string $buildType,
        ?string $labelHtml = null,
        ?string $descriptionHtml = null,
        ?string $infoHtml = null,
        ?string $errorHtml = null,
        ?string $inputName = null
    ) {
        // Protect class invariants
        $inputId = trim($inputId);
        if (!$inputId) { // protects against '' and '0'
            throw new \InvalidArgumentException('Input id not defined');
        }
        $buildTypeNormalized = null;
        if ($buildType !== null) {
            $buildTypeNormalized = Cast::toString($buildType, Constants\Installation::EDIT_COMPONENT_DATA_BUILD_TYPES);
            if ($buildTypeNormalized === null) {
                throw new \InvalidArgumentException(
                    'Invalid value for build type argument'
                    . composeSuffix(['value' => $buildType])
                );
            }
        }

        // Initialize object
        $this->inputId = $inputId;
        $this->inputData = $inputData;
        $this->buildType = $buildTypeNormalized;
        $this->labelHtml = $labelHtml;
        $this->descriptionHtml = $descriptionHtml;
        $this->infoHtml = $infoHtml;
        $this->errorHtml = $errorHtml;
        $this->inputName = $inputName;
    }

    /**
     * @return string
     */
    public function getInputId(): string
    {
        return $this->inputId;
    }

    /**
     * @return InputDataWeb
     */
    public function getInputData(): InputDataWeb
    {
        return $this->inputData;
    }

    /**
     * @return string|null
     */
    public function getBuildType(): ?string
    {
        return $this->buildType;
    }

    /**
     * @param string $buildType
     * @return self
     */
    public function withBuildType(string $buildType): self
    {
        $ecData = new self(
            $this->getInputId(),
            $this->getInputData(),
            $buildType,
            $this->getLabelHtml(),
            $this->getDescriptionHtml(),
            $this->getInfoHtml(),
            $this->getErrorHtml(),
            $this->getInputName()
        );
        return $ecData;
    }

    /**
     * @return string|null
     */
    public function getLabelHtml(): ?string
    {
        return $this->labelHtml;
    }

    /**
     * @return string|null
     */
    public function getDescriptionHtml(): ?string
    {
        return $this->descriptionHtml;
    }

    /**
     * @return string|null
     */
    public function getInfoHtml(): ?string
    {
        return $this->infoHtml;
    }

    /**
     * @return string|null
     */
    public function getErrorHtml(): ?string
    {
        return $this->errorHtml;
    }

    /**
     * @return string|null
     */
    public function getInputName(): ?string
    {
        return $this->inputName;
    }
}
