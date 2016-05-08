<?php


namespace EntityWranglerTest;

use Doctrine\DBAL\DriverManager;

class App
{
    public static function createDBALQueryBuilder()
    {
        $conn = DriverManager::getConnection(['pdo' => new \PDO('sqlite:testing.sqlite')]);
        $queryBuilder = $conn->createQueryBuilder();
        
        return $queryBuilder;
    }
}
