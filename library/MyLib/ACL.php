<?php

class MyLib_ACL extends Zend_Acl
{

    private $My_DB;
    private $My_ACL;

    public function __construct ()
    {
        $this->My_DB = Zend_Db_Table::getDefaultAdapter();
        $this->deny();
    }
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        
    }
    public function My_acl_get_permissions()
    {
        if (!Zend_Session::namespaceIsset('test'))
        {
            $this->My_permishions_before_login();
        }
        else
        {
           // print_r(Zend_Session::namespaceGet('test'));
        }
        Zend_Registry::set('My_ACL', $this);
    }
    private function My_permishions_after_login()
    {
        
    }
    private function My_permishions_before_login()
    {
        $this->addRole('Guest');
    }
    
}