<?php

class Application_Form_UploadForm extends Zend_Form
{

    public function init()
    {
        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')  
             ->setDestination('../data')
             ->setRequired(true)
             ->addValidator('Extension', false, 'json' ); 
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Upload')
               ->setAttrib('class', 'f-bu f-bu-default');
 
        $this->addElements(array($file, $submit));
    }


}

