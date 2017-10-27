<?php
/**
 * Created by PhpStorm.
 * User: Spiderman
 * Machine: iMac Pro
 * Date: 2017/10/11
 * Time: 12:48
 */

namespace DamageBlock\Listeners;


use pocketmine\event\Listener;
use DamageBlock\Main;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\block\Block;
use pocketmine\entity\Effect;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FloatingTextParticle;

class Listeners implements Listener{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerChat(PlayerChatEvent $event)
    {
        $message = $event->getMessage();
        $t=$this->plugin->statusp->get("pig");
        $playerww=$event->getPlayer();

        $player=$playerww->getName();
        if($t == "1")
        {
            $lpt=$this->plugin->statusp->get("name");
            if($lpt!=$event->getPlayer()->getName())
            {
                return true;
            }
            else
            {
                if($message == "cancel")
                {
                    $this->plugin->statusp->set("pig",0);
                    $this->plugin->statusp->save();
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a成功取消操作！");
                    $event->setCancelled(true);
                    return true;
                }
                if($message == "")
                {
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §c请输入游戏上限人数");
                    $event->setCancelled(true);
                    return true;
                }
                if(!is_numeric($message))
                {
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §c错误！§a请输入数字");
                    $event->setCancelled(true);
                    return true;
                }
                else
                {
                    $pick=$this->plugin->statusp->get("gname");
                    $this->plugin->statusp->set("name",$player);
                    $this->plugin->statusp->save();
                    $this->plugin->statusp->set("pig",2);
                    $this->plugin->statusp->save();
                    $this->plugin->statusp->set("players",$message);
                    $this->plugin->statusp->save();
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §e已设置游戏 $pick 的上限人数为 $message ,§a接下来请输入奖励方块奖励指令");
                    $playerww->sendMessage(C::GREEN . "提示：%p 代表玩家,无需加/ 例如给玩家1000金币 则输入 给钱 %p 1000");
                    $playerww->sendMessage(C::RED . "(我的经济API给钱指令是给钱,给钱指令请认准你自己服务器的)");
                    $playerww->sendMessage(C::GOLD . "如需添加更多指令,请在配置文件中修改");
                    $event->setCancelled(true);
                    return true;
                }
            }
        }
        if($t == "2")
        {
            $lpt=$this->plugin->statusp->get("name");
            if($event->getPlayer()->getName()==$lpt)
            {
                if($message == "")
                {
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §c请输入奖励金钱数！");
                    $event->setCancelled(true);
                    return true;
                }
                if($message == "cancel")
                {
                    $this->plugin->statusp->set("pig",0);
                    $this->plugin->statusp->save();
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a成功取消操作！");
                    $event->setCancelled(true);
                    return true;
                }
                else
                {
                    $pick=$this->plugin->statusp->get("gname");
                    $world=$this->plugin->statusp->get("world");
                    $players=$this->plugin->statusp->get("players");

                    $this->plugin->statusp->set("pig",0);
                    $this->plugin->statusp->save();
                    $this->plugin->damageblock->set($pick,[
                        "world"=>$world,
                        "players"=>$players,
                        "bonus"=>[$message]
                    ]);
                    $this->plugin->damageblock->save();
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a已设置游戏 §d$pick §a的奖励方块奖励指令为 $message");
                    $playerww->sendMessage(C::YELLOW . "[§bDamageBlock§e] §f游戏 §d$pick §f已设置完毕");
                    $event->setCancelled(true);
                }
            }
        }
    }
    public function onPlayerMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $world = $player->getLevel();
        $block = $world->getBlock($player->floor()->subtract(0, 1));
        $qw = $this->plugin->damageblock->getAll();

