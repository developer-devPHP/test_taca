<?php

class Application_Model_FrontDBWork extends Zend_Db_Table
{

    private $menu_result;
    private $My_menu;
	private $My_menu_first_ul;
    private $My_left_menu_result;

    private $My_DB;

    private $My_lib_lang;

    private $My_front_URL_helper;

    private $My_parent_menu_id;

    public function __construct ()
    {
        $this->My_DB = Zend_Db_Table::getDefaultAdapter(); //Zend_Registry::get('db_public');
        
        $this->My_front_URL_helper = new Zend_Controller_Action_Helper_Url(); 
        
        $this->menu_result = "";
        $this->My_menu = "";
        $this->My_menu_first_ul = 1;
        
        $this->My_left_menu_result = "";
        $this->My_lib_lang = new MyLib_ShowLang($this->My_DB);
    }
    
    public function My_select_top_menu($parent, $level, $lang_short_name)
    {
    	$lang_id = $this->My_lib_lang->My_getLangId_all($lang_short_name);
    	$sql = 
    	"SELECT menu.menu_ID, menu.menu_name, menu.menu_url,menu.menu_url_type,
    	menu_deriv.Count
    	FROM menu
    	LEFT OUTER JOIN (SELECT menu_parent_id, COUNT(*) AS Count FROM menu GROUP BY menu_parent_id)
    	AS menu_deriv ON (menu.menu_ID = menu_deriv.menu_parent_id)
    	WHERE menu.menu_parent_id= {$parent} AND menu.lang_id={$lang_id} AND menu_visibility=1
    	ORDER BY menu.menu_sorting ASC
    	";
    	
    	$result = $this->My_DB->getConnection()->query($sql)->fetchAll();
    	//echo '<pre>';
    	//print_r($result); exit;
    	if ($this->My_menu_first_ul === 1)
        {
            $this->My_menu .= "<ul class='level1'>";
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
    		$menu_id = $result[$i]['menu_ID'];
    		$menu_name = $result[$i]['menu_name'];
    		$menu_title = $result[$i]['menu_name'];
    		$menu_url_value = $result[$i]['menu_url'];
    		switch ($result[$i]['menu_url_type'])
    		{
    			case 1:
    				$menu_url = $this->My_front_URL_helper->url ( array (
							'lang' => $lang_short_name,
							'menu_id' => $menu_id,
							'page_id' => $menu_url_value,
							'title' =>$menu_title 
					), 'my_custom_default', true );
    				break;
    			default:
    				$menu_url = 'javascript:';
    		}
    		
    		if ($result[$i]['Count'] > 0)
    		{
    			if ($level == 1)
    			{

    				$this->My_menu .=
    				"<li class='level1-li'>
    				<a class='level1-a drop' href='{$menu_url}'>{$menu_name}<!--[if gte IE 7]><!--></a><!--<![endif]-->
    						<!--[if lte IE 6]><table><tr><td><![endif]-->
    				";
    			}
    			else
    			{
    				$this->My_menu .=
    				"
    				<li><a class='fly' href='{$menu_url}'>{$menu_name}<!--[if gte IE 7]><!--></a><!--<![endif]-->
                    	<!--[if lte IE 6]><table><tr><td><![endif]-->
    				";
    			}
    			
    			$this->My_select_top_menu($menu_id, $level + 1, $lang_short_name);
    			$this->My_menu .= "</li>";
    			
    		}
    		elseif ($result[$i]['Count']==0)
    		{
    			if($level == 1)
    			{
    				$this->My_menu .=
    				"<li class='level1-li'>
    					<a class='level1-a' href='{$menu_url}'>{$menu_name}</a>
    				</li>";
    			}
    			else 
    			{
    				$this->My_menu .=
    				"<li>
    					<a href='{$menu_url}'>{$menu_name}</a>
    				</li>";
    			}
    		}
    		//echo $menu_url;
    		$i++;
    	}
    	$this->My_menu .= "</ul>";
    	
    	return $this->My_menu;
    	
    }

