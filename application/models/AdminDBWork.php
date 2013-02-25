<?php

class Application_Model_AdminDBWork
{
    
    private $My_DB;

    private $My_lib_lang;
    
    private $My_menu;
    private $My_menu_first_ul;
    
    public function __construct()
    {
        $this->My_DB = Zend_Db_Table::getDefaultAdapter();//Zend_Registry::get('db_admin');
       
        $this->My_lib_lang = new MyLib_ShowLang($this->My_DB);
        
        $this->My_menu = '';
        
        $this->My_menu_first_ul = 1;
    }

    public function login ($login, $pass)
    {
        /*$session_storage = new Zend_Session_Namespace('test');
        $session_storage->setExpirationSeconds(1 * 365 * 24 * 60 * 60);
        $session_storage->__set('tiko',array(
                'tasdg'=>'asd'
                ));
                */
        // Получить экземпляр Zend_Auth
        $auth = Zend_Auth::getInstance();
        
        // Создаем Adapter для Zend_Auth, указывая ему где в БД искать логин и
        // пароль для сравнения
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->My_DB, 'admin', 
                'username', 'password', "MD5(?)");
        
        $authAdapter->setIdentity($login)->setCredential($pass);
        
        // Проверяет и сохраняет результат проверки
        $result = $auth->authenticate($authAdapter);
        
        if ($result->isValid())
        {
            // Успешно
            
            // Можно записать в сессию некоторые поля
            
           /* $auth->getStorage()->write(
                    array(
                          'auth_params' =>  $authAdapter->getResultRowObject(
                                    array(
                                            'admin_ID',
                                            'username'
                                    ))
                        )
                    );*/
            
            // Получить объект Zend_Session_Namespace
            
            $session = new Zend_Session_Namespace('Zend_Admin_Login');
            $session->__set('admin_params',$authAdapter->getResultRowObject(
                            array(
                                    'admin_ID',
                                    'username',
                                    'current_lang'
                            ))
                    );
            // Установить время действия залогинености
            $session_exp_time = 1 * 24 * 60 * 60;
            
            $session->setExpirationSeconds($session_exp_time); // 1 tari 1 * 365 * 24 * 60 * 60
                                                                     
            // если отметили "запомнить"
            if (isset($_POST['remember_me']) && $_POST['remember_me'] == 1)
            {
                $session_exp_time = 15 * 24 * 60 * 60;
                Zend_Session::rememberMe($session_exp_time);
            }
            return true;
        }
        
        // Неудачно
        $error_msg = "Username or Password is incorrect"; /*
                                                           *
                                                           * $result->getMessages();
                                                           */
        return $error_msg;
    }
  
    public function Show_menu($parent, $level, $lang_short_name)
    {
        $translate = Zend_Registry::get('Zend_Translate');
        
        $lang_id = $this->My_lib_lang->My_getLangId_all($lang_short_name);
        
        $sql = "SELECT menu.menu_ID, menu.menu_name, menu.menu_url,menu.menu_visibility, 
                    menu_deriv.Count
        FROM menu
        LEFT OUTER JOIN (SELECT menu_parent_id, COUNT(*) AS Count FROM menu GROUP BY menu_parent_id)
        AS menu_deriv ON (menu.menu_ID = menu_deriv.menu_parent_id)
        WHERE menu.menu_parent_id= {$parent} AND menu.lang_id={$lang_id}
        ORDER BY menu.menu_sorting ASC
        "; 
        
        $result = $this->My_DB->getConnection()
        ->query($sql)
        ->fetchAll();
        if ($this->My_menu_first_ul === 1)
        {
            $this->My_menu .= "<ul id='sitemap'>";
            $this->My_menu_first_ul++;
        }
        else 
        {
            $this->My_menu .= "<ul>";
        }

   
        $size_array_result = count($result);
        $i =0;
        while ($i<$size_array_result)
        {
           $menu_name = $result[$i]['menu_name'];
           $menu_id = $result[$i]['menu_ID'];
           $menu_visibility = $result[$i]['menu_visibility'];
           $url = "";
           $url_title = "";
           $icon_move_tittle = $translate->translate('Move');
           $icon_delete_tittle = $translate->translate('Delete');
           $icon_addsub_tittle = $translate->translate('Add submenu');
           
           if ($menu_visibility == 1)
           {
               $menu_visibility = 'checked';
           }
           else
           {
               $menu_visibility = '';
           }
           if ($result[$i]['Count'] > 0)
           {
               $this->My_menu .=
               "<li id='{$menu_id}' class='sm2_liOpen'>
               <dl class='sm2_s_published'><a href='#'class='sm2_expander'>&nbsp;</a>
               <dt><a class='sm2_title' href='#'>{$menu_name}</a></dt>
               <dd class='sm2_actions hidden-phone'> <i title='{$icon_move_tittle}' class='icon-move'></i> <i title='{$icon_delete_tittle}' class='icon-trash'></i> <i title='{$icon_addsub_tittle}' class='icon-plus'></i> <i title='' class='icon-edit'></i> </dd>
               <dd class='sm2_status'> <input type='checkbox' {$menu_visibility}  /> </dd>
               </dl>
               ";
               // <a href='{$row['menu_url']}'>{$row['menu_name']}</a>";
               $this->Show_menu($menu_id, $level + 1, $lang_short_name);
               $this->My_menu .= "</li>";
           }
           elseif ($result[$i]['Count']==0)
           {
           $this->My_menu .=
           "<li id='{$menu_id}'>
           <dl class='sm2_s_published'><a href='#' class='sm2_expander'>&nbsp;</a>
           <dt><a class='sm2_title' href='#'>{$menu_name}</a></dt>
               <dd class='sm2_actions hidden-phone'> <i title='{$icon_move_tittle}' class='icon-move'></i> <i title='{$icon_delete_tittle}' class='icon-trash'></i> <i title='{$icon_addsub_tittle}' class='icon-plus'></i> <i title='' class='icon-edit'></i> </dd>
               <dd class='sm2_status'> <input type='checkbox' {$menu_visibility} /> </dd>
               </dl>
               </li>";
               //<a href='{$row['menu_url']}'>{$row['menu_name']}</a>
           };
           
           $i++;
        }
        
         $this->My_menu .= "</ul>";
         
      return $this->My_menu;
    }
    
    public function My_get_menu_types()
    {
       // $lang_id = $this->My_lib_lang->My_getLangId_all($lang_short_name);
        
        $sql = "SELECT * FROM site_menu_url_types 
                WHERE url_type_visibility = 1
                ORDER BY sorting_number ";
        $result = $this->My_DB->getConnection()->query($sql)->fetchAll();
        
        return $result;
    }
    public function My_get_categorys($lang_short_name)
    {
    	$lang_id = $this->My_lib_lang->My_getLangId_all($lang_short_name);
    	
    	$sql = "SELECT * FROM site_categories
    			WHERE lang_id={$lang_id}
    	";
    	$result = $this->My_DB->getConnection()->query($sql)->fetchAll();
    	
    	return $result;
    }
    
    public function My_insert_content($lang_short_name)
    {
    	$lang_id = $this->My_lib_lang->My_getLangId_all($lang_short_name);
    	
    	$sql_content = "INSERT INTO site_content
    			";
    	
    }
}