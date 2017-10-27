<?php
/**
 * Created by PhpStorm.
 * User: Spiderman
 * Machine: iMac Pro
 * Date: 2017/10/11
 * Time: 12:31
 */

namespace DamageBlock\Commands;

use onebone\economyapi\EconomyAPI;
use DamageBlock\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;

class Commands implements CommandExecutor{

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
    public function help($sender)
    {
        $sender->sendMessage(C::YELLOW ."[§bDamageBlock§e]=====================");
        $sender->sendMessage(C::DARK_AQUA . "/db help               §c查看帮助");
        $sender->sendMessage(C::AQUA .       "/db reload              §c重载配置文件");
        $sender->sendMessage(C::WHITE .      "/db add 游戏名 游戏世界  §c添加游戏世界");
        $sender->sendMessage(C::GRAY .       "/db list                §c查看游戏列表");
        $sender->sendMessage(C::YELLOW .     "/db remove 游戏名       §c删除游戏");
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        switch($command->getName())
        {
            case "db":
                if(count($args) === 0)
                {
                    $this->help($sender);
                    return true;
                }
                if(isset($args[0]))
                {
                    switch($args[0])
                    {
                        case "add":
                            if(isset($args[1]))
                            {
                                if(isset($args[2]))
                                {
                                    if(!$this->plugin->getServer()->isLevelGenerated($args[2]))
                                    {
                                        $sender->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a地图 §a$args[2] §a不存在！");
                                        return true;
                                    }
                                    if($this->plugin->damageblock->exists($args[1]))
                                    {
                                        $sender->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a游戏名字 §a$args[1] §a已存在！");
                                        return true;
                                    }
                                    if($this->plugin->damageblock->exists($args[2]))
                                    {
                                        $sender->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a游戏地图 §a$args[1] §a已创建！不需要再次创建！");
                                        return true;
                                    }
                                    else
                                    {
                                        $gname=$args[1];
                                        $worldname=$args[2];
                                        $name=$sender->getName();
                                        $this->plugin->statusp->set("pig",1);
                                        $this->plugin->statusp->save();
                                        $this->plugin->statusp->set("name",$name);
                                        $this->plugin->statusp->save();
                                        $this->plugin->statusp->set("world",$args[2]);
                                        $this->plugin->statusp->save();
                                        $this->plugin->statusp->set("gname",$args[1]);
                                        $this->plugin->statusp->save();
                                        $sender->sendMessage(C::YELLOW . "[§bDamageBlock§e] §a已添加游戏名字为 §f$gname ,§a游戏世界为 §f$worldname ，§a接下来请在聊天框内直接输入游戏玩家下限人数");
                                        $sender->sendMessage(C::RED .     "提示：如想撤销这次操作请在聊天框内输入cancel即可");
                                        return true;
                                    }
                                }
                                else
                                {
                                    $sender->sendMessage(C::YELLOW .     "[§bDamageBlock§e] 您未输入游戏世界  ");
                                    return true;
                                }
                            }
                            else
                            {
                                $sender->sendMessage(C::YELLOW .     "[§bDamageBlock§e] 用法 /db add 游戏名 游戏世界  ");
                                return true;
                            }
                        case "reload":
                            $this->plugin->statusp->reload();
                            $this->plugin->damageblock->reload();
                            $sender->sendMessage(C::YELLOW . "[§bDamageBlock§e]  §f配置重载完成");
                            return true;
                        case "help":
                            $this->help($sender);
                            return true;
                        case "list":
                            $qw = $this->plugin->damageblock->getAll();
                            $sender->sendMessage(C::YELLOW ."[§bDamageBlock§e]§a=============§f§l游戏列表§r§a==============");
                            foreach($qw as $a=>$b)
                            {
                                $pos = $this->plugin->damageblock->get($a);
                                $d = $pos["world"];
                                $b = $pos["players"];
                                $sender->sendMessage(C::GOLD . "游戏名字:§a$a §6游戏世界:§a$d §6下限人数:§a$b");
                            }
                            return true;
                        case "remove":
                            if(isset($args[1]))
                            {
                                if($this->plugin->damageblock->exists($args[1]))
                                {
                                    $qw = $this->plugin->damageblock->getAll();
                                    $po = $args[1];
                                    unset($qw[$po]);
                                    $this->plugin->damageblock->remove($po);
                                    $this->plugin->damageblock->save();
                                    $sender->sendMessage(C::YELLOW ."[§bDamageBlock§e] §a成功删除！");
                                    return true;
                                }
                                else
                                {
                                    $sender->sendMessage(C::YELLOW ."[§bDamageBlock§e] §a游戏名字 §a$args[1] §a不存在！");
                                    return true;
                                }

                            }
                            else
                            {
                                $sender->sendMessage(C::YELLOW ."[§bDamageBlock§e] 您未输入要删除的游戏名字或者游戏名字输入错误");
                                return true;
                            }
                        default:
                            $this->help($sender);
                            return true;
                    }

                }
        }
    }
}