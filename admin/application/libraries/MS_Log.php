<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MS_Log extends CI_Log {

	/**
	 * debug 信息
	 */
	function debug($msg, $filename = null, $linenum = null) {
		if ($filename && $linenum) {
			$msg = $msg . ' --> ' . $filename . ' ' . $linenum;
		}
		$this->write_log('MSDEBUG', $msg);
	}

	/**
	 * 记录日志
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE) {
		if ($this->_enabled === FALSE) {
			return FALSE;
		}

		$level = strtoupper($level);

		if ($level != 'MSDEBUG' && !isset($this->_levels[$level]) OR ( $this->_levels[$level] > $this->_threshold)) {
			return FALSE;
		}

		$filepath	 = $this->_log_path . date('Ymd') . '.log';
		$message	 = '';

		if (!$fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
			return FALSE;
		}

		$message .= $level . ' ' . (($level == 'INFO') ? ' -' : '-') . ' ' . date($this->_date_fmt) . ' ' . $msg . "\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, FILE_WRITE_MODE);
		return TRUE;
	}

}
