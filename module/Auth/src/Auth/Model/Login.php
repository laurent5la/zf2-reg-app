<?php
	namespace Auth\Model;

	use Zend\InputFilter\Factory as InputFactory;
	use Zend\InputFilter\InputFilter;
	use Zend\InputFilter\InputFilterAwareInterface;
	use Zend\InputFilter\InputFilterInterface;

	class Login implements InputFilterAwareInterface {

    	//public $block;
    	public $password;
   		protected $inputFilter;
    
    	public function exchangeArray($data) {
        //	$this->block = (isset($data['block'])) ? $data['block'] : null;
        	$this->password = (isset($data['password'])) ? $data['password'] : null;
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
	
	            $inputFilter->add($factory->createInput(
	            		array(
	            			'name' => 'email',
	                		'required' => true,
	                 		'validators' => array(
	                 			array(
	                    			'name' => 'NotEmpty',
	                        		'options' => array(
	                          			'messages' => array(
	                                		'isEmpty' => 'Email is required.'
	                                	),
	                            	),
	                         	),
	                            array(
	                                'name' => 'EmailAddress',
	                            ),
	                        ),
	            )));
	            $inputFilter->add($factory->createInput(array(
	                        'name' => 'password',
	                        'required' => true,
	                        'filters' => array(
	                            array('name' => 'StripTags'),
	                            array('name' => 'StringTrim'),
	                        ),
	                        'validators' => array(
	                            array(
	                                'name' => 'NotEmpty',
	                                'options' => array(
	                                    'messages' => array(
	                                        'isEmpty' => 'Password is required.'
	                                    ),
	                                ),
	                            ),
	                            array(
	                                'name' => 'StringLength',
	                                'options' => array(
	                                    'encoding' => 'UTF-8',
	                                    'max' => 100,
	                                ),
	                            ),
	                        ),
	            )));
            	$this->inputFilter = $inputFilter;
        	}
        	return $this->inputFilter;
    	}
	}