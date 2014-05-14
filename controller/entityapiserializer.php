<?php
/**
 * ownCloud - News
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Bernhard Posselt 2014
 */

namespace OCA\News\Controller;

use \OCP\AppFramework\Http\IResponseSerializer;
use \OCP\AppFramework\Http\Response;

use \OCA\News\Db\IAPI;


class EntityApiSerializer implements IResponseSerializer {


    public function __construct($level) {
        $this->level = $level;
    }


    /**
     * Call toAPI() method on all entities. Works on
     * @param mixed $data:
     * * Entity
     * * Entity[]
     * * array('level' => Entity[])
     * * Response
     */
    public function serialize($data) {

        if($data === null || $data instanceof Response) {
            return $data;
        }

        if($data instanceof IAPI) {
            return array(
                $this->level => array($data->toAPI())
            );
        }

        if(is_array($data) && array_key_exists($this->level, $data)) {
            $data[$this->level] = $this->convert($data[$this->level]);
        } elseif(is_array($data)) {
            $data = array(
                $this->level => $this->convert($data)
            );
        }

        return $data;
    }


    private function convert($entities) {
        $converted = array();

        foreach($entities as $entity) {
            if($entity instanceof IAPI) {
                $converted[] = $entity->toAPI();    
            } else {
                $converted[] = $entity;
            }
        }
        
        return $converted;
    }

}