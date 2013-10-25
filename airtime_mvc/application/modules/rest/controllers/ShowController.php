<?php

class Rest_ShowController extends Zend_Rest_Controller
{
    public function init()
    {
        /* Initialize action controller here */
        /*
        $context = $this->_helper->getHelper('contextSwitch');
        $context->initContext();
        */

        /*
        $front     = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route($front);
        $front->getRouter()->addRoute('show', $restRoute);
        */
        
        $this->_helper->layout->disableLayout();
    }

    public function indexAction()
    {
         $this->getResponse()
            ->appendBody("From indexAction() returning all articles");
    }
    public function getAction()
    {
        $this->getResponse()
            ->appendBody("From getAction() returning the requested article");

        /*
        if (!$id = $this->_getParam('id', false)) {
                // report error, redirect, etc.
            // }
            //
        */
    }
    
    public function postAction()
    {
        $this->getResponse()
            ->appendBody("From postAction() creating the requested article");
    }
    
    public function putAction()
    {
        $this->getResponse()
            ->appendBody("From putAction() updating the requested article");
    }
    
    public function deleteAction()
    {
        $this->getResponse()
            ->appendBody("From deleteAction() deleting the requested article");
    }
}
