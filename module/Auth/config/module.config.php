<?php
	return array(
			
	    'controllers' => array(
	        'invokables' => array(
	            'Auth\Controller\Auth' => 'Auth\Controller\AuthController',
	        ),
	    ),
	    
	    'router' => array(
	        'routes' => array(
				'login' => array(
					'type'    => 'Literal',
					'options' => array(
						'route'    => '/login',
						'defaults' => array(
							'controller' => 'Auth\Controller\Auth',
							'action'     => 'login',
						),
					),
				),
	        ),
	    ),
	    
	    'view_manager' => array(
	        'template_path_stack' => array(
	            'auth' => __DIR__ . '/../view',
	        ),
	    ),
	
	);