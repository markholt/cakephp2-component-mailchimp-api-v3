<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
class MailChimpComponent extends Component {

	const POST_TIMEOUT = 30;
	private $__apiUrl;
	private $__apiKey;

	/**
	 * Subscribes an email to a list 
	 *
	 * @param string $listId list id
	 * @param string $params JSON data for subscribe
	 *
	 * @return response in JSON
	 *
	 * @throws Exception
	 */
    public function listsMembers($listId, $params) {
    	
    	// check for mandatory data keys
    	if(!key_exists("email_address", $params)) {
    		throw new Exception(__("Email is required"));
    	}
    	
    	if(!key_exists("status", $params)) {
    		throw new Exception(__("Status is required"));
    	}
    	
    	return $this->__makeCall('/lists/'.$listId.'/members', $params);
    	
    }

    
    /**
     * Make a call to MailChimp api
     *
     * @param string $method we want to call in Api
     * @param string $params JSON data for POST request
     *
     * @return response in JSON
     * @throws Exception
     */    
    private function __makeCall($method, $params) {
    
    	$HttpSocket = new HttpSocket(array('timeout' => MailChimpComponent::POST_TIMEOUT));
    	$HttpSocket->configAuth('Basic', 'anystring', $this->__apiKey);
    
    	// set headers
    	$request = array('header' => array('Content-Type' => 'application/json'));
    	
    	// post data
    	$result = $HttpSocket->post($this->__apiUrl.$method, json_encode($params), $request);
  
    	return $result;
    }    
    
    
	/**
	 * initialize
	 *
	 * @param Controller $controller controller
	 *
	 * @return void
	 */
	public function initialize(Controller $controller) {
		$this->__apiUrl = Configure::read('Chimp.url');
		$this->__apiKey = Configure::read('Chimp.key');
	}
}
