<?php 
namespace place;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\scheduler\Task;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
class Fi extends PluginBase implements Listener{
	private static $ChatCnt = 5;
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onplace(BlockPlaceEvent $event){
		$block = $event->getBlock();
		$player = $event->getPlayer();
		$player->getInventory()->addItem(Item::get(Item::CARROT,0,1)->setCustomName("§l§b[§f마법당근§b]"));
		$this->getScheduler()->scheduleDelayedTask(new Delayed($block), 0.01);
		$player->sendMessage("남은 기회".self::$ChatCnt,self::$ChatCnt--);
		if(self::$ChatCnt < 0)
		{
			$player->kick("블럭을 너무 많이 설치 하셨어요..");
		}
	}
	public function onJoin(PlayerJoinEvent $event){
		$event->setJoinMessage("블럭설치제한 플러그인 제작자:PLcode(리모콘)");
	}
	public function onExit(PlayerQuitEvent $event){
		self::$ChatCnt=5;
	}
}

class Delayed extends Task{
	private $block;
	
	public function __construct(Block $block){
		$this->block = $block;
	}
	public function onRun(int $currentTick){
		$this->block->getLevel()->setBlock($this->block, Block::get(Block::AIR));
	}
}
?>