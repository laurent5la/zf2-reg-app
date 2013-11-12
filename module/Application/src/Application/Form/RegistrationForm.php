<?php
	namespace Application\Form;
	
	use Zend\Form\Form;
	use Zend\Form\Element;
	
	class RegistrationForm extends Form {
	
	    public function __construct($name = null) {

	        parent::__construct('registration');

	        $this->setAttribute('method', 'post');
	        $this->setAttribute('autocomplete', 'off');
	        $this->setAttribute('class', 'form-signin');
	        
	        $this->add(array(

	        	'name' => 'id',
	        	'type' => 'hidden',

	        ));
	        
	        $this->add(array(
	        		
	            'name' => 'email',
	            'attributes' => array(
	                'type' => 'text',
	                'placeholder' => 'Email',
	                'class' => 'input-block-level',
	                'id' => 'email',
	                'maxlength' => 100,
	            ),
	            'options' => array(
	                'label' => 'Email',
	            ),
	        ));
	        
	        $this->add(array(
	        		
	        		'name' => 'firstname',
	        		'attributes' => array(
	        				'type' => 'text',
	        				'placeholder' => 'First Name',
	        				'class' => 'input-block-level',
	        				'id' => 'first-name',
	        				'maxlength' => 100,
	        		),
	        		'options' => array(
	        				'label' => 'First Name',
	        		),
	        ));
	        
	        $this->add(array(
	        		
	        		'name' => 'lastname',
	        		'attributes' => array(
	        				'type' => 'text',
	        				'placeholder' => 'Last Name',
	        				'class' => 'input-block-level',
	        				'id' => 'last-name',
	        				'maxlength' => 100,
	        		),
	        		'options' => array(
	        				'label' => 'Last Name',
	        		),
	        ));
	        
	        $this->add(array(
	        		
	            'name' => 'password',
	            'attributes' => array(
	                'type' => 'password',
	                'placeholder' => 'Password',
	                'class' => 'input-block-level',
	                'id' => 'password' ,
	                'maxlength' => 50,
	            ),
	            'options' => array(
	                'label' => 'Password',
	            ),
	        ));
	        
	        $this->add(array(
	        		
	        		'name' => 'confirm-password',
	        		'attributes' => array(
	        				'type' => 'password',
	        				'placeholder' => 'Confirm Password',
	        				'class' => 'input-block-level',
	        				'id' => 'confirm-password' ,
	        				'maxlength' => 50,
	        		),
	        		'options' => array(
	        				'label' => 'Confirm Password',
	        		),
	        ));
	        
	        
	        $this->addElements();
	        
	        $this->add(array(
	        		
	            'name' => 'submit',
	            'attributes' => array(
	                'type' => 'submit',
	                'value' => 'Submit',
	                'id' => 'submitbutton',
	                'class' => 'btn btn-large btn-primary',
	            ),
	        ));
	        
	    }
	    
	    public function addElements()
	    {
	    	// File Input
	    	$file = new Element\File('image');
	    	$file->setLabel('Avatar')
	    		->setAttribute('id', 'image');
	    	$this->add($file);
	    }
	
	}