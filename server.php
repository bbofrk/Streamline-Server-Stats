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
		$tmpContent = @file_get_contents($this->apiUrl.$this->serverName.'/');
		if ($tmpContent === false || $tmpContent == '[]') {
			$this->warningMessage = '<div id="stats-warning" class="alert alert-warning"><strong>No stats for the server!</strong></div>';
		}
		else {
			$tmpServerStats = json_decode($tmpContent, true);
			foreach ($tmpServerStats as $tmpServerStat) {
				$this->stats[$tmpServerStat['data_label']] = $tmpServerStat['data_value'];
			}
		}
	}
}

$server = new Server($_POST['name']);
$server->getStats();
echo json_encode($server);

?>
