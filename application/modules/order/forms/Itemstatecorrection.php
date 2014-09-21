<?php

class Order_Form_Itemstatecorrection extends Zend_Form {

	protected $_order_item = null;
	
	public function __construct(array $options = array()) {
		$this->_order_item = $options['order_item'];
		
		unset($options['order_item']);
		
		parent::__construct($options);
	}
	
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
		
		$buttonGroup = array();
		
		if (empty($this->_order_item->locked_render_front_preview) || empty($this->_order_item->locked_render_back_preview)) {
			$this->addElement('submit', '_preview', array(
					'class'		=> 'btn',
					'value'	    => '',
					'ignore'    => true,
					'label'     => 'Vorschau',
			));		
			$previewButton = $this->getElement('_preview');		
			$previewButton->setAttrib('onclick', 'document.getElementById("loading-indicator").style.display="block";document.getElementById("overlay").style.display="block";window.scrollTo(0,0);return true;');
			$buttonGroup[] = '_preview';
		}
		
		$this->addElement('submit', '_deny', array(
				'class'		=> 'btn',
				'value'	    => '',
				'ignore'    => true,
				'label'     => 'Korrektur',
		));
		$buttonGroup[] = '_deny';
		
        $this->addElement('submit', '_correction', array(
        	'class'		=> 'btn',
        	'value'	    => '',
            'ignore'    => true,
            'label'     => 'Freigeben',
        ));
        $buttonGroup[] = '_correction';
        
        $this->addDisplayGroup($buttonGroup, 'submit');
 
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