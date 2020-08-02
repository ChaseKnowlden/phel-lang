<?php

declare(strict_types=1);

namespace Phel\Emitter\NodeEmitter;

use Phel\Ast\DefNode;
use Phel\Ast\Node;
use Phel\Emitter\NodeEmitter;

final class DefEmitter implements NodeEmitter
{
    use WithOutputEmitter;

    public function emit(Node $node): void
    {
        assert($node instanceof DefNode);

        $this->outputEmitter->emitGlobalBase($node->getNamespace(), $node->getName());
        $this->outputEmitter->emitStr(' = ', $node->getStartSourceLocation());
        $this->outputEmitter->emitNode($node->getInit());
        $this->outputEmitter->emitLine(';', $node->getStartSourceLocation());

        if (count($node->getMeta()) > 0) {
            $this->outputEmitter->emitGlobalBaseMeta($node->getNamespace(), $node->getName());
            $this->outputEmitter->emitStr(' = ', $node->getStartSourceLocation());
            $this->outputEmitter->emitLiteral($node->getMeta());
            $this->outputEmitter->emitLine(';', $node->getStartSourceLocation());
        }
    }
}