    private function My_find_menu_top_parent ($menu_id)
    {
        $result = null;
        
        $men_id = intval($menu_id);
        $sql = "SELECT menu_ID, parent_id FROM menu WHERE menu_ID = {$men_id} LIMIT 1";
        
        $query_result = $this->My_DB->query($sql)->fetchAll();
        if (! empty($query_result))
        {
            while ($query_result[0]['parent_id'] != 0)
            {
                $sql = "SELECT menu_ID, parent_id FROM menu WHERE menu_ID = {$query_result[0]['parent_id']} LIMIT 1";
                
                $query_result = $this->My_DB->getConnection()
                    ->query($sql)
                    ->fetchAll();
            }
            
            return $query_result[0]['menu_ID'];
        }
        else
        {
            throw new Zend_Controller_Action_Exception("This menu not exist", 
                    404);
        }
    }

   /* public function My_Select_menu ($parent, $level, $lang_short_name, $menu_id)
    {
        if (empty($this->My_parent_menu_id))
        {
            $this->My_parent_menu_id = $this->My_find_menu_top_parent($menu_id);
        }
        
        if ($level <= 2)
        {
            
            $lang_id = $this->My_lib_lang->My_getLangId($lang_short_name);
            
            $sql = "SELECT menu.menu_ID, menu.menu_name, menu.menu_page_id, menu_deriv.Count
				FROM menu
				LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS Count FROM menu GROUP BY parent_id) 
				AS menu_deriv ON (menu.menu_ID = menu_deriv.parent_id) 
				WHERE menu.parent_id= {$parent} AND menu.lang_id={$lang_id}
				ORDER BY menu.parent_id,menu.sorting
		 ";
            $result = $this->My_DB->getConnection()
                ->query($sql)
                ->fetchAll();
            
            $this->menu_result .= "<ul>";
            foreach ($result as $row)
            {
                $menu_name = $row['menu_name'];
                $title = str_replace(' ', '_', $row['menu_name']);
                $title = str_replace('/', '_', $title);
                $title = str_replace('&', '_', $title);
                $title = str_replace('~', '_', $title);
                $title = str_replace('!', '_', $title);
                $title = str_replace('-', '_', $title);
                $My_URL = array(
                        'lang'=>$lang_short_name,
                        'menu_id'=>$row['menu_ID'],
                        'title'=>$title,
                        'page_id'=>$row['menu_page_id']
                );
                if ($row['Count'] > 0)
                {
                    if (empty($row['menu_page_id']))
                    {
                        if ($row['menu_ID'] == $this->My_parent_menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='javascript:'>" .
                                     $menu_name . "</a>";
                            $this->My_Select_menu($row['menu_ID'], $level + 1, 
                                    $lang_short_name, $menu_id);
                            $this->menu_result .= "</li>";
                        }
                        else
                        {
                            $this->menu_result .= "<li><a href='javascript:'>" .
                                     $menu_name . "</a>";
                            $this->My_Select_menu($row['menu_ID'], $level + 1, 
                                    $lang_short_name, $menu_id);
                            $this->menu_result .= "</li>";
                        }
                    }
                    else
                    {
                        if ($row['menu_ID'] == $this->My_parent_menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='{$this->My_front_URL_helper->url($My_URL,'my_custom_default')}'>" .
                                     $menu_name . "</a>";
                            if ($row['menu_ID'] != 1)
                            {
                                $this->My_Select_menu($row['menu_ID'], 
                                        $level + 1, $lang_short_name, $menu_id);
                            }
                            $this->menu_result .= "</li>";
                        }
                        else
                        {
                            $this->menu_result .= "<li><a href='{$this->My_front_URL_helper->url($My_URL,'my_custom_default')}'>" .
                                     $menu_name . "</a>";
                            if ($row['menu_ID'] != 1)
                            {
                                $this->My_Select_menu($row['menu_ID'], 
                                        $level + 1, $lang_short_name, $menu_id);
                            }
                            $this->menu_result .= "</li>";
                        }
                    }
                }
                elseif ($row['Count'] == 0)
                {
                    if (empty($row['menu_page_id']))
                    {
                        if ($row['menu_ID'] == $this->My_parent_menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='javascript:'>" .
                                     $menu_name . "</a></li>";
                        }
                        
                        else
                        {
                            $this->menu_result .= "<li><a href='javascript:'>" .
                                     $menu_name . "</a></li>";
                        }
                    }
                    else
                    {
                        if ($row['menu_ID'] == $this->My_parent_menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='{$this->My_front_URL_helper->url($My_URL,'my_custom_default')}'>" .
                                     $menu_name . "</a></li>";
                        }
                        else
                        {
                            $this->menu_result .= "<li><a href='{$this->My_front_URL_helper->url($My_URL,'my_custom_default')}'>" .
                                     $menu_name . "</a></li>";
                        }
                    }
                }
            }
            
            $this->menu_result .= "</ul>";
        }
        
        return $this->menu_result;
    }

    private function My_drow_left_menu ($parent, $level, $lang_short_name, 
            $menu_id)
    {
        if ($level <= 2)
        {
            
            $lang_id = $this->My_lib_lang->My_getLangId($lang_short_name);
            
            $sql = "SELECT menu.menu_ID,page.site_view_id, menu_deriv.Count, menu_det.menu_name
        		FROM menu
        		INNER JOIN pages AS page ON (menu.page_view_id = page.page_ID)
        		INNER JOIN menu_details AS menu_det ON(menu.menu_ID = menu_det.menu_id)
        		LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS Count FROM menu GROUP BY parent_id)
        		AS menu_deriv ON (menu.menu_ID = menu_deriv.parent_id)
        		WHERE menu.parent_id= {$parent} AND menu_det.lang_id={$lang_id}
        		ORDER BY menu.parent_id,menu.sorting
		        ";
            $result = $this->My_DB->getConnection()
                ->query($sql)
                ->fetchAll();
            
            $this->menu_result .= "<ul>";
            foreach ($result as $row)
            {
                $menu_name =  $row['menu_name'];
                $title = str_replace(' ', '_', $row['menu_name']);
                if ($row['Count'] > 0)
                {
                    if ($row['site_view_id'] == 1)
                    {
                        if ($row['menu_ID'] == $menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='javascript:'>" .
                                     $menu_name . "</a>";
                            $this->My_Select_menu($row['menu_ID'], $level + 1, 
                                    $lang_short_name, $menu_id);
                            $this->menu_result .= "</li>";
                        }
                        else
                        {
                            $this->menu_result .= "<li><a href='javascript:'>" .
                                     $menu_name . "</a>";
                            $this->My_Select_menu($row['menu_ID'], $level + 1, 
                                    $lang_short_name, $menu_id);
                            $this->menu_result .= "</li>";
                        }
                    }
                    else
                    {
                        if ($row['menu_ID'] == $menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='{$this->My_front_URL_helper->url(array('title'=>"{$title}",'menu_id'=>"{$row['menu_ID']}"))}'>" .
                                     $menu_name . "</a>";
                            $this->My_Select_menu($row['menu_ID'], $level + 1, 
                                    $lang_short_name, $menu_id);
                            $this->menu_result .= "</li>";
                        }
                        else
                        {
                            
                            $this->menu_result .= "<li><a href='{$this->My_front_URL_helper->url(array('title'=>"{$title}",'menu_id'=>"{$row['menu_ID']}"))}'>" .
                                     $menu_name . "</a>";
                            $this->My_Select_menu($row['menu_ID'], $level + 1, 
                                    $lang_short_name, $menu_id);
                            $this->menu_result .= "</li>";
                        }
                    }
                }
                elseif ($row['Count'] == 0)
                {
                    if ($row['site_view_id'] == 1)
                    {
                        if ($row['menu_ID'] == $menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='javascript:'>" .
                                     $menu_name . "</a></li>";
                        }
                        
                        else
                        {
                            $this->menu_result .= "<li><a href='javascript:'>" .
                                     $menu_name . "</a></li>";
                        }
                    }
                    else
                    {
                        if ($row['menu_ID'] == $menu_id)
                        {
                            $this->menu_result .= "<li><a class='top_menu_select' href='{$this->My_front_URL_helper->url(array('title'=>"{$title}",'menu_id'=>"{$row['menu_ID']}"))}'>" .
                                     $menu_name . "</a></li>";
                        }
                        else
                        {
                            $this->menu_result .= "<li><a href='{$this->My_front_URL_helper->url(array('title'=>"{$title}",'menu_id'=>"{$row['menu_ID']}"))}'>" .
                                     $menu_name . "</a></li>";
                        }
                    }
                }
            }
            
            $this->menu_result .= "</ul>";
        

        }
        
        return $this->menu_result;
    }
*/
    public function My_Select_leftmenus ($menu_id, $lang_short_name)
    {
        $this->menu_result = '';
        $menu_id = intval($menu_id);
        $sql = "SELECT parent_id FROM menu
				WHERE menu_ID={$menu_id}";
        $result_parent = $this->My_DB->getConnection()
            ->query($sql)
            ->fetchAll();
        
        if (! empty($result_parent))
        {
            if ($result_parent[0]['parent_id'] == 0)
            {
                return $this->My_drow_left_menu($menu_id, 1, $lang_short_name, 
                        $menu_id);
            }
            else
            {
                return $this->My_drow_left_menu($result_parent[0]['parent_id'], 
                        1, $lang_short_name, $menu_id);
            }
        }
    }

