<?php
class MyException extends Exception{
    public function __construct($error = ''){
        parent::__construct();
        $this->processError($error);
    }
    /*
     * �Զ����쳣������
     */
    public function processError($error = ''){
        echo $error;
    }
}
?>