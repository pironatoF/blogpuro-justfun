<?php

namespace Justfun\Repositories;

use Justfun\Core\Database as Database;
use Justfun\Core\Factory as CoreFactory;
/**
 * Description of Factory
 *
 * @author Pironato Francesco
 */
class Factory {
     
    public static function getPostsRepository(){
        return new postsRepository(CoreFactory::getDatabase());
    }
}
