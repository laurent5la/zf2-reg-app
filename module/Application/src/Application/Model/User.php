<?php

	namespace Application\Model;

	use Zend\InputFilter\Factory as InputFactory;
	use Zend\InputFilter\InputFilter;
	use Zend\InputFilter\InputFilterAwareInterface;
	use Zend\InputFilter\InputFilterInterface;

	class User implements InputFilterAwareInterface {
	
	    protected $inputFilter;
	    public $id;
	    public $email;
	    public $password;
	    public $firstname;
	    public $lastname;
	    public $avatar;
	
	    public function exchangeArray($data) {
	    	
	    	$this->id        = (isset($data['id']))        ? $data['id'] : null;
	    	$this->firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
	    	$this->lastname  = (isset($data['lastname']))  ? $data['lastname'] : null;
	        $this->email     = (isset($data['email']))     ? $data['email'] : null;
	        $this->password  = (isset($data['password']))  ? $data['password'] : null; 
	        $this->avatar    = (isset($data['avatar']))    ? $data['avatar'] : null;
	        
	    }
	
	    public function getArrayCopy() {
	    	
	        return get_object_vars($this);
	        
	    }
	
	    public function setInputFilter(InputFilterInterface $inputFilter) {
	        
	    }
	
	    /**
	     * Sets Inout Filters for Username and Password field used in Login Form
	     */
	    public function getInputFilter() {
	    	
	        if (!$this->inputFilter) {
	        	
	            $inputFilter = new InputFilter();
	            
	            $factory = new InputFactory();
	            
	            $inputFilter->add($factory->createInput(array(
	            		 
	            		'name' => 'id',
	            		 
	            		'required' => true,
	            
	            		'filters' => array(
	            				
	            				array('name' => 'Digits'),
	            				array('name' => 'StringTrim'),
	            				
	            		),
	            		 
	            		'validators' => array(
	            				
	            				array(
	                    			'name' => 'NotEmpty',
	                        		'options' => array(
	                          			'messages' => array(
	                                		'isEmpty' => 'You need to have an existing account'
	                                	),
	                            	),
	                         	),
	            				
	            		),
	            )));
	
	            $inputFilter->add($factory->createInput(array(
	            		
	            	'name' => 'email',
	            		
	                'required' => true,

	            	'filters' => array(
	                            array('name' => 'StripTags'),
	                            array('name' => 'StringTrim'),
	                ),
	                        
	            	'validators' => array(
	                            array(
	                                'name' => 'StringLength',
	                                'options' => array(
	                                    'encoding' => 'UTF-8',
	                                    'max' => 100,
	                                ),
	                            ),
	                            array(
	                                'name' => 'EmailAddress',
	                                'options' => array('message'=>'Enter valid email address (eg. bob@domain.com).')
	                            ),	            					
	                        ),
	            )));
	            
	            $inputFilter->add($factory->createInput(array(
	            		'name' => 'firstname',
	            		'required' => true,
	            		'filters' => array(
	            				array('name' => 'StripTags'),
	            				array('name' => 'StringTrim'),
	            		),
	            		'validators' => array(
	            				array(
	            						'name' => 'StringLength',
	            						'options' => array(
	            								'encoding' => 'UTF-8',
	            								'min' => 2,
	            								'max' => 80,
	            						),
	            				),
	            				array(
	            						'name' => 'Regex',
	            						'options' => array(
	            								'pattern' => '/^[a-z]+[a-z0-9_\s]*$/i',
	            								'message' => 'Enter valid Firstname',
	            						),
	            				),
	            		),
	            )));
	            $inputFilter->add($factory->createInput(array(
	            		'name' => 'lastname',
	            		'required' => true,
	            		'filters' => array(
	            				array('name' => 'StripTags'),
	            				array('name' => 'StringTrim'),
	            		),
	            		'validators' => array(
	            				array(
	            						'name' => 'StringLength',
	            						'options' => array(
	            								'encoding' => 'UTF-8',
	            								'min' => 2,
	            								'max' => 80,
	            						),
	            				),
	            				array(
	            						'name' => 'Regex',
	            						'options' => array(
	            								'pattern' => '/^[a-z]+[a-z0-9_\s]*$/i',
	            								'message' => 'Enter valid Lastname',
	            						),
	            				),
	            		),
	            )));
	
	            $inputFilter->add($factory->createInput(array(
	            		'name'     => 'password',
	            		'required' => true,
	            		'filters'  => array(
	            				array('name' => 'StripTags'),
	            				array('name' => 'StringTrim'),
	            		),
	            		'validators' => array(
	            				array(
	            						'name'    => 'StringLength',
	            						'options' => array(
	            								'encoding' => 'UTF-8',
	            								'min'      => 6,
	            								'max'      => 20,
	            						),
	            				),
	            		),
	            
	            )));
	            
	            $inputFilter->add($factory->createInput(array(
	            		'name'     => 'confirm-password',
	            		'required' => true,
	            		'filters'  => array(
	            				array('name' => 'StripTags'),
	            				array('name' => 'StringTrim'),
	            		),
	            		'validators' => array(
	            				array(
	            						'name'    => 'StringLength',
	            						'options' => array(
	            								'encoding' => 'UTF-8',
	            								'min'      => 6,
	            								'max'      => 20,
	            						),
	            				),
	            				array(
	            						'name'    => 'identical',
	            						'options' => array(
	            								'token' => 'password',
	            								'message' => array(
	            										'NOT_SAME' => 'New Password and Confirm Password should be same.'
	            								)
	            						),
	            				),
	            		),
	            )));
	             
	
	            $this->inputFilter = $inputFilter;
	        }
	
	        return $this->inputFilter;
	    }
	
	}

?>
