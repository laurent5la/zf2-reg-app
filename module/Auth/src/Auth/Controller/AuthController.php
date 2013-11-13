<?php

	namespace Auth\Controller;
	
	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\Authentication\AuthenticationService;
	use Zend\Authentication\Adapter\DbTable as AuthAdapter;
	use Zend\Authentication\Result as Result;
	use Zend\View\Model\ViewModel;
	use Zend\View\Model\JsonModel;
	
	use Auth\Model\Login;
	use Auth\Form\LoginForm;

	class AuthController extends AbstractActionController {
	
	    public $salt;
	    protected $loginTable;
	
	    /**
	     * Returns LoginTable instance from Auth/Module.php
	     * @return LoginTable instance
	     */
	    public function getLoginTable() {
	        if (!$this->loginTable) {
	        	try {
	        		$this->loginTable = $this->getServiceLocator()->get('Auth\Model\LoginTable');
	        	} catch (Exception $e) {
	        		print $e->getMessage(); exit;
	        	}
	        }
	        return $this->loginTable;
	    }
	
	
	    /**
	     * Displays Login Form
	     * @return \Zend\View\Model\ViewModel
	     */
	    public function loginAction() {
	
	        if ($this->LanUserAuthentication()->hasIdentity()) {
	            return $this->redirect()->toRoute('profile');
	        }
	        
	        $messages = array();
	        
	        $form = new LoginForm();
	        
	        $request = $this->getRequest();
	        
	        if ($request->isPost()) {
	        	
	            $login = new Login();
	            
	            // get post data
	            $post = $request->getPost();
	            
	            $form->setInputFilter($login->getInputFilter());
	            
	            $form->setData($post);
	            
	            if ($form->isValid()) {	
	                // get the db adapter
	                $sm = $this->getServiceLocator();
	                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	                // create auth adapter
	                $authAdapter = new AuthAdapter($dbAdapter);
	                // configure auth adapter
	                $authAdapter->setTableName('users')
	                        ->setIdentityColumn('email')
	                        ->setCredentialColumn('password');
	                
	  				// The password stored in the database is of the form md5($credential.$salt):$salt
	                //Find the Salt value from DB for this Username
	                //Method is written in Auth\Model\LoginTable.php
	                $loginTable = $this->getLoginTable();
	                
	                $this->salt = $loginTable->getSalt($post->get('email'));
	                
	                $credential = md5($post->get('password').$this->salt).':'.$this->salt;
	                
	                // pass authentication information to auth adapter
	                $authAdapter->setIdentity($post->get('email'))
	                        	->setCredential($credential);
	                // create auth service and set adapter
	                // auth services provides storage after authenticate
	                $authService = new AuthenticationService();
	                $authService->setAdapter($authAdapter);
	
	                // authenticate
	                $result = $authService->authenticate();
	
	                // check if authentication was successful
	                // if authentication was successful, user information is stored automatically by adapter
	                if ($result->isValid()) {
	                	
	                    //Now check if the User is block
	                    $user_data = $this->getLoginTable()->getUserbyEmail($post->get('email'));
	                    
	                    if ($user_data) {
	                    	
	                    	$this->flashMessenger()->addMessage('You are now logged in.');
	                       	return $this->redirect()->toRoute('registration');
	
	                    } else {
	                    	
	                        $form->setMessages(array(
	                            'email' => array('The Email or Password you entered is incorrect.')
	                        ));
	                        
	                    }
	                    //If for any of the above reason, the authentication is valid but user is inactive
	                    //then reset the auth adapter.
	                    //or else
	                    //upon clicking login button again,
	                    //user will be redirected to the dashboard page directly
	                    //beacause the authService object will be checked in Module.php
	                    //and it will find hasIndentity() to be true.
	                    $authService->clearIdentity();
	
	                } else {
	                    switch ($result->getCode()) {
	                        case Result::FAILURE_IDENTITY_NOT_FOUND:
	                            $form->setMessages(array(
	                                'email' => array('The email you entered is incorrect.')
	                            ));
	                            break;
	
	                        case Result::FAILURE_CREDENTIAL_INVALID:
	                            $form->setMessages(array(
	                                'email' => array('The Email or Password you entered is incorrect.')
	                            ));
	                            break;
	
	                        case Result::SUCCESS:
	                            return $this->redirect()->toRoute('uploader');
	                            break;
	                        default:
	                            $form->setMessages(array(
	                                'email' => array(
	                                    'Unknown Error',
	                                )
	                            ));
	                            break;
	                    }
	                }
	            } // End if ($form->isValid()) {
	        }
	        
	    	if ($this->flashMessenger()->hasMessages()) {
				
				$messages = $this->flashMessenger()->getMessages();
				
				$this->flashMessenger()->clearMessages();
			}
	        
	        $viewModel = new ViewModel(array(
	            'form' => $form,
	        	'messages' => $messages
	        ));
	        
	        return $viewModel;
	    }
	}