    public function My_Select_page_type ($page_id)
    {
        $sql = "SELECT pages.page_cat_id,site_types.site_type_name FROM pages
                INNER JOIN kap_cont_cat AS KAP ON(pages.page_cat_id = KAP.cat_id)
                INNER JOIN site_categories AS CATEG ON (KAP.cat_id = CATEG.cat_ID)
                INNER JOIN site_types ON(CATEG.site_type_id = site_types.site_type_ID)
                WHERE pages.page_ID = {$page_id} 
                LIMIT 1
				";
        $result = $this->My_DB->getConnection()
            ->query($sql)
            ->fetchAll();
        if (! empty($result))
        {
            return $result[0];
        }
        else
        {
            throw new Zend_Controller_Action_Exception(
                    'Page view type not exist', 404);
        }
    }
    
    public function My_Get_page_id_content($page_id,$lang_short_name,$limit=null)
    {

        $separator = MY_SEPARATOR;
        $lang_id = $this->My_lib_lang->My_getLangId($lang_short_name);
        if (!empty($limit))
        {
            $limit = "LIMIT {$limit}";
        }
        $sql_limit = 'SET SESSION  group_concat_max_len = 9999999999999999999'; //
        // site_content.content_title SEPARATOR '{$separator}'
        // GROUP_CONCAT( site_content.content_title SEPARATOR '{$separator}' ) as test,
        $sql = "SELECT site_types.site_type_name,site_categories.cat_name,
                
                GROUP_CONCAT(site_content.content_text SEPARATOR '{$separator}') AS test2
                FROM (SELECT * FROM site_content $limit) AS site_content
                INNER JOIN kap_cont_cat ON (kap_cont_cat.content_id = site_content.content_ID)
                INNER JOIN site_categories ON (site_categories.cat_ID = kap_cont_cat.content_cat_id AND site_categories.lang_id = site_content.lang_id)
                INNER JOIN kap_pages_cat ON (kap_pages_cat.page_cat_id = kap_cont_cat.content_cat_id)
                INNER JOIN site_pages ON (site_pages.site_page_ID = kap_pages_cat.page_id AND site_pages.lang_id = site_content.lang_id )
                INNER JOIN site_types ON (site_types.site_type_ID = site_pages.site_type_id)
                
                
                WHERE kap_pages_cat.page_ID = {$page_id} AND site_content.lang_id = {$lang_id}
                GROUP BY kap_cont_cat.content_cat_id
                
                ";
        
         $this->My_DB->getConnection()->query($sql_limit);
         $result = $this->My_DB->getConnection()->query($sql)->fetchAll();
         if (!empty($result))
         {
             return $result;
         }
         else 
         {
             throw new Zend_Controller_Action_Exception('page id result content is empty',404);
         }
    }
}