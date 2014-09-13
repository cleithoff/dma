<?php

class Order_Form_Itemstatecorrection extends Zend_Form {
	
	public function init() {
		$this->setMethod('post');
		
		// Das Kommentar Element hinzufügen
		$this->addElement('textarea', 'comment', array(
				'label'      => 'Kommentar:',
				//'required'   => true,
				/*'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 20)),
				),*/
				'filters'    => array(
						array('filter' => 'StripTags'),
				),
		));
				
 		// Ein Captcha hinzufügen
        /*$this->addElement('captcha', 'captcha', array(
            'label'      => "Bitte die 5 Buchstaben eingeben die unterhalb "
                          . "angezeigt werden:",
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));*/
		
		$this->addElement('submit', '_preview', array(
				'class'		=> 'btn',
				'value'	    => '',
				'ignore'    => true,
				'label'     => 'Korrektur',
		));
		
        $this->addElement('submit', '_correction', array(
        	'class'		=> 'btn',
        	'value'	    => '',
            'ignore'    => true,
            'label'     => 'Freigeben',
        ));
        
        $this->addDisplayGroup(array('_preview', '_correction'), 'submit');
 
        // Und letztendlich etwas CSRF Protektion hinzufügen
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
	}
	
	public function render(Zend_View_Interface $view = NULL)
	{
		foreach($this->getElements() as $element) {
			if($element->hasErrors()) {
				$element->setAttrib('class', 'error');
			}
		}
		return parent::render();
	}
	
}