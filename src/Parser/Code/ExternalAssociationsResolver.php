<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Variables\Variable;

/**
 * It checks the attributes and the constructor parameters of a class looking for external definitions
 *
 * An external definition is either a class or interface from a third party library, or a built-in
 * class
 *
 * In this case a `ClassDefinition` is added by default.
 * Although we don't really know if it's an interface since we don't have access to the source code
 */
class ExternalAssociationsResolver extends ExternalDefinitionsResolver
{
    protected function resolveForClass(ClassDefinition $definition, Codebase $codebase): void
    {
        parent::resolveForClass($definition, $codebase);
        $this->resolveExternalAttributes($definition, $codebase);
        $this->resolveExternalConstructorParameters($definition, $codebase);
    }

    private function resolveExternalAttributes(ClassDefinition $definition, Codebase $codebase): void
    {
        array_map(function (Attribute $attribute) use ($codebase) {
            if ($attribute->isAReference() && !$codebase->has($attribute->typeName())) {
                $codebase->add($this->externalClass($attribute->typeName()));
            }
        }, $definition->attributes());
    }

    private function resolveExternalConstructorParameters(ClassDefinition $definition, Codebase $codebase): void
    {
        array_map(function (Variable $parameter) use ($codebase) {
            if ($parameter->isAReference() && !$codebase->has($parameter->typeName())) {
                $codebase->add($this->externalClass($parameter->typeName()));
            }
        }, $definition->constructorParameters());
    }
}