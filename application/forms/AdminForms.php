<?php
/*
 * @author Tigran
 * 
 */

class Application_Form_AdminForms
{
    private $My_form_decorator;
    private $My_standart_decorator;
    private $My_standart_decorator_without_lable;
    private $My_button_decorators;
    private $My_files_decorators;
    private $My_front_URL_helper;
    
    private $My_translate;
    
    private $My_Admin_DB_work;

    public function __construct()
    {
        $this->My_front_URL_helper = Zend_Controller_Action_HelperBroker::getStaticHelper('url');
        $this->My_translate =  Zend_Registry::get('Zend_Translate');
        $this->My_Admin_DB_work = new Application_Model_AdminDBWork();

        $this->My_form_decorator =
        array(
                'FormElements',
                array('HtmlTag', array('class'=>'form-horizontal')),
                'Form',
        );
        $this->My_standart_decorator =
        array(
                'ViewHelper',
                /*'Errors',*/
                array(array('data' => 'HtmlTag'), array('tag' => 'div','class'=>'controls')),
                array('Label', array('class'=>'control-label')),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'=>'control-group')),
        );

        $this->My_standart_decorator_without_lable =
        array(
                'ViewHelper',
                /*'Errors',*/
                array(array('data' => 'HtmlTag'), array('tag' => 'td')),

                array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        );

        $this->My_button_decorators = array(
                'ViewHelper',
                array(array('data' => 'HtmlTag'), array('tag' => 'div','class'=>'controls' )),
                array(array('label' => 'HtmlTag'), array('tag' => 'td',  'placement' => 'prepend')),
                array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        );
        $this->My_files_decorators = array(
                'File',
                array(array('Value'=>'HtmlTag'), array('tag'=>'td')),
                /*'Errors',*/
                'Description',
                array('Label', array('tag' => 'td')),
                array(array('Field'=>'HtmlTag'), array('tag'=>'tr')),
        );
    }
    
