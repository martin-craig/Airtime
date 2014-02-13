<?php

class Rest_StoredFileController extends Zend_Rest_Controller
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        /*if (!$this->verifyAPIKey()) {
            return;
        }*/

        $this->getResponse()->setHttpResponseCode(200);
        $this->getResponse()->appendBody(json_encode(CcFilesQuery::create()->find()->toArray()));
    }

    public function getAction()
    {
        /*if (!$this->verifyAPIKey()) {
            return;
        }*/

        if (!$id = $this->getId("ERROR: No file ID specified.")) {
            return;
        }

        $file = CcFilesQuery::create()->findPk($id);
        if ($file) {
            $this->getResponse()->setHttpResponseCode(200);
            $this->getResponse()->appendBody(json_encode($file->toArray()));
        } else {
            $this->fileNotFoundResponse();
        }
    }

    public function postAction()
    {
        if (!$this->verifyAPIKey()) {
            return;
        }

        if ($id = $this->getId("ERROR: ID should not be specified when using POST. POST is only for new file uploads and an ID will be given by Airtime")) {
            return;
        }
    }

    public function putAction()
    {
        if (!$this->verifyAPIKey()) {
            return;
        }

        if (!$id = $this->getId("ERROR: No file ID specified.")) {
            return;
        }

        $file = CcFilesQuery::create()->findPk($id);
        if ($file) {
            $this->getResponse()
                ->setHttpResponseCode(200);
            echo json_encode($file);
        } else {
            $this->fileNotFoundResponse();
        }
    }

    public function deleteAction()
    {
        
    }

    private function fileNotFoundResponse()
    {
        $resp = $this->getResponse();
        $resp->setHttpResponseCode(404);
        $resp->appendBody("ERROR: File not found");
    }

    private function getId($error_msg)
    {
        if (!$id = $this->_getParam('id', false)) {
            $resp = $this->getResponse();
            $resp->setHttpResponseCode(400);
            $resp->appendBody($error_msg); 
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
}