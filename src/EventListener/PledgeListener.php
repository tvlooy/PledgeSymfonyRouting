<?php

namespace Ctors\PledgeRoutingBundle\EventListener;

use Ctors\PledgeRoutingBundle\Attribute\Pledge;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class PledgeListener
{
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        [$controllerObject, $methodName] = $controller;

        // Check for the attribute on the class
        $reflectionClass = new \ReflectionClass($controllerObject);
        $classAttributes = $reflectionClass->getAttributes(Pledge::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($classAttributes as $attribute) {
            /** @var Pledge $pledge */
            $pledge = $attribute->newInstance();

            pledge($pledge->promises, $pledge->execpromises);
        }

        // Check for the attribute on the method
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $methodAttributes = $reflectionMethod->getAttributes(Pledge::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($methodAttributes as $attribute) {
            /** @var Pledge $pledge */
            $pledge = $attribute->newInstance();

            pledge($pledge->promises, $pledge->execpromises);
        }
    }
}
