<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    private $view_page;
    protected function _initDbRegistry()
    {
        $this->bootstrap('multidb');
        $multidb = $this->getPluginResource('multidb');
        Zend_Registry::set('db_public', $multidb->getDb('public_user'));
        Zend_Registry::set('db_admin', $multidb->getDb('admin_user'));
    }
    
    protected function _initViewHelpers ()
    {
        $this->_bootstrap("layout");
        $layout = $this->getResource("layout");
        $this->view_page = $layout->getView();
        
        $this->view_page->headMeta()->appendHttpEquiv("Content-Type", 
                "text/html; charset=utf-8");
        $this->view_page->headMeta()->appendName('viewport','width=device-width, initial-scale=1.0');
        $this->view_page->headTitle("ISSD");
        $this->view_page->headTitle()->setSeparator(" :: ");
    }

    protected function _initMyLibraries ()
    {
        Zend_Loader_Autoloader::getInstance()->registerNamespace('MyLib_');
        Zend_Loader_Autoloader::getInstance()->registerNamespace('HTMLPurifier');
    }
    

    protected function _initSession ()
    {
        // $options = $this->getOptions();
        $sessionOptions = array(
                'save_path' => MY_SESSION_DIRECTORY,
                'use_only_cookies' => true,
                'remember_me_seconds' => 60//86400 //864000
        );
        Zend_Session::setOptions($sessionOptions);
        Zend_Session::start();
        $defaultNamespace = new Zend_Session_Namespace();
        
        if (!isset($defaultNamespace->initialized)) 
        {
        	Zend_Session::regenerateId();
        	$defaultNamespace->initialized = true;
        }
    }

    protected function _initSetMultilanguage ()
    {
        $plugin = new MyLib_InitLanguage( Zend_Registry::get('db_public'));
        Zend_Controller_Front::getInstance()->registerPlugin($plugin);
        
       /* $config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', 'production');
        $params = $config->toArray();
        $locales = $params['locales'];
        $show_lang = 'hy';
        
        $request = new Zend_Controller_Request_Http();
        $myCookie = $request->getCookie('locale');
        if (empty($myCookie))
        {
            setcookie('locale', $show_lang, time() + (1 * 365 * 24 * 60 * 60), 
                    '/');
        }
        else
        {
            foreach ($locales as $langarray)
            {
                if ($langarray === $myCookie)
                {
                    $show_lang = $myCookie;
                    break;
                }
            }
        }
        
        // register it so that it can be used all over the website
        Zend_Registry::set('Zend_Locale', $show_lang);*/
        // return $lang;
        
    }

    protected function _initTranslate ()
    {
   /*     // Get Locale
        $locale = Zend_Registry::get('Zend_Locale');
        
        // Set up and load the translations (there are my custom translations
        // for my app)
        $translate = new Zend_Translate(
                array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/public/' . $locale .
                                 '.php',
                                'locale' => $locale
                ));
        
        // Set up ZF's translations for validation messages.
        $translate_msg = new Zend_Translate(
                array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/global/' . $locale .
                                 '/Zend_Validate.php',
                                'locale' => $locale
                ));
        
        $translate_admin = new Zend_Translate(
                array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/admin/' . $locale .
                                 '.php',
                                'locale' => $locale
                ));
        
        // Add translation of validation messages
        $translate->addTranslation($translate_msg);
        $translate->addTranslation($translate_admin);
        
        Zend_Form::setDefaultTranslator($translate);
        
        // Save it for later
        Zend_Registry::set('Zend_Translate', $translate);
        */
    }

    protected function _initMyRouting ()
    {
     //   $show_lang = Zend_Registry::get('Zend_Locale');
        $frontController = Zend_Controller_Front::getInstance();
        // print_r( $frontController->getDispatcher() );exit;
        $router = $frontController->getRouter();
        $router->removeDefaultRoutes();
        
        
   /*  $my_custom_default_route = new MyLib_Routing(    
             '{lang}{/menu_id}{&page_id}{~paramstr}{!title}{-paging}', 
             array(
                     'lang' => $show_lang,
                     'controller' => 'taca', 
                     'action' => 'index',
                     'title' =>  Zend_Form::getDefaultTranslator()->translate("Home"),
                     'page_id'=>'1',
                     'paging'=>'1',
                    
                     'menu_id' => '1',
                     'page_id'=>'0'
                     
              ),
             array(
                     'lang' => '[A-Za-z]{2}',
                     'menu_id' => '\d+',
                     'page_id' => '\d+',
                     'paging' => '\d+',
                     'title' => '.[^\/\&\~\!\-]*',
                     //'paramstr' => '.[^\-]+'
                    
             )
        );*/
        
        $my_default_route = new Zend_Controller_Router_Route(
             ':lang/:menu_id/:page_id/:title/*',
             array(
                     'lang' => '',
                     'controller' => 'taca',
                     'action' => 'index',
                     'title' => 'Home',
                     'menu_id'=>'1',
                     'page_id'=>'1',
             )
             ,
             array(
                     'lang' => '[A-Za-z]{2}',
                     'menu_id'=> '\d+',
                     'page_id'=> '\d+',
             
             ));
        
     
     $my_login_form = new Zend_Controller_Router_Route(
             ':lang/login/*',
             array(
                     'lang' => '',
                     'controller' => 'taca',
                     'action' => 'login',
             )
             ,
             array(
                     'lang' => '[A-Za-z]{2}',
             
             ));
     
     $my_admin_route = new Zend_Controller_Router_Route(
                'admin/:lang/:action/:paging/*', 
                array(
                        'lang' => '',
                         'controller' => 'taca-admin', 
                         'action' => 'main',
                         'page_id'=>'1',
                         'paging'=>'1'
                )
                , 
                array(
                        'lang' => '[A-Za-z]{2}',
                         'menu_id' => '\d+',
                         'paging' => '\d+',
                         'paramstr' => '.[^\-]+'
                        
                ));
     $my_admin_ajax_route = new Zend_Controller_Router_Route(
                'admin-ajax/:action/*', 
                array(
                         'controller' => 'admin-ajax', 
                         'action' => 'ijk',
                ), 
                array(
                        
                        
                ));
     
           $router->addRoute('my_custom_default', $my_default_route);
           $router->addRoute('my_login', $my_login_form);
           $router->addRoute('my_admin_route', $my_admin_route);
           $router->addRoute('my_admin_ajax', $my_admin_ajax_route);
           
    }
    
    protected function _initMyACL()
    {
       
       // $this->My_lib->My_acl_get_permissions();
    }
}

