<?php
	namespace Auth\Form;
	
	use Zend\Form\Form;
	
	class LoginForm extends Form {
	
	    public function __construct($name = null) {
	        // we want to ignore the name passed
	        parent::__construct('login');
	
	        $this->setAttribute('method', 'post');
	        $this->setAttribute('autocomplete', 'off');
	        $this->setAttribute('class', 'form-signin');
	        
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
	            'name' => 'submit',
	            'attributes' => array(
	                'type' => 'submit',
	                'value' => 'Login',
	                'id' => 'submitbutton',
	                'class' => 'btn btn-large btn-primary',
	            ),
	        ));
	        
	    }
	
	}