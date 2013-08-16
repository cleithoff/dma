<?php

class Order_Form_Itemstatedeny extends Zend_Form {
	
	public function init() {
		$this->setMethod('post');
		
		// Das Kommentar Element hinzuf�gen
		$this->addElement('textarea', 'comment', array(
				'label'      => 'Bitte ein Kommentar:',
				'required'   => true,
				/*'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 20)),
				),*/
				'filters'    => array(
						array('filter' => 'StripTags'),
				),
		));
				
 		// Ein Captcha hinzuf�gen
        $this->addElement('captcha', 'captcha', array(
            'label'      => "Bitte die 5 Buchstaben eingeben die unterhalb "
                          . "angezeigt werden:",
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));
 
        // Den Submit Button hinzuf�gen
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Senden',
        ));
 
        // Und letztendlich etwas CSRF Protektion hinzuf�gen
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
	}
	
}