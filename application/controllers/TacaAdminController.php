<?php
class TacaAdminController extends Zend_Controller_Action
{
    private $My_admin_forms;
    private $My_admin_DB_Work;
    
    public function init ()
    {     /*   
        $handle = fopen('testing.php', 'w+');
        $text = '
        <?php
        $MyLanguage = array();

return $MyLanguage;';
        fwrite($handle, $text);
        fclose($handle);
        */
      //  echo "<pre>";
     //   print_r($this->_getAllParams()); exit;
        //$Auth = Zend_Session::namespaceGet('Zend_Auth_admin');

        //echo '<pre>';
        	//print_r((array)$Auth['storage']);exit;
    

      //  Zend_Registry::set("Zend_Locale", $showLang->My_getLanguage($UrlLang));
        if (!Zend_Session::namespaceIsset('Zend_Admin_Login'))
        {
            $this->_forward('login',null,null,array('lang'=>Zend_Registry::get('Zend_Locale')));
            $this->_helper->_layout->setLayout('admin_login_layout');
            
            $redirect =new Zend_Session_Namespace('redirect');
            $redirect->__set('redirect_url', $this->getRequest()->getRequestUri());
          //  $this->getResponse()->setRedirect($this->view->url(array('action'=>'login'),'my_admin_route',true));
            //echo "asd";
        }
        else
        {
            $this->_helper->_layout->setLayout('admin_layout');
            if (Zend_Session::namespaceIsset('redirect'))
            {
            	$session_redirect = Zend_Session::namespaceGet('redirect')['redirect_url'];
            	if($session_redirect != "/admin/".Zend_Registry::get('Zend_Locale')."/logout")
            	{
            		$this->getResponse()->setRedirect($session_redirect);
            		Zend_Session::namespaceUnset('redirect');
            	}
            	else
            	{
            		Zend_Session::namespaceUnset('redirect');
            	}
            }
        }
        $showLang = new MyLib_ShowLang(Zend_Registry::get('db_admin'));
        $this->My_admin_forms = new Application_Form_AdminForms();
        $this->My_admin_DB_Work = new Application_Model_AdminDBWork();
        
      
        Zend_Layout::getMvcInstance()->assign('all_languages',$showLang->My_get_all_languages());
        
    }
    
    public function loginAction()
    {
        if (Zend_Session::namespaceIsset('Zend_Admin_Login'))
        {
            $this->getResponse()->setRedirect($this->view->url(array('action'=>'main'),'my_admin_route',true));
        }
        /*if(!Zend_Registry::get('My_ACL')->isAllowed('Guest'))
        {
           $this->getResponse()->setHttpResponseCode(403);
           throw new Zend_Exception('asdasdasd');
        }*/
        $login_form = $this->My_admin_forms->My_login_form();
        
        $this->view->login_form = $login_form;
        
        if ($this->getRequest()->isPost())
        {
            $post = $this->getRequest()->getPost();
            if ($login_form->isValid($post))
            {
                $login_status = $this->My_admin_DB_Work->login($post['username'], $post['password']);
                if ($login_status === true)
                {
                    $this->getResponse()->setRedirect($this->view->url(array('action'=>'main'),'my_admin_route',true));
                }
                else
                {
                   // print_r($login_status);
                    $login_form->reset();
                }
            }
            else
            {
                $errors = $login_form->getErrors();
                $errorsMessages = $login_form->getMessages();
                
                $this->view->errors = $errors;
                $this->view->errorsMessages = $errorsMessages; 
            }
        }
    }
    
    public function logoutAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::destroy(true);
        
        $sessions_list = glob(MY_SESSION_DIRECTORY . "/sess_*");
        foreach ($sessions_list as $ses)
        {
            if ( 0 == filesize( $ses ) )
            {
               chmod($ses, 0755);
               unlink($ses);
            }
        }

        $this->getResponse()->setRedirect($this->view->url(array('action'=>'login','lang'=>Zend_Registry::get('Zend_Locale')),'my_admin_route',true));
    }
    
    public function mainAction ()
    {
       // echo '<pre>';
      //  $a = Zend_Session::namespaceGet('Zend_Auth');
      //  $array = (array)$a['storage'];
       // print_r($array);
       // print_r($a);
        //$stdClass = ObjectConverter::toStdClass( $obj );
    }
    
    public function menusortingAction()
    {
        
        //$this->_helper->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
        $site_map = $this->My_admin_DB_Work->Show_menu(0, 1, Zend_Registry::get('Zend_Locale'));
        $this->view->sorting_menu = $site_map;
        
        $this->render('menusorting');
        $this->renderScript('js_renders/js-menusorting.phtml');
    }
    public function addmenuAction()
    {
          $add_menu_form = $this->My_admin_forms->My_add_new_menu();
          
          if ($this->getRequest()->isPost())
          {
              $post = $this->getRequest()->getPost();
              if ($add_menu_form->isValid($post))
              {
                  print_r($add_menu_form->getValues());
              }
              else
              {
                  $errors = $add_menu_form->getErrors();
                  $errorsMessages = $add_menu_form->getMessages();
                  
                  $this->view->errors = $errors;
                  $this->view->errorsMessages = $errorsMessages;
              }
          }
          $this->view->addmenuform = $add_menu_form;
          
          $this->render('addmenu');
          $this->renderScript('js_renders/js-addmenu.phtml');
    }
    public function addcontentAction()
    {    	
    	$form = $this->My_admin_forms->My_add_new_content(Zend_Registry::get('Zend_Locale'));
    	
    	
    	if ($this->getRequest()->isPost()) 
    	{
    		$post = $this->getRequest()->getPost();
    		if($form->isValid($post))
    		{
    			
    			$status = 'insert db error';
    			if ($status === true) 
    			{
    				$this->view->success = "Sucsusfuly inserted";
    				$form->reset();
    			}
    			else 
    			{
    				$this->view->customerror = $status;
    			}
    		}
    		else 
    		{
    			$errors = $form->getErrors();
    			$errorsMessages = $form->getMessages();
    			
    			$this->view->errors = $errors;
    			$this->view->errorsMessages = $errorsMessages;
    		}
    	}
    	
    	$this->view->form = $form;
    	
    }
}