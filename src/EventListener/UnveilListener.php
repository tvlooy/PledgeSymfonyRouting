<?php

namespace Ctors\PledgeRoutingBundle\EventListener;

use Ctors\PledgeRoutingBundle\Attribute\Unveil;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class UnveilListener
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
        $classAttributes = $reflectionClass->getAttributes(Unveil::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($classAttributes as $attribute) {
            /** @var Unveil $unveil */
            $unveil = $attribute->newInstance();

            unveil($unveil->path, $unveil->permissions);
        }

        // Check for the attribute on the method
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $methodAttributes = $reflectionMethod->getAttributes(Unveil::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($methodAttributes as $attribute) {
            /** @var Unveil $unveil */
            $unveil = $attribute->newInstance();

            unveil($unveil->path, $unveil->permissions);
        }
    }
}
