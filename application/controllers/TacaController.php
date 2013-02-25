<?php

class TacaController extends Zend_Controller_Action
{

    private $My_front_DB;

    private $My_menu_id;
    
    private $My_page_id;
    
    private $My_fron_forms;

    public function init ()
    {
     //  echo "<pre>";
      // print_r($this->_getAllParams());
     //  echo '<br>';
    //  echo '<br>';
        /* Initialize action controller here */
        $this->My_front_DB = new Application_Model_FrontDBWork();
        $this->My_fron_forms = new Application_Form_FrontForms();
        
        $UrlLang = $this->_getParam('lang');
        $showLang = new MyLib_ShowLang(Zend_Registry::get('db_public'));
       // Zend_Registry::set("Zend_Locale", $showLang->My_getLanguage($UrlLang));
        
       $this->My_menu_id = $this->_getParam('menu_id');
        Zend_Layout::getMvcInstance()->assign('all_languages', 
                $showLang->My_get_all_languages());
        
        /*if (Zend_Uri::check('seua.am'))
        {
            echo "Asd";
        }*/
      Zend_Layout::getMvcInstance()->assign('top_menu',
      		$this->My_front_DB->My_select_top_menu(0, 1, Zend_Registry::get('Zend_Locale'))); 
     /*   Zend_Layout::getMvcInstance()->assign('top_menu', 
                $this->My_front_DB->My_Select_menu(0, 1, 
                        Zend_Registry::get('Zend_Locale'), $this->My_menu_id));*/ 
      /*  Zend_Layout::getMvcInstance()->assign('left_menu', 
                $this->My_front_DB->My_Select_leftmenus($this->My_menu_id, 
                        Zend_Registry::get('Zend_Locale')));*/
    }

    public function indexAction ()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(true);
      //  $this->view->headTitle($this->_getParam('title'), "PREPEND");
  //      echo $this->My_menu_id;
    /*   $page_view_type = $this->My_front_DB->My_Select_page_type(
                1);*/
      //  echo "<pre>";
     //   print_r($page_view_type);
     
       /*if ($this->_hasParam('more'))
       {
           $more = $this->_getParam('more');
           if (!is_numeric($more))
           {
               throw new Zend_Controller_Action_Exception('More is xaced param not int',404);
           }
       }*/
        $all_content = array();
        $page_view_type = null;
        if ($this->_hasParam('page_id'))
        {
            $page_id = $this->_getParam('page_id');
            if ($page_id != 0)
            {
                $all_content = $this->My_front_DB->My_Get_page_id_content($page_id,Zend_Registry::get('Zend_Locale'),9);
                $page_view_type = $all_content[0]['site_type_name'];
            }
            else
            {
                throw new Zend_Controller_Action_Exception('Page id is 0',404);
            }
        }
      // $page_view_type['site_type_name'] = 'home page view';
        switch ($page_view_type)
        {
            case 'home page view':
                
                $this->view->all_content = $all_content;
                $html = $this->view->render("/view_types/home.phtml");
              
                echo $html;
                break;
            
            default:
                throw new Zend_Controller_Action_Exception(
                        'Tthis page not have page view type', 404);
        }
    }
    public function loginAction()
    {
       /* if (!$this->getRequest()->isXmlHttpRequest())
        {
            $this->_redirect("/");
        }*/
        $this->_helper->layout()->disableLayout();
      //  $this->_helper->viewRenderer->setNoRender(true);
       $login_form = $this->My_fron_forms->My_login_form();
       $this->view->login_form = $login_form;
       
       //print_r($this->getRequest()->getParams());
       if ($this->getRequest()->isPost())
       {
           $post = $this->getRequest()->getPost();
           if ($login_form->isValid($post))
           {
               echo "ok";
           }
           else {
           	
           	$errors = $login_form->getErrors();
           	$errorsMessages = $login_form->getMessages();
           	
           	$this->view->errors = $errors;
           	$this->view->errorsMessages = $errorsMessages;
              
             /*  $a = <<<rd
               <script type="text/javascript">
    parent.window.location.href = '{$this->view->url(array('action'=>'index'),'my_custom_default',true)}';
</script>
rd;
               echo $a;*/
           }
           
       }
    }
    public function oauth2callbackAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    }
}

