<?php
class AdminAjaxController extends Zend_Controller_Action
{
    private $My_admin_ajax_dbwork;
    public function init ()
    {
        if (!Zend_Session::namespaceIsset('Zend_Admin_Login'))
        {
            throw new Zend_Controller_Action_Exception('Admin ajax acsess denied',404);
        }
       if (! $this->getRequest()->isXmlHttpRequest())
       {
           exit('error_1251');
       // $this->getResponse()->setRedirect($this->view->url(array('action'=>'home'),'my_default_route',true));
       }
        $this->My_admin_ajax_dbwork = new Application_Model_AdminAjaxDBWork();
    }
    public function insertsortingmenuAction()
    {
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);
       if ($this->getRequest()->isPost())
       {
           $post = $this->getRequest()->getPost();
           
           $dirqer = Zend_Json::decode($post['dirqy']);
           $hertakanutyun = Zend_Json::decode($post['hertakanutyuny']);
           
           $update_sorting = $this->My_admin_ajax_dbwork->My_sorting_menu($dirqer, $hertakanutyun);
       }
      // print_r($dirqer);
      // print_r($hertakanutyun);
           
    }
    public function insertvisibilityAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost())
        {
            $post = $this->getRequest()->getPost();
            $element_id = $post['element'];
            $visibility_status = $post['visibility'];
            $elements_array = Zend_Json::decode($post['element_childes']);
            
             
            $this->My_admin_ajax_dbwork->My_change_menu_visibility($element_id, $elements_array, $visibility_status);              
        }
    }
    
    public function selectmenutypevalueAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	if ($this->getRequest()->isPost())
    	{
	    	$post = $this->getRequest()->getPost();
	    	$menu_type_id = $post['menu_type'];
	    	switch ($menu_type_id)
	    	{
	    		case 1:
	    			$data_result = $this->My_admin_ajax_dbwork->My_Select_site_pages(Zend_Registry::get('Zend_Locale'));
	    			if (!empty($data_result)) {
	    				echo Zend_Json::encode($data_result);
	    				break;
	    			}
	    		case 2:
	    			$data_result = $this->My_admin_ajax_dbwork->My_Select_site_categorys(Zend_Registry::get('Zend_Locale'));
	    			if (!empty($data_result)) {
	    				echo Zend_Json::encode($data_result);
	    				break;
	    			}
	    		case 3:
	    			$data_result = $this->My_admin_ajax_dbwork->My_Select_site_contents(Zend_Registry::get('Zend_Locale'));
	    			if (!empty($data_result)) {
	    				echo Zend_Json::encode($data_result);
	    				break;
	    			}
	    		case 4:
	    			$data_result = $this->My_admin_ajax_dbwork->My_Select_site_special_action();
	    			if (!empty($data_result)) {
	    				echo Zend_Json::encode($data_result);
	    				break;
	    			}
	    		default: echo 'error';
	    	}
	    	//echo Zend_Registry::get('Zend_Locale');
	    	 
	    	//print_r($data_result);
	    
	    	//echo Zend_Json::encode($a);
    	}
    }
}