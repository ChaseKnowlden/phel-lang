<?php

declare(strict_types=1);

namespace Phel\Command;

use Phel\Command\Format\PathFilterInterface;
use Phel\Command\Format\PhelPathFilter;
use Phel\Command\Repl\ColorStyle;
use Phel\Command\Repl\ReplCommandSystemIo;
use Phel\Command\Shared\CommandIoInterface;
use Phel\Command\Shared\CommandSystemIo;
use Phel\Command\Shared\NamespaceExtractor;
use Phel\Command\Shared\NamespaceExtractorInterface;
use Phel\Compiler\Analyzer\Environment\GlobalEnvironmentInterface;
use Phel\Compiler\CompilerFactoryInterface;
use Phel\Exceptions\TextExceptionPrinter;
use Phel\Formatter\FormatterFactoryInterface;
use Phel\Printer\Printer;
use Phel\Runtime\RuntimeInterface;

final class CommandFactory implements CommandFactoryInterface
{
    private string $currentDir;
    private CompilerFactoryInterface $compilerFactory;
    private FormatterFactoryInterface $formatterFactory;

    public function __construct(
        string $currentDir,
        CompilerFactoryInterface $compilerFactory,
        FormatterFactoryInterface $formatterFactory
    ) {
        $this->currentDir = $currentDir;
        $this->compilerFactory = $compilerFactory;
        $this->formatterFactory = $formatterFactory;
    }

    public function createReplCommand(RuntimeInterface $runtime): ReplCommand
    {
        $runtime->loadFileIntoNamespace('user', __DIR__ . '/Repl/startup.phel');

        return new ReplCommand(
            new ReplCommandSystemIo($this->currentDir . '.phel-repl-history'),
            $this->compilerFactory->createEvalCompiler($runtime->getEnv()),
            TextExceptionPrinter::create(),
            ColorStyle::withStyles(),
            Printer::nonReadableWithColor()
        );
    }

    public function createRunCommand(RuntimeInterface $runtime): RunCommand
    {
        return new RunCommand(
            $runtime,
            $this->createNamespaceExtractor($runtime->getEnv())
        );
    }

    public function createTestCommand(RuntimeInterface $runtime): TestCommand
    {
        return new TestCommand(
            $this->currentDir,
            $runtime,
            $this->createNamespaceExtractor($runtime->getEnv()),
            $this->compilerFactory->createEvalCompiler($runtime->getEnv())
        );
    }

    public function createFormatCommand(): FormatCommand
    {
        return new FormatCommand(
            $this->formatterFactory->createFormatter(),
            $this->createCommandIo(),
            $this->createPathFilter(),
            TextExceptionPrinter::create()
        );
    }

    private function createNamespaceExtractor(GlobalEnvironmentInterface $globalEnv): NamespaceExtractorInterface
    {
        return new NamespaceExtractor(
            $this->compilerFactory->createLexer(),
            $this->compilerFactory->createParser(),
            $this->compilerFactory->createReader($globalEnv),
            $this->createCommandIo()
        );
    }

    private function createCommandIo(): CommandIoInterface
    {
        return new CommandSystemIo();
    }

    private function createPathFilter(): PathFilterInterface
    {
        return new PhelPathFilter();
    }
}
