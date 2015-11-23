<?php
/**
 * Interface for Dependency Injection container (is absent in M1).
 * This is copy of the \Magento\Framework\ObjectManagerInterface
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Context;


interface IObjectManager {
    /**
     * Create new object instance
     *
     * @param string $type
     * @param array  $arguments
     *
     * @return mixed
     */
    public function create($type, array $arguments = [ ]);

    /**
     * Retrieve cached object instance
     *
     * @param string $type
     *
     * @return mixed
     */
    public function get($type);

    /**
     * This method is not used cause: Declaration of Zend\Di\Di::configure() must be compatible with
     * Praxigento\Core\Lib\Context\IObjectManager::configure(array $configuration)
     *
     * @param array $configuration
     *
     * @return void
     */
    //    public function configure(array $configuration);
}