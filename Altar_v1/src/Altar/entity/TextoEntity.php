<?php

namespace Altar\entity;

use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\math\Vector3;
use pocketmine\level\Position;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

use pocketmine\entity\object\ItemEntity;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\particle\HugeExplodeParticle;
use Altar\Inicio;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class TextoEntity extends Entity {

	public const NETWORK_ID = 71;

	public $height = 0.98;
	public $width = 0.98;
	protected $temp = 0;
	private $ticks = 0;

	/*public function initEntity(): void{
	/	if(!$this->namedtag->hasTag(self::TAG_SHOW_BOTTOM, ByteTag::class)){
			$this->namedtag->setByte(self::TAG_SHOW_BOTTOM, 0);
		}
		parent::initEntity();
	}*/
	public function getConfig(){
		return Inicio::getMain()->config;
	}
	 public function onUpdate(int $ticks) : bool{
  		if($this->closed) return false;
		parent::onUpdate($ticks);
  		$this->ticks++;
		$s = $this->ticks / 20;
 		if(is_int($s)){
 		 	$this->updateTime($s);
 		}
 		return true;
 	}
 	public function getName(): string{
		return "Texto";
	}
	public function updateTime(int $seg){
		$nomeAP = $this->getConfig()->get("nome.de.cima");
		$id = $this->getConfig()->get("id.poder.max");
		$damag = $this->getConfig()->get("damage.poder.max");
		$count = $this->getConfig()->get("quantidade.poder.max");
		$custom = $this->getConfig()->get("nome.poder.max");
		$dropt = $this->getConfig()->get("tempo.de.dropar");
		$ktype = $this->getConfig()->get("tipo.key");
		$item = Item::get(131, 0, $count);
		$tag = $item->getNamedTag();
        $tag->setTag(new StringTag("chave", $ktype), true);
        $item->setNamedTag($tag);
		$item->setCustomName("§r§eChave de Ativação\n§ftipo:§7 ".$ktype);
   if($this->temp <= 0){
   	$this->temp = $this->getConfig()->get("tempo.de.dropar");
   	$this->getLevel()->dropItem(new Vector3($this->x, $this->y, $this->z), $item);
   } else {
   	$this->temp--;
   	$time = $this->temp;
		$seg = (int)($time % 60);
		$time /= 60;
		$min = (int)($time % 60);
		$time /= 60;
		$hora = (int)($time % 24);
		if($seg < 10){
			$seg = "0" . $seg;
		}
		if($min < 10){
			$min = "0" . $min;
		}
		if($hora < 10){
			$hora = "0" . $hora;
		}
		$porc = (100*(int)$this->temp)/(int)$dropt;
		$barras = "§7▌▌▌▌▌▌▌▌▌▌";
		if($porc <= 100 and $porc >= 90) $barras = "§7▌▌▌▌▌▌▌▌▌▌";
		if($porc <= 90 and $porc >= 80) $barras = "§a▌§7▌▌▌▌▌▌▌▌▌";
		if($porc <= 80 and $porc >= 70) $barras = "§a▌▌§7▌▌▌▌▌▌▌▌";
		if($porc <= 70 and $porc >= 60) $barras = "§a▌▌▌§7▌▌▌▌▌▌▌";
		if($porc <= 60 and $porc >= 50) $barras = "§a▌▌▌▌§7▌▌▌▌▌▌";
		if($porc <= 50 and $porc >= 40) $barras = "§a▌▌▌▌▌§7▌▌▌▌▌";
		if($porc <= 40 and $porc >= 30) $barras = "§a▌▌▌▌▌▌§7▌▌▌▌";
		if($porc <= 30 and $porc >= 20) $barras = "§a▌▌▌▌▌▌▌§7▌▌▌";
		if($porc <= 20 and $porc >= 10) $barras = "§a▌▌▌▌▌▌▌▌§7▌▌";
		if($porc <= 10 and $porc >= 0) $barras = "§a▌▌▌▌▌▌▌▌▌§7▌";
		if($this->temp >= 3600){
			$this->setNameTag("§r§6§lALTAR DE KEYS§r\n§r§fProxima key em {$hora}h {$min}m {$seg}s \n {$barras}\n§a\n§b\n§c\n§s\n§e\n§9\n§5\n§3");
		} else {
    $this->setNameTag("§r§6Altar de keys\n§r§fProximo item em {$min}m {$seg}s \n {$barras}\n§a\n§b\n§c\n§s\n§e\n§9\n§5\n§3");
		}
		$this->setNameTagVisible(true);
		$this->setNameTagAlwaysVisible(true);
}
}

	
}