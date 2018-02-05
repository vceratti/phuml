<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\TypeDeclaration;

class StaticMethod extends Method
{
    public function __construct(
        string $name,
        Visibility $modifier,
        array $parameters = [],
        TypeDeclaration $returnType
    ) {
        parent::__construct($name, $modifier, $parameters, $returnType);
        $this->isStatic = true;
    }
}
