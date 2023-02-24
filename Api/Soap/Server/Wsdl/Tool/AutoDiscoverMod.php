<?php
/**
 * AutoDiscoverMod overrides AutoDiscover::generateClass(), generateWsdl() addFunctionToWsdl() methods to:
 * 1. exclude methods with $this->excludeTag from wsdl.
 * 2. generate <message>'s tags before <portType>, <binding>, <service> as xwsdl-analyzer.com prescribes
 * 3. add documentation for <definitions>, <portType> tags as xwsdl-analyzer.com prescribes
 *
 * Extract addMessagesToWsdl() from addFunctionToWsdl() and call it in generateWsdl() before addFunctionToWsdl()
 *
 * http://forums.zend.com/viewtopic.php?t=113523
 *
 * Project        SOAP Server
 * Filename       Soap12Controller.php
 * @author        Victor Pautoff
 * @version       SVN: $Id: $
 * @since         Dec 26, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Server\Wsdl\Tool;

use Laminas\Code\Reflection\DocBlockReflection;
use Laminas\Server\Reflection;
use Laminas\Server\Reflection\ReflectionMethod;
use Laminas\Soap\AutoDiscover;
use Laminas\Soap\Exception;
use Laminas\Soap\Wsdl;

/**
 * Class AutoDiscoverMod
 * @package Sam\Soap
 */
class AutoDiscoverMod extends AutoDiscover
{
    /** @var string */
    protected string $documentationDefinitions = 'WebService provides information on SAM system.';
    /** @var string */
    protected string $documentationPortType = 'Available operations.';
    /** @var string */
    protected string $excludeTag = 'notAutoDiscoverable';

    /**
     * Generate the WSDL for a service class.
     * @return Wsdl
     */
    protected function generateClass(): Wsdl
    {
        $methods = $this->reflection->reflectClass($this->class)->getMethods();
        foreach ($methods as $key => $method) {
            if (!count((new DocBlockReflection($method->getDocComment() ?: '/***/'))->getTags($this->excludeTag))) {
                continue;
            }
            unset($methods[$key]);
        }
        return $this->generateWsdl($methods);
    }

    /**
     * Generate the WSDL for a set of reflection method instances.
     *
     * @param array $reflectionMethods
     * @return Wsdl
     */
    protected function generateWsdl(array $reflectionMethods): Wsdl
    {
        $uri = $this->getUri();

        $serviceName = $this->getServiceName();

        $wsdl = new $this->wsdlClass($serviceName, $uri, $this->strategy, $this->classMap);
        $wsdl->addDocumentation($wsdl, $this->documentationDefinitions);

        // The wsdl:types element must precede all other elements (WS-I Basic Profile 1.1 R2023)
        $wsdl->addSchemaTypeSection();

        // Add messages first
        foreach ($reflectionMethods as $method) {
            $this->addMessagesToWsdl($method, $wsdl);
        }

        $port = $wsdl->addPortType($serviceName . 'Port');
        $wsdl->addDocumentation($port, $this->documentationPortType);
        $binding = $wsdl->addBinding($serviceName . 'Binding', Wsdl::TYPES_NS . ':' . $serviceName . 'Port');

        $wsdl->addSoapBinding($binding, $this->bindingStyle['style'], $this->bindingStyle['transport']);
        $wsdl->addService(
            $serviceName . 'Service',
            $serviceName . 'Port',
            Wsdl::TYPES_NS . ':' . $serviceName . 'Binding',
            $uri
        );

        foreach ($reflectionMethods as $method) {
            $this->addFunctionToWsdl($method, $wsdl, $port, $binding);
        }

        return $wsdl;
    }

