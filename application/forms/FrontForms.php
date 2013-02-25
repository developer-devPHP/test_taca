<?php
class Application_Form_FrontForms
{
    private $My_form_decorator;
    private $My_standart_decorator;
    private $My_standart_decorator_without_lable;
    private $My_button_decorators;
    private $My_files_decorators;
    private $My_contact_us_decorators;
    private $My_front_URL_helper;
    
    public function __construct()
    {
        $this->My_front_URL_helper = Zend_Controller_Action_HelperBroker::getStaticHelper('url');
    
        $this->My_form_decorator =
        array(
                'FormElements',
                array('HtmlTag', array('tag' => 'table')),
                'Form',
        );
        $this->My_standart_decorator =
        array(
                'ViewHelper',
                /*'Errors',*/
                array(array('data' => 'HtmlTag'), array('tag' => 'td')),
                array('Label', array('tag' => 'td')),
                array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
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
                array(array('data' => 'HtmlTag'), array('tag' => 'td' )),
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
    
        $this->My_contact_us_decorators = array(
                'ViewHelper',
                /*'Errors',*/
                array('HtmlTag' , array('tag' => 'div', 'class'=>'c_input')),
                array(array('data' => 'HtmlTag'), array('tag' => 'td')),
                array( 'label' , array('tag' => 'td' )),
                array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
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
	    
	    $username = new Zend_Form_Element_Text('username');
	    $username
	        ->setLabel('Username')
	        ->setAttrib('placeholder','username')
	        ->setRequired(true)
	        //->addFilter('StripTags')
	        ->setDecorators($this->My_standart_decorator);
	    
	    $form->addElement($username,'username');
	    
	    $password = new Zend_Form_Element_Password('password');
	    $password
	        ->setLabel('Password')
	        ->setAttrib('placeholder','password')
	        ->setRequired(true)
	      //  ->addFilter('StripTags')
	        ->setDecorators($this->My_standart_decorator);
	    
	    $form->addElement($password,'password');
	    
	    $login = new Zend_Form_Element_Submit('login');
	    $login
	        ->setLabel('Login')
	        ->setAttrib('class', 'btn')
	        ->setDecorators($this->My_button_decorators);
	    
	    $form->addElement($login,'login');
	        
	    
	    return $form; 
    }
}