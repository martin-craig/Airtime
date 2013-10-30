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
        if (!$this->verifyApiKey()) {
            return;
        }
         $this->getResponse()
            ->appendBody("From indexAction() returning all shows");
    }
    public function getAction()
    {
        if (!$this->verifyApiKey()) {
            return;
        }
        $id = $this->getId();
        if (!$id) {
            return;
        }

        $show = Airtime\CcShowQuery::create()->findPk($id);
        if ($show) {
            $this->getResponse()
                ->appendBody($show->exportTo("JSON"));
        } else {
            $this->showNotFoundResponse();
        }
    }
    
    public function postAction()
    {
        if (!$this->verifyApiKey()) {
            return;
        }
        //If we do get an ID on a POST, then that doesn't make any sense
        //since POST is only for creating.
        if ($id = $this->_getParam('id', false)) {
            $resp = $this->getResponse();
            $resp->setHttpResponseCode(400);
            $resp->appendBody("ERROR: ID should not be specified when using POST. POST is only used for show creation, and an ID will be chosen by Airtime"); 
            return;
        }

        $show = new Airtime\CcShow(); 
        $rawRequestBody = $this->getRequest()->getRawBody();
        //Hacky check to see if the request is a whole JSON object (updating a Show)
        if ($rawRequestBody[0] == '{') {
            $show->importFrom('JSON', $rawRequestBody);
            $show->save();
            return;
        }

        //Otherwise, we're assuming this is a request to create a new show, using
        //URL parameters passed to us.
        //
        //TODO: Implement creation!!
        //TODO: Create a show
        //TODO: Then create a show instance?
        //if ($id = $this->_getParam('id', false)) {
        //stat
    }
    
    public function putAction()
    {
        if (!$this->verifyApiKey()) {
            return;
        }
        $id = $this->getId();
        if (!$id) {
            return;
        }
        
        $show = Airtime\CcShowQuery::create()->findPk($id);
        if ($show)
        {
            $show->importFrom('JSON', $this->getRequest()->getRawBody());
            $show->save();
            $this->getResponse()
                ->appendBody("From putAction() updating the requested show");
        } else {
            $this->showNotFoundResponse();
        }
    }
    
    public function deleteAction()
    {
        if (!$this->verifyApiKey()) {
            return;
        }
        $id = $this->getId();
        if (!$id) {
            return;
        }
        $show = Airtime\CcShowQuery::create()->$query->findPk($id);
        if ($show) {
            $show->delete();
        } else {
            $this->showNotFoundResponse();
        }
    }

    private function getId()
    {
        if (!$id = $this->_getParam('id', false)) {
            $resp = $this->getResponse();
            $resp->setHttpResponseCode(400);
            $resp->appendBody("ERROR: No show ID specified."); 
            return false;
        } 
        return $id;
    }

    private function verifyAPIKey()
    {
        //The API key is passed in via HTTP "basic authentication":
        //  http://en.wikipedia.org/wiki/Basic_access_authentication

        //TODO: Fetch the user's API key from the database to check against 
        $unencodedStoredApiKey = "foobar"; 
        $encodedStoredApiKey = base64_encode($unencodedStoredApiKey . ":");

        //Decode the API key that was passed to us in the HTTP request.
        $authHeader = $this->getRequest()->getHeader("Authorization");
        $encodedRequestApiKey = substr($authHeader, strlen("Basic "));

        if ($encodedRequestApiKey === $encodedStoredApiKey)
        {
            return true;
        }
        else
        {
            $resp = $this->getResponse();
            $resp->setHttpResponseCode(401);
            $resp->appendBody("ERROR: Incorrect API key."); 
            return false;
        }
    }

    private function showNotFoundResponse()
    {
        $resp = $this->getResponse();
        $resp->setHttpResponseCode(404);
        $resp->appendBody("ERROR: Show not found."); 
    }
}