    /**
     * Custom SAM method
     * @param ReflectionMethod $function
     * @param Wsdl $wsdl
     */
    protected function addMessagesToWsdl(ReflectionMethod $function, Wsdl $wsdl): void
    {
        $prototype = $this->getPrototype($function);
        $functionName = $wsdl->translateType($function->getName());

        // Add the input message (parameters)
        $args = [];
        if ($this->bindingStyle['style'] == 'document') {
            // Document style: wrap all parameters in a sequence element
            $sequence = [];
            foreach ($prototype->getParameters() as $param) {
                $sequenceElement = [
                    'name' => $param->getName(),
                    'type' => $wsdl->getType($this->discoveryStrategy->getFunctionParameterType($param))
                ];
                if ($param->isOptional()) {
                    $sequenceElement['nillable'] = 'true';
                }
                $sequence[] = $sequenceElement;
            }

            $element = [
                'name' => $functionName,
                'sequence' => $sequence
            ];

            // Add the wrapper element part, which must be named 'parameters'
            $args['parameters'] = ['element' => $wsdl->addElement($element)];
        } else {
            // RPC style: add each parameter as a typed part
            foreach ($prototype->getParameters() as $param) {
                $args[$param->getName()] = [
                    'type' => $wsdl->getType($this->discoveryStrategy->getFunctionParameterType($param))
                ];
            }
        }
        $wsdl->addMessage($functionName . 'In', $args);

        $isOneWayMessage = $this->discoveryStrategy->isFunctionOneWay($function, $prototype);

        if ($isOneWayMessage == false) {
            // Add the output message (return value)
            $args = [];
            if ($this->bindingStyle['style'] == 'document') {
                // Document style: wrap the return value in a sequence element
                $sequence = [];
                if ($prototype->getReturnType() != "void") {
                    $sequence[] = [
                        'name' => $functionName . 'Result',
                        'type' => $wsdl->getType($this->discoveryStrategy->getFunctionReturnType($function, $prototype))
                    ];
                }

                $element = [
                    'name' => $functionName . 'Response',
                    'sequence' => $sequence
                ];

                // Add the wrapper element part, which must be named 'parameters'
                $args['parameters'] = ['element' => $wsdl->addElement($element)];
            } elseif ($prototype->getReturnType() != "void") {
                // RPC style: add the return value as a typed part
                $args['return'] = [
                    'type' => $wsdl->getType($this->discoveryStrategy->getFunctionReturnType($function, $prototype))
                ];
            }

            $wsdl->addMessage($functionName . 'Out', $args);
        }
    }

    /**
     * Add a function to the WSDL document.
     *
     * @param  $function Reflection\AbstractFunction function to add
     * @param  $wsdl     Wsdl WSDL document
     * @param  $port     \DOMElement wsdl:portType
     * @param  $binding  \DOMElement wsdl:binding
     * @throws Exception\InvalidArgumentException
     */
    protected function addFunctionToWsdl($function, $wsdl, $port, $binding): void
    {
        $uri = $this->getUri();
        $prototype = $this->getPrototype($function);
        $functionName = $wsdl->translateType($function->getName());
        $isOneWayMessage = $this->discoveryStrategy->isFunctionOneWay($function, $prototype);

        // Add the portType operation
        if ($isOneWayMessage == false) {
            $portOperation = $wsdl->addPortOperation(
                $port,
                $functionName,
                Wsdl::TYPES_NS . ':' . $functionName . 'In',
                Wsdl::TYPES_NS . ':' . $functionName . 'Out'
            );
        } else {
            $portOperation = $wsdl->addPortOperation(
                $port,
                $functionName,
                Wsdl::TYPES_NS . ':' . $functionName . 'In'
            );
        }
        $desc = $this->discoveryStrategy->getFunctionDocumentation($function);

        if ($desc !== '') {
            $wsdl->addDocumentation($portOperation, $desc);
        }

        // When using the RPC style, make sure the operation style includes a 'namespace'
        // attribute (WS-I Basic Profile 1.1 R2717)
        $operationBodyStyle = $this->operationBodyStyle;
        if ($this->bindingStyle['style'] == 'rpc' && !isset($operationBodyStyle['namespace'])) {
            $operationBodyStyle['namespace'] = '' . $uri;
        }

        // Add the binding operation
        if ($isOneWayMessage == false) {
            $operation = $wsdl->addBindingOperation($binding, $functionName, $operationBodyStyle, $operationBodyStyle);
        } else {
            $operation = $wsdl->addBindingOperation($binding, $functionName, $operationBodyStyle);
        }
        $wsdl->addSoapOperation($operation, $uri . '#' . $functionName);
    }

    /**
     * We only support one prototype: the one with the maximum number of arguments
     * @param $function
     * @return Reflection\Prototype
     */
    protected function getPrototype($function): Reflection\Prototype
    {
        $prototype = null;
        $maxNumArgumentsOfPrototype = -1;
        foreach ($function->getPrototypes() as $tmpPrototype) {
            $numParams = count($tmpPrototype->getParameters());
            if ($numParams > $maxNumArgumentsOfPrototype) {
                $maxNumArgumentsOfPrototype = $numParams;
                $prototype = $tmpPrototype;
            }
        }
        if ($prototype === null) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'No prototypes could be found for the "%s" function',
                    $function->getName()
                )
            );
        }
        return $prototype;
    }
}
