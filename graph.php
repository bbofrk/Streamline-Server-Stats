<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Server Stats</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- JQuery -->
	<script
	src="https://code.jquery.com/jquery-3.2.1.min.js"
	integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>

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

	//use @ to suppress errors
	$tmpServers = @file_get_contents('https://services.mysublime.net/st4ts/data/get/type/servers/');
	if ($tmpServers === false) {
		//if there is no servers from the api
		?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger"><strong>No servers from the api</strong></div>
				</div>
			</div>
		</div>
		<?php
		exit();
	} else {
		$returnServers = json_decode($tmpServers, true);
	}

	$servers = [];
	foreach ($returnServers as $i => $tmpArray) {
		foreach ($tmpArray as $serverName) {
			$servers[$i] = new Server($serverName);
		}
	}

	?>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<h1>Server Stats</h1>
			</div> <!-- col -->
		</div> <!-- row -->

		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Servers  <span class="caret"></span></button>
					<ul class="dropdown-menu">
						<?php
						foreach ($servers as $server) {
							echo '<li><a href="#">';
								echo $server->serverName;
							echo '</a></li>';
						}
						?>
					</ul>
				</div> <!-- dropdown -->
			</div> <!-- col -->
		</div> <!-- row -->
	</div> <!-- container -->

	<script type="text/javascript">
		//Change the text based on the selection
		$(function(){
			$(".dropdown-menu li a").on('click', function() {
				$(".btn:first-child").text($(this).text());
				$(".btn:first-child").val($(this).text());
			});
		});
	</script>
</body>
</html>
