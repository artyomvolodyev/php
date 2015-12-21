<?php
class ErrorController extends AppController
{

     public function errorAction() 
    { 		
        // Ensure the default view suffix is used so we always return good 
        // content
		$this->view->pageTitle="Page Not Found";
       $this->_helper->viewRenderer->setViewSuffix('phtml');
	$this->_helper->layout->setLayout('myaccount');
        // Grab the error object from the request
        $errors = $this->_getParam('error_handler');
        $this->view->errorHandlerObject = $errors;

        // $errors will be an object set as a parameter of the request object, 
        // type is a property
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER: 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION: 

                // 404 error -- controller or action not found 
                $this->getResponse()->setHttpResponseCode(404); 
                $this->view->message = 'Page not found (Error Type: '.$errors->type.')';
            break;
            default: 
                // application error 
                $this->getResponse()->setHttpResponseCode(500); 
                $this->view->message = 'Application error (Error Type: '.$errors->type.')';
                break; 
        } 

        // pass the environment to the view script so we can conditionally 
        // display more/less information
        $this->view->env       = $this->getInvokeArg('env'); 
        
        // pass the actual exception object to the view
        $this->view->exception = $errors->exception; 
        
        // pass the request to the view
        $this->view->request   = $errors->request; 
    } 

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}