<?php

	namespace Application\Controller;
	
	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\EventManager\EventManagerInterface;
	use Zend\View\Model\ViewModel;
	use Zend\View\Model\JsonModel;
	use Zend\Session\Container;
	use Zend\Validator\Db\NoRecordExists;
	
	use Application\Form\RegistrationForm;
	use Application\Model\User;
	
	class IndexController extends AbstractActionController {
		
		/**
		 * @var Container
		 */
		protected $sessionContainer;
		
		public function __construct()
		{
			$this->sessionContainer = new Container('registration');
		}
	
	    public function indexAction()
		{
			if ($this->LanUserAuthentication()->hasIdentity())
				return $this->redirect()->toRoute('profile');
			else
				return $this->redirect()->toRoute('login');
		}
		
		public function registrationAction()
		{
			$messages = array();
			
			if (!$this->LanUserAuthentication()->hasIdentity())
			{
				return $this->redirect()->toRoute('login');
			}
			
			$email = $this->LanUserAuthentication()->getIdentity();

			$sm = $this->getServiceLocator();
			
			$userTable = $sm->get('Application\Model\UserTable');

			$userdata = $userTable->getUserByEmail($email);

			if (!$userdata)
			{
				## user logged in not identified
				## loggout user
				$this->LanUserAuthentication()->getAuthService()->clearIdentity();
				
				return $this->redirect()->toRoute('login');
			}
			
			if ($this->flashMessenger()->hasMessages()) {
				
				$messages = $this->flashMessenger()->getMessages();
				
				$this->flashMessenger()->clearMessages();
			}
			
			$form = new RegistrationForm();

			$userdata->password = ''; ## remove password info from form
			
			$form->bind($userdata);
				 
			$request = $this->getRequest();
				 
			if ($request->isPost()) {
					
				$user = new User();
					
		        $form->setInputFilter($user->getInputFilter());
		
		        $form->setData($request->getPost());
		
		        if ($form->isValid()) {
		            	
		        	$user->exchangeArray($request->getPost());
		                
		            $errors = array();
		            
		            $flag_new_email = 0;
		            
		            ## if the email was changed user first verify the email is not already in the database and then user need to logging again
		            if ($email != $user->email)
		            {
		            	// get the db adapter
		            	$sm = $this->getServiceLocator();
		            	
		            	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		            	
		            	$validator = new NoRecordExists(
		            			
		            			array(
		            				'table'   => 'la_users',
		            				'field'   => 'email',
		            				'adapter' => $dbAdapter
		            			)
		            			
		            	);
		            	
		            	if ($validator->isValid($user->email)) {
		            		
		            		$operation = $userTable->save($user);
		            		
		            		if (isset($operation['success']) && ($operation['success']))
		            		{
		            		
		            			$this->flashMessenger()->addMessage("User info successfully saved.");
		            			
		            			$errors['email'] = array("User info successfully saved.");
		            			
		            			if (isset($operation['message']) && (strlen($operation['message'])))
		            			{
		            			
		            				$this->flashMessenger()->addMessage($operation['message']);
		            				
		            				$errors['email'] = array($operation['message']);
		            			
		            			}
		            			
		            			if (!empty($_FILES)) {
				 
			            			if ($this->uploadAvatar($user, $_FILES['image']['tmp_name'], $_FILES['image']['name']))
									{
										
										$this->flashMessenger()->addMessage("You need to login with the new email address");
										
										$this->LanUserAuthentication()->getAuthService()->clearIdentity();
		                		 
		                				return $this->redirect()->toRoute('login');
										
									} else {
										
										$errors['image'] = array('Error while creating Avatar filename '. $_FILES['image']['tmp_name']);
										
									}
									
		            			} else {
								
							   		$errors['image'] = array('Error while creating Avatar');

								}
		            			 
		            		} else {
		            			
		            			$errors['email'] = array('Error saving user information');
		            			
		            		}
		            		
		            	} else {
		            		
		            		$errors['email'] = array('Email already in the database');
		            	}	
		            		 
		            } else {

			            $operation = $userTable->save($user);
		            		
		            	if (isset($operation['success']) && ($operation['success']))
		            	{
		            		
		            		$this->flashMessenger()->addMessage("User info successfully saved.");
		            		
		            		$errors['email'] = array("User info successfully saved.");
	
		            		if (isset($operation['message']) && (strlen($operation['message'])))
		            		{
		            			 
		            			$this->flashMessenger()->addMessage($operation['message']);
		            			
		            			$errors['email'] = array($operation['message']);
		            			 
		            		}
		            
			            	if (!empty($_FILES)) {
								
								if ($this->uploadAvatar($user, $_FILES['image']['tmp_name'], $_FILES['image']['name']))
								{
									
									return $this->redirect()->toRoute('profile');
									
								} else {
									
									$errors['image'] = array('Error while creating Avatar filename '. $_FILES['image']['tmp_name']);
									
								}
								
	            			} else {
							
						   		$errors['image'] = array('Error while creating Avatar');

							}
			            
			            } else {
			            	
			            	$message = (isset($operation['message'])) ? $operation['message'] : 'Error saving user information (first name, last name, password)';
			            	
			            	$errors['email'] = array($message);
			            	
			            }
		                	
		            }

		            $form->setMessages($errors);
		            
		        }	
			}
				
			$view = new ViewModel(
						
						array(
								'title' => 'Registration',
								'form'  => $form,
								'messages' => $messages
						)
						
				);
				return $view;
			
		}
		
		public function profileAction()
		{
			if (!$this->LanUserAuthentication()->hasIdentity())
			{
				
				return $this->redirect()->toRoute('login');
				
			}
			
			$email = $this->LanUserAuthentication()->getIdentity();
			
			$sm = $this->getServiceLocator();
				
			$userTable = $sm->get('Application\Model\UserTable');
			
			$userdata = $userTable->getUserByEmail($email);
			
			if (!$userdata)
			{
				$this->flashMessenger()->addMessage("User not identified"); 
				## loggout user
				$this->LanUserAuthentication()->getAuthService()->clearIdentity();
				
				return $this->redirect()->toRoute('login');
			}
			
			if ($this->flashMessenger()->hasMessages()) {
				
				$messages = $this->flashMessenger()->getMessages();
				
				$this->flashMessenger()->clearMessages();
			}
			
			$view = new ViewModel(
			
					array(
							'title' => 'Profile',
							'userDetails'  => $userdata,
							'messages' => $messages
					)
			
			);
			return $view;
		}
		
		public function showimageAction()
		{
			
			$result = NULL;
			
			if ($this->getRequest()->isXmlHttpRequest()) {
				
				$userid = (int) $this->params()->fromQuery('userid', 0);
				
				if ($userid) {
					
					$sm = $this->getServiceLocator();
					
					$userTable = $sm->get('Application\Model\UserTable');
						
					$userdata = $userTable->getUser($userid);
					
					if ($userdata)
						
					{
					
						$image = "<img src='/images/".$userid."/".$userdata->avatar."' width='150'>";
				
						$result = new JsonModel(array(
						
							'image' => $image
					
						));
						
					}
				
				}
			
			}
			
			return $result;
			
		}
		
		public function uploadAvatar(User $user, $tempfile, $avatar)
		{
			
			if ($user->id > 0) {
				 
				$newdir = __DIR__.'/../../../../../public/images';
				
				$filter = new RealPath();
				
				$newdir = $filter->filter($newdir).'/'.$user->id.'/';
				 
				if (!is_dir($newdir)) {
					
					mkdir($newdir);
					
					chmod($newdir, 0777);
					
				}
				
				if (move_uploaded_file($tempfile, $newdir.$avatar))
				{
					## store $avatar in database field avatar
					
					$sm = $this->getServiceLocator();
					
					$userTable = $sm->get('Application\Model\UserTable');
						
					if ($userTable->saveAvatar($user, $avatar))
					{
					
						$this->flashMessenger()->addMessage("File ". $avatar ." uploaded successfully.");
					
						return true;
					
					}
					
				}
				
			}
				 
			return false;
		}
	
	}