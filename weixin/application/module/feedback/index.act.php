<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 已经反馈
 * http://host/weixin/test.php/feedback
 */
class FeedbackIndex extends Action {

	function __construct() {
		parent::__construct();

		$this->wxauth();
	}

	function on_index() {
		$feedback = isset($_REQUEST['Feedback']) ? $_REQUEST['Feedback'] : array();
		if ($feedback && trim($feedback['content']) != '') {
			$data = array(
				'content'		 => $feedback['content'],
				'username'		 => $feedback['username'],
				'mobile'		 => $feedback['mobile'],
				'user_id'		 => $this->user_id,
				'create_time'	 => date('Y-m-d H:i:s'),
			);
			$this->db->insert('feedback', $data);
			$this->view->assign('success_message', '您的建议我们已经收到，衷心的感谢您对我们的支持！谢谢！');
		}
		$this->view->display('feedback/feedback_index.html');
	}

}
