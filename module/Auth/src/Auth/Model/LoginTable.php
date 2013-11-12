<?php
	namespace Auth\Model;

	use Zend\Db\TableGateway\TableGateway;
	use Zend\Db\Sql\Where;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Predicate;

	class LoginTable {
	
	    protected $tableGateway;
	    protected $_table = 'la_users';
	
	    public function __construct(TableGateway $tableGateway) {
	        $this->tableGateway = $tableGateway;
	    }
	
	    /**
	     * While Authenticating in loginAction() we need the salt for
	     * ->setCredential(md5($this->salt . $post->get('password')).':'.$this->salt);
	     * before passing AuthAdapter to AuthenticationService->authenticate()
	     */
	    public function getSalt($email = null) {
	        $salt = '';
	        if ($email != null && $email != '') {
	            $select = new Select($this->_table);
	            $like = array(
	                new Predicate\PredicateSet(
	                        array(
	                    new Predicate\Like($this->_table . '.email', "" . $email . ""),
	                        )
	                ),
	            );
	            $select->where($like);
	            $rowset = $this->tableGateway->selectWith($select);
	            $row = $rowset->current();
	            if ($row) {
	            	$password = $row->password;
	            	$parts	= explode( ':', $password );
	            	$salt	= @$parts[1];
	            }
	        }
	        return $salt;
	    }
	    
	    /**
	     * Returns User data via passed email
	     * @param type $username
	     * @return type
	     */
	    public function getUserbyEmail($email = null) {
	    	
	    	$user = null;
	    	 
	    	if ($email != null && $email != '') {

	    		$select = new Select($this->_table);
	    		
	    		$like = array(
	    				
	    			new Predicate\PredicateSet(
	    				array(
	    					new Predicate\Like($this->_table . '.email', "" . $email . ""),
	    				)
	    			),
	    				
	    		);
	    		
	    		$select->where($like);
	    		
	    		$rowSet = $this->tableGateway->selectWith($select);
	    		
	    		if($rowSet) {
	    			
	    			$user = $rowSet->current();
	    		
	    		}
	    	}
	    
	    	return $user;
	    }
	
	    /**
	     * Returns User data via passed Username
	     * @param type $username
	     * @return type
	     */
	    public function getUserbyUsername($username = null) {
	        $user = null;
	        
	        if ($username != null && $username != '') {
	            
	        	$select = new Select($this->_table);
	            
	            $like = array(
	                
	            	new Predicate\PredicateSet(
	                    array(
	                        new Predicate\Like($this->_table . '.username', "" . $username . ""),
	                    )
	                ),
	            );
	            
	            $select->where($like);
	            
	            $rowSet = $this->tableGateway->selectWith($select);
	            
	            if($rowSet) {
	                $user = $rowSet->current();
	            }
	        }
	
	        return $user;
	    }
	    
	}