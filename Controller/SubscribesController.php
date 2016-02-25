<?php
App::uses('AppController', 'Controller');
class SubscribesController extends AppController {

	public $components = array('MailChimp');
	
	/**
	 * Generic add action
	 */
	public function add() {
		
		if ($this->request->is('post')) {
		
			$this->Subscribe->create();

			if ($this->Subscribe->save($this->request->data)) {
					
				// set data including *|MERGE|* tags
				$data = array(
					'email_address'=>$this->request->data['Subscribe']['email'],
					'status'=>'subscribed',
					'merge_fields'=>array(
								'FNAME'=>$this->request->data['Capture']['first_name'],
								'LNAME'=>$this->request->data['Capture']['last_name']
						)
				);
				
				$result = $this->MailChimp->listsMembers('INSERT_MAILCHIMP_LIST_ID', $data);
					
			}
		}
	}
		
}