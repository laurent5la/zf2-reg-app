<?php

	namespace Application;
	
	use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
	use Zend\ModuleManager\Feature\ConfigProviderInterface;
	use Zend\ModuleManager\Feature\ServiceProviderInterface;
	
	use Zend\Mvc\ModuleRouteListener;
	use Zend\Mvc\MvcEvent;
	
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;
	
	use Application\Model\User;
	use Application\Model\UserTable;

	class Module implements AutoloaderProviderInterface, ConfigProviderInterface {
	
	    /**
	     * Returns the config array from the below included file.
	     * @return type
	     */
	    public function getConfig() {
	        return include __DIR__ . '/config/module.config.php';
	    }
	
	    
	    public function getAutoloaderConfig() {
	        return array(
	            'Zend\Loader\ClassMapAutoloader' => array(
	                __DIR__ . '/autoload_classmap.php',
	            ),
	            'Zend\Loader\StandardAutoloader' => array(
	                'namespaces' => array(
	                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
	                ),
	            ),
	        );
	    }
	    
	    function getServiceConfig() {
	    	return array(
    			'factories' => array(
    					'Application\Model\UserTable' => function($sm) {
    						$tableGateway = $sm->get('UserTableGateway');
    						$table = new UserTable($tableGateway);
    						return $table;
    					},
    					'UserTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new User());
    						return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
    					},
    			)
	    	);
	    }
	}
