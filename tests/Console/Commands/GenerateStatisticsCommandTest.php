<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Console\Commands;

use PHPUnit\Framework\TestCase;
use PhUml\Console\PhUmlApplication;
use PhUml\Console\ProgressDisplay;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateStatisticsCommandTest extends TestCase
{
    /** @before */
    function configureCommandTester()
    {
        $application = new PhUmlApplication(new ProgressDisplay());
        $this->command = $application->find('phuml:statistics');
        $this->tester = new CommandTester($this->command);
        $this->statistics = __DIR__ . '/../../.output/statistics.txt';
        if (file_exists($this->statistics)) {
            unlink($this->statistics);
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
            'output' => $this->statistics,
        ]);
    }

    /** @test */
    function it_generates_the_statistics_of_a_given_directory()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../.code',
            'output' => $this->statistics,
            '--recursive' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->statistics);
    }

    /** @var GenerateStatisticsCommand */
    private $command;

    /** @var CommandTester */
    private $tester;

    /** @var string */
    private $statistics;
}
