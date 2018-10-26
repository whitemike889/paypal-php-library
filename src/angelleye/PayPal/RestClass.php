<?php 
namespace angelleye\PayPal;
use PayPal\Common\PayPalModel;
class RestClass extends PayPalModel
{
    private $_api_context;
    
    public function __construct($configArray){        
        $this->_api_context = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
        $this->set_partner_attribution_id('AngellEYE_PHPClass');
    }
        
    public function get_api_context(){
        return $this->_api_context;
    }
    
    public function set_partner_attribution_id($source){
        $this->_api_context->setConfig(array('http.headers.PayPal-Partner-Attribution-Id' => $source));
        $this->_api_context->setConfig(
                array(
                  'log.LogEnabled' => true,
                  'log.FileName' => 'F:\xampp\htdocs\PayPal.log',
                  'log.LogLevel' => 'DEBUG'
                )
        );
    }
    
    public function set_paypal_request_id($source){
        $this->_api_context->setConfig(array('http.headers.PayPal-Request-Id' => $source));
    }    
    
    /*
     *  Method set_prefer
     *  Indicates how the client expects the server to process this request.
     *  To process the request asynchronously, set this header to respond-async.
     *  If you omit this header, the API processes the request synchronously.
     *  For synchronous processing the application may levy additional checks on the number of supported items
     *  in the request and may fail the request if those limits are breached.
     */
    
    public function set_prefer($source){
        $this->_api_context->setConfig(array('http.headers.Prefer' => $source));
    }

    public function checkEmptyObject($array){
        $filter_array = array_filter($array);
        if(count($filter_array) > 0){
            return $filter_array;
        }
        else {
            return array();
        }
    }
    
    public function setArrayToMethods($array, $object) {
        if(!empty($array)){
            foreach ($array as $key => $val) {                
                if (!empty($val)) {
                    $method = 'set' . $key;
                    if (method_exists($object, $method)) {
                        $object->$method($val);
                    }
                }
            }
            return TRUE;
        }
        return;
    }
    
    public function createErrorResponse($ex){
        $returnArray['RESULT'] = 'Error';
        if($this->isJson($ex->getData())){
            $returnArray['errors'] =json_decode($ex->getData(),true);
        }
        else{
            $returnArray['errors'] = $ex->getData();
        }
        return $returnArray;
    }
    
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}