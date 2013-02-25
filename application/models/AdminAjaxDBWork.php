<?php
class Application_Model_AdminAjaxDBWork
{

    private $My_DB;

    private $My_lib_lang;

    
    public function __construct()
    {
        $this->My_DB = Zend_Db_Table::getDefaultAdapter();//Zend_Registry::get('db_admin');
        $this->My_lib_lang = new MyLib_ShowLang($this->My_DB);
    }
    
    public function My_sorting_menu($dirqer,$hertakanutyun)
    {
        $current = intval($dirqer['arajin']);
        $parent = intval($dirqer['erkrord']);
        $sql_update_state = "UPDATE menu
                SET menu_parent_id = {$parent}
                WHERE menu_ID = {$current}";
        
        $this->My_DB->beginTransaction();
        try 
        {            
            $this->My_DB->getConnection()->query($sql_update_state);
            reset($hertakanutyun);
        //    while((list($m_id, $m_sort_num) = each($hertakanutyun)) == true)            
            foreach ($hertakanutyun as $m_id => $m_sort_num)
            {
                $menu_id = intval($m_id);
                $sorting_num = intval($m_sort_num);
                $sql_update_sorting = "UPDATE menu
                        SET menu_sorting = {$sorting_num}
                        WHERE menu_ID = {$menu_id}";
                
                $this->My_DB->getConnection()->query($sql_update_sorting);
            }
            $this->My_DB->commit();
            return true;
        }
        catch (Exception $e)
        {
            $this->My_DB->rollBack();
            return $e->getMessage();
        }
    }
    
    public function My_change_menu_visibility($element_id,$child_elements_array, $visivility_status)
    {
        $element_id = intval($element_id);
        $visivility_status = intval($visivility_status);
        echo $visivility_status;
        $this->My_DB->beginTransaction();
        try {
            
            if ($visivility_status == 0)
            {
                $sql_element = "UPDATE menu SET menu_visibility = '0' WHERE menu_ID = {$element_id}";
                $count_array = sizeof($child_elements_array);
                if ($count_array != 0)
                {
                    $i = 0;
                    while ($i<$count_array)
                    {
                        $child_element = $child_elements_array[$i];
                        $sql_element_chileds = "UPDATE menu SET menu_visibility = '0' WHERE menu_ID = {$child_element}";
                        $this->My_DB->getConnection()->query($sql_element_chileds);
                        $i++;
                    }
                }
            }
            elseif ($visivility_status == 1)
            {
                $sql_element = "UPDATE menu SET menu_visibility = '1' WHERE menu_ID = {$element_id}";
            }
            
            $this->My_DB->getConnection()->query($sql_element);
            $this->My_DB->commit();
            return true;
        }
        
        catch (Exception $e)
        {
            $this->My_DB->rollBack();
            return $e->getMessage();
        }
    }
    public function My_Select_site_pages($lang_short_name)
    {
    	$lang_id = $this->My_lib_lang->My_getLangId($lang_short_name);
    	$sql = "SELECT * FROM site_pages WHERE lang_id = {$lang_id}";
    	
    	$result = $this->My_DB->getConnection()->query($sql)->fetchAll();
    	
    	return $result;
    	
    }
    public function My_Select_site_categorys($lang_short_name)
    {
    	$lang_id = $this->My_lib_lang->My_getLangId($lang_short_name);
    	
    	$sql = "SELECT * FROM site_categories WHERE lang_id={$lang_id}";
    	
    	$result = $this->My_DB->getConnection()->query($sql)->fetchAll();
    	 
    	return $result;
    }
    public function My_Select_site_contents($lang_short_name)
    {
    	$lang_id = $this->My_lib_lang->My_getLangId($lang_short_name);
    	
    	$sql = "SELECT site_content_id,content_title FROM site_content_seo WHERE lang_id={$lang_id}";
    	 
    	$result = $this->My_DB->getConnection()->query($sql)->fetchAll();
    	
    	return $result;
    }
    public function My_Select_site_special_action()
    {
    	$sql = "SELECT * FROM site_special_action";
    	
    	$result = $this->My_DB->getConnection()->query($sql)->fetchAll();
    	 
    	return $result;
    }
}