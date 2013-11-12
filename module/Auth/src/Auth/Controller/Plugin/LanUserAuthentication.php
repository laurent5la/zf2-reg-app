<?php
	namespace Auth\Controller\Plugin;
	
	use Zend\Mvc\Controller\Plugin\AbstractPlugin;
	use Zend\Authentication\AuthenticationService;
	use Zend\ServiceManager\ServiceLocatorInterface;
	
	class LanUserAuthentication extends AbstractPlugin {
	
	    /**
	     * @var AuthenticationService
	     */
	    protected $authService;
	
	    /**
	     * @var ServiceLocator
	     */
	    protected $serviceLocator;
	
	    /**
	     * Gets ServiceLocator instance
	     * @return type ServiceLocator
	     */
	    public function getServiceLocator() {
	        return $this->serviceLocator;
	    }
	
	    /**
	     * Sets ServiceLocator
	     * @param type $serviceLocator
	     */
	    public function setServiceLocator($serviceLocator) {
	        $this->serviceLocator = $serviceLocator;
	    }
	
	    /**
	     * @return bool
	     */
	    public function hasIdentity() {
	        return $this->getAuthService()->hasIdentity();
	    }
	
	    /**
	     * Returns Authentication Service Identity
	     * @return mixed
	     */
	    public function getIdentity() {
	        return $this->getAuthService()->getIdentity();
	    }
	
	    /**
	     * Returns Authentication Service instance from Auth/Module.php
	     * @return AuthenticationService instance
	     */
	    public function getAuthService() {
	        if ($this->authService == null)
	            $this->authService = $this->getServiceLocator()->get('lan_auth_service');
	
	        return $this->authService;
	    }
	
	    /**
	     * Set authService.
	     *
	     * @param AuthenticationService $authService
	     */
	    public function setAuthService(AuthenticationService $authService)
	    {
	    	$this->authService = $authService;
	    	return $this;
	    }
	}