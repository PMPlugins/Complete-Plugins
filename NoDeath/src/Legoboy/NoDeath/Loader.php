<?php

namespace Legoboy\NoDeath;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use pocketmine\level\Level;
use pocketmine\level\Position;

use pocketmine\math\Vector3;

use pocketmine\item\Item;

class Loader extends PluginBase implements Listener{

	  /** @var string AUTHOR Plugin author(s) */
	  const AUTHOR = "Legoboy";
	
	  /** @var string VERSION Plugin version */
	  const VERSION = "1.0.0";
	
	  /** @var string PREFIX Plugin message prefix */
	  const PREFIX = "[NoDeath]";
	
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		$this->setting = new Config($this->getDataFolder() . "settings.yml", Config::YAML, array(
			"death-drop-item" => true,
			"clear-inv-after-death" => true,
			"health-after-death" => 20,
			"death-player-message" => "You respawned without pressing the respawn button!",
		)
		);
		$this->setting->save();
        $this->getLogger()->info(TextFormat::GREEN . "NoDeath has been enabled!");
    }
    
	public function onDeath(EntityDamageEvent $event){
		$player = $event->getEntity();
		if($player instanceof Player){
			if($event->getDamage() >= $player->getHealth()){
				$event->setCancelled(true);
				$stuff = $player->getInventory()->getContents(); // Item[]
				if($event instanceof EntityDamageByEntityEvent && $this->setting->get("death-drop-item")){
					$event->getDamager()->getInventory()->sendContents($stuff);
				}
				if($this->setting->get("clear-inv-after-death")){
					$player->getInventory()->setContents(array(Item::get(0, 0, 0)));
				}
				$this->getServer()->getPluginManager()->callEvent(new PlayerDeathEvent($player, $stuff, $player->getName() . " died."));
				$player->setHealth((int) $this->setting->get("health-after-death"));
				$player->sendMessage($this->setting->get("death-player-message"));
				$spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
				$player->teleport($spawn);
			}
		}
	}
	
    public function onDisable(){
        $this->setting->save();
    }
}
