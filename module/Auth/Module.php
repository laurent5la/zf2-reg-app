<?php
	
	namespace Auth;
	
	use Zend\ModuleManager\ModuleManager;
	use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
	use Zend\ModuleManager\Feature\ConfigProviderInterface;
	use Zend\ModuleManager\Feature\ServiceProviderInterface;
	
	use Zend\Mvc\ModuleRouteListener;
	use Zend\Mvc\RouteListener;
	use Zend\Mvc\MvcEvent;
	
	use Zend\ServiceManager\ServiceLocatorInterface;
	
	use Zend\Stdlib\Hydrator\ClassMethods;
	
	use Zend\Authentication\AuthenticationService;
	use Zend\Authentication\Adapter\DbTable as AuthAdapter;
	
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;
	
	use Auth\Controller\Plugin\LanUserAuthentication;
	use Auth\Model\LoginTable;
	use Auth\Model\Login;

	class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{
	
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
	    
	    public function getControllerPluginConfig()
	    {
	    	return array(
	    			'factories' => array(
	    					'LanUserAuthentication' => function ($sm) {
	    						$serviceLocator = $sm->getServiceLocator();
	    						$authService = $serviceLocator->get('lan_auth_service');
	    						$controllerPlugin = new Controller\Plugin\LanUserAuthentication;
	    						$controllerPlugin->setAuthService($authService);
	    						return $controllerPlugin;
	    					},
	    			),
	    	);
	    }
	
	    function getServiceConfig() {
	    	return array(
	    		'factories' => array(
	    				'Auth\Model\LoginTable' => function($sm) {
	    					$tableGateway = $sm->get('LoginTableGateway');
	    					$table = new LoginTable($tableGateway);
	    					return $table;
	    				},
	    				'LoginTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Login());
	    					return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
	    				},
	    				'lan_auth_service' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					// create auth adapter
	    					$authAdapter = new AuthAdapter($dbAdapter);
	    				
	    					// configure auth adapter
	    					$authAdapter->setTableName('users')
	    						->setIdentityColumn('email')
	    						->setCredentialColumn('password');
	    				
	    					$auth = new AuthenticationService();
	    					$auth->setAdapter($authAdapter);
	    				
	    					return $auth;
	    				},
	    		)
	    	);
	    }
	}
