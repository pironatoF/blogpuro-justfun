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
    
    public static function getPostCommentsRepository(){
        return new postCommentsRepository(CoreFactory::getDatabase());
    }
    
    public static function getUsersRepository(){
        return new usersRepository(CoreFactory::getDatabase());
    }
}
