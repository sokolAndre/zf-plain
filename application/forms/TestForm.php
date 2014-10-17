<?php

class Application_Form_TestForm extends Zend_Form
{

    public function init()
    {
         $this->setName('test');
         $isEmptyMessage = 'Please fill in the textarea';
         $isGreaterMessage = 'inteval must be greater than 0';
         $user_int = new Zend_Form_Element_Text('interval');
         $user_int->setLabel('Enter the number of the interval (1..)')
                  ->addFilter('Int')
                  ->addValidator('NotEmpty', true,
	                array('messages' => array('isEmpty' => $isEmptyMessage))
                   )
                  ->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)),true);
         $user_point = new Zend_Form_Element_Text('point');
         $user_point->setLabel('Enter the number of the point of arrival. The point of departure - 0')
                  ->addFilter('Int')
                  ->addValidator('NotEmpty', true,
	                array('messages' => array('isEmpty' => $isEmptyMessage))
                   )
                  ->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)),true);
         
         $datetime_d = new Zend_Form_Element_Text('datetime_d');
         $datetime_d->setLabel('Enter the date/time of departure')
                    ->addValidator('NotEmpty', true,
	                array('messages' => array('isEmpty' => $isEmptyMessage))
                   );
         
         
         $test = new Zend_Form_Element_Submit('start');
         $test->setLabel('Test')
              ->setAttrib('class', 'f-bu f-bu-default');       
	        
         $this->addElements(array($user_int, $user_point, $datetime_d, $test));
         
    }


}

