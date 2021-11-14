<?php

namespace App\Repositories;

trait DbConnectionTrait
{
    protected \PDO $dbConnection;

    protected function setDbConnection(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}
