<?php

class MyLib_ShowLang
{

    private $My_db;

    public function __construct ($My_db_adapter)
    {
        $this->My_db = $My_db_adapter;
    }
    
    public function My_get_all_languages ()
    {
        $sql = "SELECT * FROM languages WHERE lang_show = 1";
    
        $result = $this->My_db->getConnection()
        ->query($sql)
        ->fetchAll();
    
        return $result;
    }

    public function My_getLangId ($lang_short_name)
    {
        $lang_short_name = $this->My_db->quote($lang_short_name);
        
        $sql = "SELECT * FROM languages 
				WHERE lang_short_name = {$lang_short_name} AND lang_show = 1 
				LIMIT 1 ";
        
        $result = $this->My_db->getConnection()
            ->query($sql)
            ->fetchAll();
        
        if (! empty($result))
        {
            return $result[0]['language_ID'];
        }
        else
        {
            throw new Zend_Controller_Action_Exception(
                    'This language not supported or it turn off', 404);
        }
    }
    public function My_getLangId_all($lang_short_name)
    {
        $lang_short_name = $this->My_db->quote($lang_short_name);
        
        $sql = "SELECT * FROM languages
        WHERE lang_short_name = {$lang_short_name}
        LIMIT 1 ";
        
        $result = $this->My_db->getConnection()
        ->query($sql)
        ->fetchAll();
        
        if (! empty($result))
        {
            return $result[0]['language_ID'];
        }
        else
        {
        throw new Zend_Controller_Action_Exception(
                'This language not supported at this moment', 404);
        }
    }
}

