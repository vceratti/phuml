<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Console\Commands;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateClassDiagramCommandTest extends TestCase 
{
    /** @before */
    function configureCommandTester()
    {
        $application = new Application();
        $this->command = new GenerateClassDiagramCommand();
        $application->add($this->command);
        $this->tester = new CommandTester($this->command);
        $this->diagram = __DIR__ . '/../../.output/out.png';
        if (file_exists($this->diagram)) {
            unlink($this->diagram);
        }
    }

    /** @test */
    function it_fails_to_execute_if_either_directory_or_output_arguments_are_missing()
    {
        $this->expectException(RuntimeException::class);

        $this->tester->execute([
            'command' => $this->command->getName()
        ]);
    }

    /** @test */
    function it_fails_to_generate_a_diagram_if_directory_with_classes_does_not_exist()
    {
        $this->expectException(RuntimeException::class);

        $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => 'invalid-directory',
            'output' => $this->diagram,
        ]);
    }

    /** @test */
    function it_fails_to_generate_a_diagram_if_no_processor_is_specified()
    {
        $this->expectException(RuntimeException::class);

        $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../.code/classes',
            'output' => $this->diagram,
        ]);
    }

    /** @test */
    function it_generates_a_class_diagram_without_searching_recursively_for_classes()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../.code/classes',
            'output' => $this->diagram,
            '--processor' => 'dot',
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->diagram);
    }

    /** @test */
    function it_generates_a_class_diagram_searching_for_files_recursively()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../.code',
            'output' => $this->diagram,
            '--recursive' => true,
            '--processor' => 'neato',
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->diagram);
    }

    /** @var GenerateClassDiagramCommand */
    private $command;

    /** @var CommandTester */
    private $tester;

    /** @var string */
    private $diagram;
}