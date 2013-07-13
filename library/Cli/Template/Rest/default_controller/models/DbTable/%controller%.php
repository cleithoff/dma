<?php

class %module%_Model_DbTable_%controller% extends Zend_Db_Table_Abstract
{

    protected $_name = '%table%';
    protected $_rowClass = '%module%_Model_%controller%';
%depentendtables%    
%referencemap%

}