        $effect21 = Effect::getEffect(Effect::HEALTH_BOOST)->setVisible(true)->setAmplifier(0)->setDuration(40);//生命提升
        $effect22 = Effect::getEffect(Effect::ABSORPTION)->setVisible(true)->setAmplifier(0)->setDuration(100);//伤害吸收
        $effect23 = Effect::getEffect(Effect::SATURATION)->setVisible(true)->setAmplifier(0)->setDuration(40);//饱和
        $effect1 = Effect::getEffect(Effect::SPEED)->setVisible(true)->setAmplifier(0)->setDuration(40);//加速.
        $effect = Effect::getEffect(Effect::SLOWNESS)->setVisible(true)->setAmplifier(0)->setDuration(40);//缓慢.
        $effect9 = Effect::getEffect(Effect::NAUSEA)->setVisible(true)->setAmplifier(0)->setDuration(40); //反胃
        $effect14 = Effect::getEffect(Effect::INVISIBILITY)->setVisible(true)->setAmplifier(0)->setDuration(40);//隐身.
        $effect15 = Effect::getEffect(Effect::BLINDNESS)->setVisible(true)->setAmplifier(0)->setDuration(40);//失明
        $effect17 = Effect::getEffect(Effect::HUNGER)->setVisible(true)->setAmplifier(0)->setDuration(40);//饥饿.
        $effect18 = Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40);//变弱.
        $effect19 = Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(40);//中毒.
        $effect20 = Effect::getEffect(Effect::WITHER)->setVisible(true)->setAmplifier(0)->setDuration(40);//凋零.
        $effect7 = Effect::getEffect(Effect::HARMING)->setVisible(true)->setAmplifier(0)->setDuration(40);//伤害.
        $effect3 = Effect::getEffect(Effect::HASTE)->setVisible(true)->setAmplifier(0)->setDuration(40);//迅捷.
        $effect8 = Effect::getEffect(Effect::JUMP)->setVisible(true)->setAmplifier(0)->setDuration(40);//跳跃.
        $effect6 = Effect::getEffect(Effect::HEALING)->setVisible(true)->setAmplifier(0)->setDuration(40);//愈合.
        $effect4_2 = Effect::getEffect(Effect::MINING_FATIGUE)->setVisible(true)->setAmplifier(0)->setDuration(40);//疲劳
        $effect5 = Effect::getEffect(Effect::STRENGTH)->setVisible(true)->setAmplifier(0)->setDuration(40);//力量
        $effect10 = Effect::getEffect(Effect::REGENERATION)->setVisible(true)->setAmplifier(0)->setDuration(40);//再生
        $effect11 = Effect::getEffect(Effect::DAMAGE_RESISTANCE)->setVisible(true)->setAmplifier(0)->setDuration(40);//抗性提升
        $effect12 = Effect::getEffect(Effect::FIRE_RESISTANCE)->setVisible(true)->setAmplifier(0)->setDuration(40);//抗火
        $effect13 = Effect::getEffect(Effect::WATER_BREATHING)->setVisible(true)->setAmplifier(0)->setDuration(40);//水下呼吸
        $effect16 = Effect::getEffect(Effect::NIGHT_VISION)->setVisible(true)->setAmplifier(0)->setDuration(40);//夜视
        foreach($qw as $a=>$link)
        {
            $pos=$this->plugin->damageblock->get($a);
            if($link["world"] == $world->getName())
            {
                switch($block->getId())
                {
                    case Block::PLANKS:
                        $player->sendTitle( C::GREEN . "你安全了！");
                        break;
                    case Block::STONE:
                        $player->sendTitle(C::WHITE . "Go！GO！Go！");
                        break;
                    case Block::GRASS:
                        $player->sendTitle(C::GOLD . "进入草丛,已隐身");
                        $player->addEffect($effect14);
                        break;
                    case Block::LAPIS_BLOCK:
                        $player->sendTitle(C::RED . "获得一瓶红牛,加速！！");
                        $player->addEffect($effect1);
                        break;
                    case Block::GOLD_BLOCK:
                        $player->sendTitle(C::DARK_GREEN . "啊！好痛啊！");
                        $player->addEffect($effect7);
                        break;
                    case Block::IRON_BLOCK:
                        $player->sendTitle(C::DARK_GRAY . "你是一只兔子！");
                        $player->addEffect($effect8);
                        break;
                    case Block::HARDENED_CLAY:
                    case Block::STAINED_CLAY:
                    case Block::STAINED_HARDENED_CLAY:
                        $player->sendTitle(C::AQUA . "凋零！");
                        $player->addEffect($effect20);
                        break;
                    case Block::NETHERRACK:
                        $player->sendTitle(C::DARK_RED . "快跑！你会中毒的！");
                        $player->addEffect($effect19);
                        break;
                    case Block::SANDSTONE:
                        $player->sendTitle(C::GREEN . "你变low了！");
                        $player->addEffect($effect18);
                        break;
                    case Block::REDSTONE_BLOCK:
                        $player->sendTitle(C::GOLD . "饿货.来条士力架！");
                        $player->addEffect($effect17);
                        break;
                    case Block::ICE:
                        $player->sendTitle(C::DARK_BLUE . "哈哈哈哈~~你跑不动了！");
                        $player->addEffect($effect);
                        break;
                    case Block::DIAMOND_BLOCK:
                        $player->sendTitle(C::YELLOW . "算你有眼光,知道选钻石块",C::GOLD ."生命提升!");
                        $player->addEffect($effect21);
                        break;
                    case Block::OBSIDIAN:
                        $player->sendTitle(C::DARK_GRAY. "想挖黑曜石？",C::YELLOW ."试试快速挖掘(滑稽)");
                        $player->addEffect($effect3);
                        break;
                    case Block::BEDROCK:
                        $player->sendTitle(C::GRAY . "冥想...！");
                        $player->addEffect($effect6);
                        break;
                    case Block::PUMPKIN:
                        $player->sendTitle(C::GOLD . "嗯努阿努阿奴啊",C::DARK_GRAY. "饱和up");
                        $player->addEffect($effect23);
                        break;
                    case Block::SOUL_SAND:
                        $player->sendTitle(C::DARK_GRAY . "灵魂沙给你伤害吸收~");
                        $player->addEffect($effect22);
                        break;
                    case Block::WOOL:
                        $player->sendTitle(C::GOLD . "哈哈哈头晕了吧~","你以为这是奖励方块?");
                        $player->addEffect($effect9);
                        break;
                    case Block::SNOW_BLOCK:
                        $player->sendTitle(C::DARK_BLUE . "眼瞎吧！","你竟然把这个当做奖励方块");
                        $player->addEffect($effect15);
                        break;
                    case Block::COAL_BLOCK:
                        $player->sendTitle(C::YELLOW . "想要煤块？矿挖多了吧","试一试挖掘疲劳~");
                        $player->addEffect($effect4_2);
                        break;
                    case Block::MELON_BLOCK:
                        $player->sendTitle(C::GRAY . "嗯呢呢,西瓜给予你力量！");
                        $player->addEffect($effect5);
                        break;
                    case Block::RED_SANDSTONE:
                        $player->sendTitle(C::LIGHT_PURPLE . "再生！");
                        $player->addEffect($effect10);
                        break;
                    case Block::END_STONE:
                        $player->sendTitle(C::GOLD . "抗性提升");
                        $player->addEffect($effect11);
                        break;
                    case Block::WOOD:
                    case Block::WOOD2:
                        $player->sendTitle(C::DARK_RED . "能被火烧的木头","想不到它给你了抗火效果");
                        $player->addEffect($effect12);
                        break;
                    case Block::STONE_BRICKS:
                    case Block::STONE_BRICK:
                        $player->sendTitle(C::DARK_AQUA . "想不到吧！水下呼吸~");
                        $player->addEffect($effect13);
                        break;
                    case Block::SEA_LANTERN:
                        $player->sendTitle(C::GRAY . "亮晶晶的海晶石","照亮了你的双眼！");
                        $player->addEffect($effect16);
                        break;
                    case Block::NETHER_BRICKS:
                    case Block::NETHER_BRICK_BLOCK:
                        $player->sendTitle(C::RED . "来自地狱的惩罚",C::GOLD ."燃烧吧@");
                        $player->setOnFire(2);
                        break;
                }
            }
        }
    }
    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        $world = $entity->getLevel();
        $block = $world->getBlock($entity->floor()->subtract(0, 1));
        $qw = $this->plugin->damageblock->getAll();
        foreach($qw as $link)
        {
            if($link["world"] == $world->getName())
            {
                switch($block->getId())
                {
                    case Block::PLANKS:
                        $event->setCancelled(true);
                        break;
                }
            }
        }
    }
    public function onTouch(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $world = $player->getLevel();
        $block = $event->getBlock();
        $qw = $this->plugin->damageblock->getAll();

        foreach($qw as $a=>$link)
        {
            $pos=$this->plugin->damageblock->get($a);
            if($link["world"] == $world->getName())
            {
                switch($block->getId())
                {
                    case Block::QUARTZ_BLOCK:
                        if(count($world->getPlayers()) < $pos["players"])
                        {
                            $player->sendTitle(C::YELLOW . "需" .$pos["players"] ."名玩家同时在线","才能获取奖励");
                            sleep(2);
                        }
                        else
                        {
                            foreach($pos["bonus"] as $command) {
                                $this->plugin->getServer()->dispatchCommand($event->getPlayer(),str_replace("%p",$player->getName(),$command));
                                $player->teleport(new Vector3($player->getX() + 1, $player->getY(), $player->getZ()));
                                $player->sendTitle(C::BOLD . C::YELLOW . "你正站在 §r§cBonusBlock §e移动！");
                            }

                        }
                        break;
                }
            }
        }
    }
}