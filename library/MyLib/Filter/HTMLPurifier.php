<?php
/*
 *  @author Tigran
 */
class MyLib_Filter_HTMLPurifier implements Zend_Filter_Interface
{
    /*
     * @var HTMLPurifier 
     */
    protected $purifier;
    public function __construct()
    {
        HTMLPurifier_Bootstrap::registerAutoload();
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Attr.EnableID', true);
        $config->set('HTML.Strict', true);
        
        $this->purifier = new HTMLPurifier($config);
    }
    
    public function filter($value)
    {
        return $this->purifier->purify($value);
    }
}