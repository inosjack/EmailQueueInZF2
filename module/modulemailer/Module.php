<?php
/**
 * ModuleMailer
 *
 */

namespace ModuleMailer;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * ModuleMailer Module
 */
class Module implements ConsoleUsageProviderInterface
{

    /**
     * This method is defined in ConsoleUsageProviderInterface
     */
    public function getConsoleUsage(AdapterInterface $console){
        return array(
            'mailer process <name>'             => 'Process queue <name>',
        );
    }
         
    /**
     * Returns module configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ModuleMailer' => function (ServiceManager $sm) {
                    return new Service($sm);
                }
        ));
    }

    /**
     * Returns the Auto loader configuration for the module
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
