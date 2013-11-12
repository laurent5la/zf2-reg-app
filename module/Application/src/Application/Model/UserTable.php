<?php

	namespace Application\Model;
	
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Predicate;
	use Zend\Db\Sql\Where;
	
	use Application\Model\User;

	class UserTable {

	    protected $tableGateway;

	    protected $_table = 'la_users';

	    public function __construct(TableGateway $tableGateway) {
	    	
	        $this->tableGateway = $tableGateway;
	        
	    }

		public function save(User $user) {
			
			$success = 0;
			
			$message = 'Error saving user info (email, first name, last name, password)';
			
			##  cannot use email since it can be edited 
			$id = (int) $user->id;
	        
	        if ($member = $this->getUser($id)) {
	        	
	        	$data = array();
	        			
	        	if ($member->firstname != $user->firstname) $data['firstname'] = $user->firstname;
	        	
	        	if ($member->lastname != $user->lastname) $data['lastname'] = $user->lastname;
	        	
	        	if ($member->email != $user->email) $data['email'] = $user->email;
	        	
	        	list($hash, $salt) = explode(':', $member->password);
	        	
	        	if (md5($user->password.$salt).":".$salt != $member->password) {
	        		
	        		$data['password'] = md5($user->password.$salt).":".$salt;
	        		
	        	}

	        	unset($data['avatar']);
	        	
	        	if (count($data) == 0)
	        	{
	        		
	        		$success = 1;
	        		 
	        		$message = 'No user information needed to be changed';
	        		
	        	}
	        	
	        	else if ($this->tableGateway->update($data, array('id' => $id)))
	        	
	        	{
	        		
	        		$success = 1;
	        		
	        		$message = (isset($data['password'])) ? 'User info saved. Password successfully changed' : '';
	        		
	        	}
	        
	        }
	        
	        return array('success' => $success, 'message' => $message);
	    }
	    
	    public function saveAvatar(User $user, $avatar) {
	    	 
	    	if ((int) $user->id) {
	    
		    	$data = array(
		    
		    		'avatar' => $avatar
		    
		    	);
		    
		    	if ($this->tableGateway->update($data, array('id' => (int) $user->id)))
		    	{
		    		
		    		return true;
		    			
		    	}
		    	
	    	}
 
	    	return false;
	    }
	    
	    public function getUser($id) {
	    	
	    	$id = (int) $id;
	    	
	    	if ($id > 0) {
	    	
	    		$rowset = $this->tableGateway->select(array('id' => $id));
	    		
	    		$row = $rowset->current();
	    		
	    		if ($row) {

	    			return $row;
	    		
	    		}
	    	
	    	}
	    	
	    	return false;
	    }
	    
	    public function getUserByEmail($email = null) {
	    	
	        $row = NULL;
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
	            return $row;
	        }
	
	        return $row;
	    }
	     
	}
	