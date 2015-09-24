<?php

namespace Legoboy\TNT;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use pocketmine\level\Level;
use pocketmine\level\Position;

use pocketmine\math\Vector3;

class Loader extends PluginBase{

	  /** @var string AUTHOR Plugin author(s) */
	  const AUTHOR = "Legoboy0215";
	
	  /** @var string VERSION Plugin version */
	  const VERSION = "1.0.0";
	
	  /** @var string PREFIX Plugin message prefix */
	  const PREFIX = "[TNT]";
	
    public function onEnable(){
        $this->getLogger()->info(TextFormat::GREEN . "TNTParticle started!");
    }
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if(strtolower($command->getName()) === "tnt"){
			if(!isset($args[0]) || !isset($args[1]) || !isset($args[2])) return false;
			if($sender instanceof Player){
				if(strtolower($args[0]) === "spawn"){
					$particle = new TNTParticle($sender, $sender->getLevel(), $args[1], (int) $args[2]);
					$sender->getlevel()->addParticle($particle);
					$sender->sendMessage("Spawned TNTParticle with the name of " . $args[1]);
					return true;
				}
			}else{
				$sender->sendMessage(TextFormat::RED . "Run this command in-game please!");
				return true;
			}
		}
	}
}
