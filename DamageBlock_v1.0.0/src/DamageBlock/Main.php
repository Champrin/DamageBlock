<?php

namespace DamageBlock;
/**
 * Created by PhpStorm.
 * User: Spiderman
 * Machine: iMac Pro
 * Date: 2017/10/11
 * Time: 15:31
 */

use DamageBlock\Listeners\Listeners;
use DamageBlock\Commands\Commands;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\config;


class Main extends PluginBase
{
    public $damageblock;
    public $statusp;
    public $config;

    public function onEnable()
	{
        if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") == null)
        {
            $this->getLogger()->warning("DamageBlock 监测到您没有安装经济核心，请安装经济核心,否则插件某些功能无法使用！");
            $this->getLogger()->warning("DamageBlock 除需要经济核心的功能外，其他功能已全部加载！");
        }
        else
        {
            $this->getLogger()->warning("DamageBlock 监测到您已安装经济核心，相关功能已加载！");
            $this->getLogger()->warning("DamageBlock §c全部已加载完毕！");
        }

        @mkdir($this->getDataFolder());
        $this->damageblock = new Config($this->getDataFolder()."DamageBlock.yml", Config::YAML, array());
        $this->statusp = new Config($this->getDataFolder()."temp.wtp",Config::YAML,array("pig" => "0" ));

        $this->getServer()->getPluginManager()->registerEvents(new Listeners($this),$this);
        $this->getCommand("db")->setExecutor(new Commands($this),$this);
    }
    public function onDisable()
    {
        $this->getLogger()->info(C::AQUA . "     DamageBlock §c已关闭,感谢使用");
    }
}
?>