    public function My_login_form()
    {
        $form = new Zend_Form();
	    $form
    	    ->setName("login_form")
    	    ->setDecorators($this->My_form_decorator)
    	    ->setMethod('post')
    	    ->setAttrib('enctype', 'multipart/form-data');
	    
	    $username_label = $this->My_translate->translate('Username');
	    
	    $username = new Zend_Form_Element_Text('username');
	    $username
	        ->setLabel($username_label)
	        ->setAttrib('placeholder', $username_label)
	        ->setRequired(true)
	        ->setAttrib('class', 'span3')
	        ->addFilter(new MyLib_Filter_HTMLPurifier())
	        ->setDecorators($this->My_standart_decorator);
	    
	    
	    
	    $password_label = $this->My_translate->translate('Password');
	    $password = new Zend_Form_Element_Password('password');
	    $password
	        ->setLabel($password_label)
	        ->setAttrib('placeholder',$password_label)
	        ->setRequired(true)
	        ->addFilter(new MyLib_Filter_HTMLPurifier())
	        ->setAttrib('class', 'span3')
	        ->setDecorators($this->My_standart_decorator);
	    
	    
	    
	    $remember_me_label = $this->My_translate->translate("Remember 15 days");
	    $remember_me = new Zend_Form_Element_Checkbox('remember_me');
	    $remember_me
	        ->setLabel($remember_me_label)
	        ->addValidator('Digits')
	        ->setDecorators($this->My_standart_decorator);
	        
	    
	    $publicKey = '6LfbpMgSAAAAAEpzEHWa78x3MS3vt4z_rGjTx1z5';
	    $privateKey = '6LfbpMgSAAAAAFUlA1_HAhctrWRXV9Avr9ErUP5x';
	    
	    $recaptcha = new Zend_Service_ReCaptcha($publicKey, $privateKey);
	    $captcha = new Zend_Form_Element_Captcha('recaptcha_response_field',
	            array(
	                    'captcha' => 'ReCaptcha',
	                    'captchaOptions' => array(
	                            'captcha' => 'ReCaptcha',
	                            'service' => $recaptcha,
	                            'theme' => 'white',
	                           
	                    )
	            ));
	    
	    $captcha->setLabel('Captcha')
	    ->setRequired(true)
	    ->setDecorators( array(
                
	    		array(array('data' => 'HtmlTag'), array('tag' => 'div','class'=>'controls')),
	    		array('Label', array('class'=>'control-label')),
	    		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'=>'control-group')),
                
        ));
	    
	    
	    $login_label = $this->My_translate->translate('Login');
	    $login = new Zend_Form_Element_Submit('login');
	    $login
	    ->setLabel($login_label)
	    ->setAttrib('class', 'btn')
	    ->setDecorators($this->My_button_decorators);
	    
	    
	    $form->addElement($username,'username');
	    $form->addElement($password,'password');
	    $form->addElement($remember_me,'remember_me');
	//    $form->addElement($captcha,'recaptcha_response_field');
	    $form->addElement($login,'login');
	    return $form; 
	    
	    
    }
    public function My_add_new_menu()
    {
        
        $menu_type_label = $this->My_translate->translate('Menu type');
        $menu_types_array = $this->My_Admin_DB_work->My_get_menu_types();
        $menu_type_count = sizeof($menu_types_array);
        
        $menu_name_label = $this->My_translate->translate('Menu name');
        $menu_type_value_label = $this->My_translate->translate('Menu type value');
        
        $menu_type_value_url_label = $this->My_translate->translate('Menu URL');
        
        $add_menu_label = $this->My_translate->translate('Add menu');
        
        $form = new Zend_Form();
        $form
        	->setDecorators($this->My_form_decorator);
        
        $menu_name = new  Zend_Form_Element_Text('menu_name_add');
        $menu_name
        	->setLabel($menu_name_label)
        	->setRequired(true)
        	->addFilter(new MyLib_Filter_HTMLPurifier())
        	->setDecorators($this->My_standart_decorator);
        
        $menu_type = new Zend_Form_Element_Select('menu_type');
        $menu_type
            ->setLabel($menu_type_label)
            ->setRequired(true)
            ->addMultiOption('','')
            ->setValue('')
            ->addValidator('Digits')
            ->setDecorators($this->My_standart_decorator);
        $i=0;
        while ($i<$menu_type_count)
        {
        	$menu_type->addMultiOption($menu_types_array[$i]['site_menu_type_ID'],$menu_types_array[$i]['description']);
        	$i++;
        }
        
        $menu_type_values = new Zend_Form_Element_Select('menu_type_values');
        $menu_type_values
        	->setLabel($menu_type_value_label)
        	->addValidator('Digits')
        	->setRegisterInArrayValidator(false)
        	->setDecorators($this->My_standart_decorator);
        	
        
        $menu_type_value_url = new Zend_Form_Element_Text('menu_type_values_url');
        $menu_type_value_url
        	->setLabel($menu_type_value_url_label)
        	->addFilter(new MyLib_Filter_HTMLPurifier())
        	->addValidator(new MyLib_Validators_UrlValidator())
        	->setDecorators($this->My_standart_decorator);
        
        
        $add_menu = new Zend_Form_Element_Submit('add_menu');
        $add_menu
        	->setLabel($add_menu_label)
        	->setAttrib('class', 'btn btn-inverse')
        	->setDecorators($this->My_button_decorators);
        
        $form->addElement($menu_name,'menu_name_add');
        $form->addElement($menu_type,'menu_type');
        $form->addElement($menu_type_values,'menu_type_values');
        $form->addElement($menu_type_value_url,'menu_type_values_url');
        $form->addElement($add_menu,'add_menu');
        
        
        
        return $form;
        
    }
    public function My_add_new_content($language_short_name)
    {
    	$categorys_values = $this->My_Admin_DB_work->My_get_categorys($language_short_name);
    	
    	$title_label = $this->My_translate->translate('Title');
    	$content_label = $this->My_translate->translate('Content');
    	$categorys_label = $this->My_translate->translate('Categorys');
    	$description_label = $this->My_translate->translate('Description');
    	$keyword_label = $this->My_translate->translate("Keywords ( separator ',' )");
    	
    	$form = new Zend_Form();
    	$form
    	->setDecorators($this->My_form_decorator);
    	
    	$title = new Zend_Form_Element_Text('content_title');
    	$title
    		->setLabel($title_label)
    		->setRequired(true)
    		->addFilter(new MyLib_Filter_HTMLPurifier())
    		->setAttrib('class', 'span12')
    		->setAttrib('title', $title_label)
    		->addValidator(
    				new Zend_Validate_StringLength(array('max' => 80, 'encoding' => 'UTF-8'))
    		)
    		->setDecorators($this->My_standart_decorator);
    	
    	$content = new Zend_Form_Element_Textarea('content');
    	$content
    		->setLabel($content_label)
    		->setRequired(true)
    		->setDecorators(array('ViewHelper'));
    	
    	$categorys = new Zend_Form_Element_MultiCheckbox('categorys');
    	$categorys
    		->setLabel($categorys_label)
    		->setSeparator(PHP_EOL)
    		->setDecorators(array(
    					'ViewHelper',
    					array(array('data' => 'HtmlTag'), array('tag' => 'div','class'=>'well pre-scrollable')),
    					'label'
    			));
    	
    	foreach ($categorys_values as $category) {
    		$categorys->addMultiOption($category['cat_ID'], $category['cat_name']);
    	}
    	
    	$description = new Zend_Form_Element_Textarea('description');
    	$description
    		->setLabel($description_label)
    		->setRequired(true)
    		->setAttrib('class', 'span12')
    		->setAttrib('title', $description_label)
    		->setAttrib('rows', '8')
    		->addValidator(
    				new Zend_Validate_StringLength(array('max' => 200, 'encoding' => 'UTF-8'))
    			)
    		->setDecorators(array(
    				'ViewHelper',
    				'label'
    			));
    	
    	$keyword = new Zend_Form_Element_Text('keywords');
    	$keyword
    		->setLabel($keyword_label)
    		->setRequired(true)
    		->setAttrib('class', 'span12')
    		->setAttrib('title', $keyword_label)
    		->addValidator(
    				new Zend_Validate_StringLength(array('max' => 250, 'encoding' => 'UTF-8'))
    		)
    		->setDecorators(array(
    				'ViewHelper',
    				'label'
    		));
    		
    	$add_submit = new Zend_Form_Element_Submit('add_submit');
    	$add_submit
    		->setLabel('Add')
    		->setAttrib('class', 'btn btn-inverse btn-large pull-right')
    		->setDecorators(array(
    				'ViewHelper',
    				array(array('data' => 'HtmlTag'), array('tag' => 'div','class'=>'form-actions' )),
    				));
    	
    	$form->addElement($title,'content_title');
    	$form->addElement($content,'content');
    	if(!empty($categorys_values))
    	{
    		$form->addElement($categorys,'categorys');
    	}
    	$form->addElement($description,'description');
    	$form->addElement($keyword,'keywords');
    	$form->addElement($add_submit,'add_submit');
    	
    	return $form;	
    	
    }
}