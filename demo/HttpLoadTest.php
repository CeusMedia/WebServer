<?php
class HttpLoadTest extends \CLI_Fork_Abstract{

	protected $numberForks;
	protected $numberRequests;

	public function __construct($numberForks, $numberRequests) {
		parent::__construct(FALSE);
		$this->numberForks		= $numberForks;
		$this->numberRequests	= $numberRequests;
	}

	protected function runInParent($arguments = array()) {}

	protected function runInChild($arguments = array()) {
		$url	= array_shift($arguments);
		for($i=0; $i<$this->numberRequests; $i++) {
			file($url);
		}
	}

public function testUrl($url) {
		$clock		= new \Alg_Time_Clock;
		try {
			for($i=0; $i<$this->numberForks; $i++) {
				print "Fork #".$i.PHP_EOL;
				$this->fork($url);
			}
		}
		catch(\Exception $e) {
			die($e->getMessage());
		}
		print 'Time: '.$clock->stop(3, 1).' ms'.PHP_EOL;
		print PHP_EOL;
	}
}
?>
