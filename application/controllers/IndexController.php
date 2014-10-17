<?php

$include_path=".;.\library";
include 'plain_library.php';
    
class IndexController extends Zend_Controller_Action
{
    private $library=null; 


    public function indexAction()
    {        
        $form = new Application_Form_UploadForm();
        $this->view->form = $form;
 
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $uploadedData = $form->getValues();
                $fullFilePath = $form->file->getFileName();
                session_start();
                $_SESSION['ffPath']=$fullFilePath;
                $this->_helper->redirector('plain');
            } else {
                $form->populate($formData);
            }
        }
    }
    
    private function getLibrary()
    { 
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        if(file_exists($_SESSION['ffPath'])) { 
            $this->library=new PlainLibrary(file_get_contents($_SESSION['ffPath']));           
        } 
        return $this->library; 
    }       

    public function plainAction()
    {    
        
        $form = new Application_Form_TestForm();
        $this->view->form = $form;
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $part = $form->getValue('interval');
                $point = $form->getValue('point'); 
                $datetime = new DateTime($form->getValue('datetime_d'));
                $this->view->v1 =  round($this->getLibrary()->getPartDistance($part),2);
                $this->view->v2 =  round($this->getLibrary()->getDistance(),2);
                $date_part = $this->getLibrary()->getPartTimeArrival($datetime, $point); 
                $this->view->v3 = $date_part->format('Y-m-d H:i:s');
                $datetime2 = new DateTime($form->getValue('datetime_d'));
                $date_all = $this->getLibrary()->getTimeArrival($datetime2); 
                $this->view->v4 = $date_all->format('Y-m-d H:i:s');
            } else {
                $form->populate($formData);
            }                
        }    
    }

    public function simulationAction()
    {    
        $this->getLibrary()->Play(); 
        $this->_helper->redirector('place');
    }
    
    public function placeAction()
    {    
        $percent=$this->getLibrary()->getPlace();
        if ($percent < 100) {$this->view->v5=$percent;} else {$this->view->v5='Flight completed';}
    }
    

}



