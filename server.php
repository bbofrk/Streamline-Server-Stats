<?php
class Server {
	public $serverName, $warningMessage;
	public $stats = [];
	private $apiUrl = 'https://services.mysublime.net/st4ts/data/get/type/iq/server/';
	public function __construct($serverName) {
		$this->serverName = $serverName;
	}

	public function getStats() {
		//use @ to suppress errors
		$tmpContent = @file_get_contents($apiUrl.$serverName.'/');
		if ($tmpContent !== false) {
			$tmpServerStats = json_decode($tmpContent, true);
			foreach ($tmpServerStats as $tmpServerStat) {
				$this->stats[$tmpServerStat['data_label']] = $tmpServerStat['data_value'];
			}
		}
		else {
			$this->warningMessage = '<div class="alert alert-warning"><strong>No stats for the server!</strong></div>';
		}
		return $this->stats;
	}
}
?>
