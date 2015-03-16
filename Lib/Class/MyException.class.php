<?php
class MyException extends Exception{
    public function __construct($error = ''){
        parent::__construct();
        $this->processError($error);
    }
    /*
     * 自定义异常处理累
     */
    public function processError($error = ''){
        echo $error;
    }
}
?>