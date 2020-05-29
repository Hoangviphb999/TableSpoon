<?php

/**
 *
 * MMP""MM""YMM               .M"""bgd
 * P'   MM   `7              ,MI    "Y
 *      MM  .gP"Ya   ,6"Yb.  `MMb.   `7MMpdMAo.  ,pW"Wq.   ,pW"Wq.`7MMpMMMb.
 *      MM ,M'   Yb 8)   MM    `YMMNq. MM   `Wb 6W'   `Wb 6W'   `Wb MM    MM
 *      MM 8M""""""  ,pm9MM  .     `MM MM    M8 8M     M8 8M     M8 MM    MM
 *      MM YM.    , 8M   MM  Mb     dM MM   ,AP YA.   ,A9 YA.   ,A9 MM    MM
 *    .JMML.`Mbmmd' `Moo9^Yo.P"Ybmmd"  MMbmmd'   `Ybmd9'   `Ybmd9'.JMML  JMML.
 *                                     MM
 *                                   .JMML.
 * This file is part of TeaSpoon.
 *
 * TeaSpoon is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TeaSpoon is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with TeaSpoon.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Xenophilicy\TableSpoon
 * @link https://CortexPE.xyz
 *
 */

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\{network\mcpe\protocol\types\DimensionIds, Server, Server as PMServer};
use pocketmine\block\Lava as PMLava;
use pocketmine\entity\Entity;
use pocketmine\event\entity\{EntityCombustByBlockEvent, EntityDamageByBlockEvent, EntityDamageEvent};
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Lava
 * @package Xenophilicy\TableSpoon\block
 */
class Lava extends PMLava {
    
    /**
     * @param Entity $entity
     */
    public function onEntityCollide(Entity $entity): void{
        if((Server::getInstance()->getTick() % $this->tickRate()) == 0){
            $entity->fallDistance *= 0.5;
            $ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_LAVA, 4);
            $entity->attack($ev);
        }
        $ev = new EntityCombustByBlockEvent($this, $entity, 15);
        PMServer::getInstance()->getPluginManager()->callEvent($ev);
        if(!$ev->isCancelled()){
            $entity->setOnFire($ev->getDuration());
        }
        $entity->resetFallDistance();
    }
    
    public function getFlowDecayPerBlock(): int{
        return (Utils::getDimension($this->getLevel()) == DimensionIds::NETHER) ? 1 : 2;
    }
}