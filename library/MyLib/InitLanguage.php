<?php
class MyLib_InitLanguage extends Zend_Controller_Plugin_Abstract
{
    
    private $My_db;

    public function __construct ($My_db_adapter = NULL)
    {
        $this->My_db = $My_db_adapter;
    }
    
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        //var_dump($request->getCookie('locale')); exit;
        /* $config = new Zend_Config_Ini(
         APPLICATION_PATH . '/configs/application.ini', 'production');
        $params = $config->toArray();*/
    
        $locales_lang_db = $this->My_get_all_languages(); //$params['locales'];
        $url_lang = mb_strtolower($request->getParam('lang'), 'UTF-8');
    
        $show_lang = $this->My_get_default_language();

        $lang_Cookie = $request->getCookie('locale');
      
        if (empty($url_lang))
        {
            if (empty($lang_Cookie))
            {
                setcookie('locale', $show_lang, time() + (1 * 365 * 24 * 60 * 60),
                '/');
            }
            else
            {
                foreach ($locales_lang_db as $lang_db)
                {
                    if ($lang_db['lang_short_name'] === $lang_Cookie)
                    {
                        $show_lang = $lang_Cookie;
                        break;
                    }
                }
            }
           
        }
        else
        {
            foreach ($locales_lang_db as $lang_db)
            {
                if ($lang_db['lang_short_name'] === $url_lang)
                {
                    $show_lang = $url_lang;
                    break;
                }
            }
            setcookie('locale', $show_lang, time() + (1 * 365 * 24 * 60 * 60), '/');
        }
        
        /* USER or Admin select language */
        if (empty($url_lang))
        {
	        if (Zend_Session::namespaceIsset('Zend_Admin_Login'))
	        {
	            $admin = Zend_Session::namespaceGet('Zend_Admin_Login');
	            if (isset($admin['admin_params']->current_lang))
	            {
	                $current_lang_id = $admin['admin_params']->current_lang;
	                if (!empty($current_lang_id))
	                {
	                    $curr_lang_short_name = $this->My_admin_current_lang($current_lang_id);
	                    if(!empty($curr_lang_short_name))
	                    {
	                        $show_lang = $curr_lang_short_name[0]['lang_short_name'];
	                    }
	                }
	            }
	        }
        }
	    
    
        // Set up and load the translations (there are my custom translations
        // for my app)
        $translate = new Zend_Translate(
                array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/public/' . $show_lang .
                        '.php',
                        'locale' => $show_lang
                ));
    
        // Set up ZF's translations for validation messages.
        $translate_msg = new Zend_Translate(
                array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/global/' . $show_lang .
                        '/Zend_Validate.php',
                        'locale' => $show_lang
                ));
    
        $translate_admin = new Zend_Translate(
                array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/admin/' . $show_lang .
                        '.php',
                        'locale' => $show_lang
                ));
    
        // Add translation of validation messages
        $translate->addTranslation($translate_msg);
        $translate->addTranslation($translate_admin);
    
        Zend_Form::setDefaultTranslator($translate);
    
        // Save it for later
    
        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Registry::set('Zend_Locale', $show_lang);

        $request->setParam('lang', $show_lang);
    
    }
   
    
    private function My_get_all_languages ()
    {
        $sql = 'SELECT * FROM languages WHERE lang_show = 1';
    
        $result = $this->My_db->getConnection()
        ->query($sql)
        ->fetchAll();
    
        return $result;
    }
    private function My_get_default_language()
    {
        $sql = 'SELECT lang_short_name FROM languages WHERE default_lang = 1 LIMIT 1';
        
        $result = $this->My_db->getConnection()
        ->query($sql)
        ->fetchAll();
        
        return $result[0]['lang_short_name'];
    }
    private function My_admin_current_lang($lang_id)
    {
        $sql ="SELECT lang_short_name FROM languages WHERE language_ID={$lang_id}";
        
        $result = $this->My_db->getConnection()
        ->query($sql)
        ->fetchAll();
        
        return $result;
    }
}