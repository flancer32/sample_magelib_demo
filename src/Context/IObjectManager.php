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
     * Configure object manager
     *
     * @param array $configuration
     *
     * @return void
     */
//    public function configure(array $configuration);
}
