<?php

namespace Justfun\Repositories;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Entities\userEntity as Entity;

/**
 * usersRepository
 *
 * @author Pironato Francesco
 */
class usersRepository {

    // qui assumiamo per comodità di utilizzare mysql :D
    const MAIN_TABLE = 'users';

    protected $database, $tableName = 'users';

    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * 
     * @TODO: capire dove idratare gli oggetti ,come ( prototype?? ;) )
     */
    public function getAll() {
        return $this->database->getConnectionAdapter()->getAll(self::MAIN_TABLE, new Entity());
    }

    public function find($id) {
        return $this->database->getConnectionAdapter()->find($id, self::MAIN_TABLE, new Entity());
    }

    public function findByTitle($title) {
        $connection = $this->database->getConnectionAdapter()->getConnection();
        $sql = 'SELECT * FROM ' . self::MAIN_TABLE . ' WHERE title="' . $title . '"';
        $query = mysqli_query($connection, $sql);
        $result = mysqli_fetch_row($query);
        /** manipolare dati (hydrate)... forse conviene farlo
         *  in tutti i repo usando un traits da portare orizzontalmente!
         */
    }

    public function search() {
        // not yet implemented
    }

}
