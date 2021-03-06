<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class posteventTest extends CIUnit_TestCase {
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp() {
        parent::setUp();
        $this -> CI = set_controller('ums');
        $this -> dbfixt('razor_channel_product');
        $this -> dbfixt('razor_event_defination');
    }
    
    public function tearDown() {
        parent::tearDown();
        $tables = array(
            'razor_channel_product'=>'razor_channel_product',
            'razor_event_defination'=>'razor_event_defination'
        );

        //$this->dbfixt_unload($tables);
    }
    public function testPostEvent() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_event/ok.json';
        ob_start();
        $this->CI->eventlog();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":1,"msg":"ok"}', 
            $output
        );
    }

    public function testPostEvent1() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->eventlog();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-3,"msg":"Invalid content from php:\/\/input."}', 
            $output
        );
    }
    
    public function testPostEvent2() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->eventlog();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-4,"msg":"Parse json data failed."}', 
            $output
        );
    }
    
    public function testPostEvent3() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_event/noappkey.json';
        ob_start();
        $this->CI->eventlog();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-5,"msg":"Appkey is not set in json."}', 
            $output
        );
    }
    
    public function testPostEvent4() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_event/errorappkey.json';
        ob_start();
        $this->CI->eventlog();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-1,"msg":"Invalid appkey:invalid_appkey_00000"}', 
            $output
        );
    }

}
?>