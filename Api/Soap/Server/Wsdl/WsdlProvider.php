<?php

namespace Sam\Api\Soap\Server\Wsdl;

use Laminas\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence;
use Laminas\Soap\Wsdl\ComplexTypeStrategy\Composite;
use Sam\Api\Soap\Front\SoapApiWrapper;
use Sam\Api\Soap\Server\Wsdl\Tool\AutoDiscoverMod;
use Sam\Api\Soap\Server\Wsdl\Tool\CustomFieldsComplexType;
use Sam\Core\Service\CustomizableClass;

/**
 */
class WsdlProvider extends CustomizableClass
{
    /**
     * Returns an instance of Service
     *
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Get the Soap12 Api wsdl structure
     *
     * @param string $wsdlUri
     * @return bool
     */
    public function getWsdl(string $wsdlUri): bool
    {
        $strategy = new Composite(
            [
                'string[]' => new ArrayOfTypeSequence(),
                'integer[]' => new ArrayOfTypeSequence(),
                'float[]' => new ArrayOfTypeSequence(),
            ], new CustomFieldsComplexType()
        );

        $wsdl = (new AutoDiscoverMod())
            ->setBindingStyle(['style' => 'rpc'])
            // Set body style first before setting a class
            ->setOperationBodyStyle(['use' => 'literal', 'namespace' => null])
            ->setClass(SoapApiWrapper::class)
            ->setUri($wsdlUri)
            ->setComplexTypeStrategy($strategy)
            ->generate();

        header('Content-Type: text/xml');
        echo $wsdl->toXML();
        return true;
    }
}
