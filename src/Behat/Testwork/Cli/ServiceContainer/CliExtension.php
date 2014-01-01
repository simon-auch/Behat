<?php

/*
 * This file is part of the Behat Testwork.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Testwork\Cli\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ServiceProcessor;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Testwork cli extension.
 *
 * Provides console services for testwork.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class CliExtension implements Extension
{
    /*
     * Available services
     */
    const COMMAND_ID = 'cli.command';
    const INPUT_ID = 'cli.input';
    const OUTPUT_ID = 'cli.output';

    /*
     * Available extension points
     */
    const CONTROLLER_TAG = 'cli.controller';

    /**
     * @var ServiceProcessor
     */
    private $processor;

    /**
     * Initializes extension.
     *
     * @param null|ServiceProcessor $processor
     */
    public function __construct(ServiceProcessor $processor = null)
    {
        $this->processor = $processor ?: new ServiceProcessor();
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'cli';
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadCommand($container);
    }

    /**
     * Processes shared container after all extensions loaded.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->processControllers($container);
    }

    /**
     * Loads application command.
     *
     * @param ContainerBuilder $container
     */
    protected function loadCommand(ContainerBuilder $container)
    {
        $definition = new Definition('Behat\Testwork\Cli\Command', array('%cli.command.name%', array()));
        $container->setDefinition(self::COMMAND_ID, $definition);
    }

    /**
     * Processes all controllers in container.
     *
     * @param ContainerBuilder $container
     */
    protected function processControllers(ContainerBuilder $container)
    {
        $references = $this->processor->findAndSortTaggedServices($container, self::CONTROLLER_TAG);
        $container->getDefinition(self::COMMAND_ID)->replaceArgument(1, $references);
    }
}
