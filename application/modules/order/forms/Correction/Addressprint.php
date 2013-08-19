<?php

class Order_Form_Correction_Addressprint extends Order_Form_Itemstatecorrection {
	
	public function isValid($data) {
		$result = parent::isValid($data);
		
		if (!$result) return $result;
		
		$firstFound = false;
		$lines = array();
		for ($i = 1; $i <= 7; $i++) {
			$data['line' . $i] = strip_tags(trim($data['line' . $i]));
			if (!empty($data['line' . $i])) {
				if (!$firstFound) {
					$firstFound = true;
					if ($data['line' . $i] != "Fleurop-Fachgeschäft") {
						$this->getElement('line' . $i)->addError('Erste benutzte Zeile muss "Fleurop-Fachgeschäft" lauten.');
						$result = false;
					}
				}
				array_push($lines, $data['line' . $i]);				
			}
			unset($data['line' . $i]);
			$this->getElement('line' . $i)->setValue(null);
		}
		
		$j = 7 - count($lines);
		foreach ($lines as $line) {
			$j++;
			$data['line' . $j] = $line;
			$this->getElement('line' . $j)->setValue($line);
		}
		
		return $result;
	}
	
	public function init() {

		$this->addElement('text', 'line1', array(
				'label'      => 'Zeile 1:',
				//'required'   => true,
				'validators' => array(
				 		array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));
		
		$this->addElement('text', 'line2', array(
				'label'      => 'Zeile 2:',
				//'required'   => true,
				'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));
				
		$this->addElement('text', 'line3', array(
				'label'      => 'Zeile 3:',
				//'required'   => true,
				'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));
		
		$this->addElement('text', 'line4', array(
				'label'      => 'Zeile 4:',
				//'required'   => true,
				'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));

		$this->addElement('text', 'line5', array(
				'label'      => 'Zeile 5:',
				//'required'   => true,
				'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));

		$this->addElement('text', 'line6', array(
				'label'      => 'Zeile 6:',
				'required'   => true,
				'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));

		$this->addElement('text', 'line7', array(
				'label'      => 'Zeile 7:',
				'required'   => true,
				'validators' => array(
						array('validator' => 'StringLength', 'options' => array(0, 60)),
				),
				'filters'    => array(
						array('filter' => 'StripTags'),
						array('filter' => 'StringTrim'),
				),
		));
		
        parent::init();
	}
	
}