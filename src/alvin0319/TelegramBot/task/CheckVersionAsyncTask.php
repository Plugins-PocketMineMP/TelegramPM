<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\task;

use alvin0319\TelegramBot\util\Promise;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;

class CheckVersionAsyncTask extends AsyncTask{

	public const POGGIT_URL = "https://poggit.pmmp.io/releases.json?name=TelegramPM";

	protected $version;

	public function __construct(Promise $promise, string $version){
		$this->storeLocal($promise);
		$this->version = $version;
	}

	public function onRun() : void{
		$url = Internet::getURL(self::POGGIT_URL);
		if(!is_string($url)){
			$this->setResult(null);
			return;
		}
		$data = json_decode($url, true);
		if(!is_array($data)){
			$this->setResult(null);
			return;
		}
		$highestVersion = "";
		$artifactUrl = "";
		$api = "";

		$now = $this->version;

		$newVersion = false;

		foreach($data as $release){
			if(version_compare($now, $release["version"], ">=")){
				continue;
			}
			$highestVersion = $release["version"];
			$artifactUrl = $release["artifact_url"];
			$api = $release["api"][0]["from"] . " - " . $release["api"][0]["to"];
			$newVersion = true;
		}
		$this->setResult([
			$highestVersion,
			$artifactUrl,
			$api,
			$newVersion
		]);
	}

	public function onCompletion(Server $server) : void{
		/** @var Promise $promise */
		$promise = $this->fetchLocal();
		if(is_array($this->getResult())){
			$promise->resolve($this->getResult());
		}else{
			$promise->reject("Unable to resolve host " . self::POGGIT_URL);
		}
	}
}