<?php

namespace Altar;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\nbt\tag\StringTag;
use pocketmine\level\Level;
use Altar\entity\TextoEntity;
use pocketmine\entity\Entity;
use CortexPE\entity\object\EndCrystal;

class Inicio extends PluginBase implements Listener {
	
	public $config;
	public $vl = [];
	public static $main = null;
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("Plugin iniciando...");
		$this->getLogger()->info("Plugin iniciado com sucesso!.");
		@mkdir($this->getDataFolder());
		self::$main = $this;
		Entity::registerEntity(TextoEntity::class, true, ["texto"]);
		if(!file_exists($this->getDataFolder() . "config.yml")){
			
			$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
			$arr = [
			"obs" => "O TEMPO PRECISA SER EM SEGUNDOS",
	       "tempo.de.dropar" => 20,
          "nome.de.cima" => "         §dAltar do Poder",
	      "id.poder.max" => 399,
      	  "damage.poder.max" => 0,
    	  "quantidade.poder.max" => 1,
    	  "nome.poder.max"  => "§aPoder max",
    	  "tipo.key" => "epica",
    	];
    	
	$this->config->setDefaults($arr);
	$this->config->save();
		} else {
			$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		}
	}
	
	 public static function getMain(){
  return self::$main;
 }
	
	
public function placeh(BlockPlaceEvent $event){
	$block = $event->getBlock();
	$player = $event->getPlayer();
	if($player->isOp()){
	if(isset($this->vl[$player->getName()])){
	if($block->getId() == 120){
		$event->setCancelled();
		$level = $block->getLevel();
		//$level->setBlock($block, $block, true, true);
		$player->getLevel()->loadChunk($block->x >> 4,$block->z >> 4);
        $nbt = TextoEntity::createBaseNBT($block->add(0.5, 0, 0.5));
		$entity = Entity::createEntity("texto", $block->getLevel(), $nbt);
		$entity->spawnToAll();
		$entity->setNameTagVisible(true);
		$entity->setNameTagAlwaysVisible(true);		
	}
}
}
}
public function onEntityMove(EntityMotionEvent $ev){
	if($ev->getEntity() instanceof TextoEntity){
		$ev->setCancelled();
	}
}
public function onEntity(EntityDamageEvent $ev){
	if($ev->getEntity() instanceof TextoEntity){
		$ev->setCancelled();
	}
}

public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
	if($cmd->getName() == "altar"){
		if($sender->isOp()){
		if(isset($args[0])){
			if($args[0] == "ativar"){
				$nomeAP = $this->config->get("nome.de.cima");
				$this->vl[$sender->getName()] = $args[0];
				$sender->sendMessage("§aAtivado com sucesso, agora você pode criar novos {$nomeAP} §r§acolocando blocos do portal do ender no chão.");
			}
			if($args[0] == "desativar"){
				$nomeAP = $this->config->get("nome.de.cima");
				unset($this->vl[$sender->getName()]);
				$sender->sendMessage("§cDesativado com sucesso, agora você não vai poder criar novos {$nomeAP}§r§c.");
			}
		}
	 } else {
	 	$sender->sendMessage("§cVocê não tem permissão para executar esse comando.");
	 	return true;
	 }
	}
	return true;
   }
   }