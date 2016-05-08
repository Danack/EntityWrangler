<?php

require_once __DIR__."/../vendor/autoload.php";


// // Create (connect to) SQLite database in file
// $file_db = new PDO('sqlite:testing.sqlite3');
// // Set errormode to exceptions
// $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//exit(0);



use Doctrine\DBAL\DriverManager;
 

$conn = DriverManager::getConnection(['pdo' => new PDO('sqlite:testing.sqlite')]);

$conn->connect();

$schemaManager = $conn->getSchemaManager();

echo "table exists is ".$schemaManager->tablesExist('users')."\n";


$table = $schemaManager->listTableDetails('users');

$columns = $table->getColumns();

foreach ($columns as $column) {
    echo "Name: ".$column->getName()."\n";
}


$tables = $schemaManager->listTables();

foreach ($tables as $table) {
    echo $table->getName() . " columns:\n\n";
    foreach ($table->getColumns() as $column) {
        echo ' - ' . $column->getName() . "\n";
    }
}

//$sequences = $schemaManager->listSequences();
//
//foreach ($sequences as $sequence) {
//    echo $sequence->getName() . "\n";
//}

//$conn->exec("CREATE TABLE users (
//            userid VARCHAR PRIMARY KEY  NOT NULL ,
//            password VARCHAR NOT NULL ,
//            name VARCHAR,
//            surname VARCHAR
//            );");
//$conn->exec("INSERT INTO users VALUES('user','pass','Name','Surname');");
//$conn->exec("INSERT INTO users VALUES('user2','pass2','Name2','Surname2');");
//
//$conn->close();
//
//exit(0);






//
//$config = new \Doctrine\DBAL\Configuration();
//
//$connectionParams = array(
//    'dbname' => 'mydb',
////    'user' => 'foo',
////    'password' => 'bar',
//    'path' => 'sqlite:///:memory:',
//    //'path' => 'sqlite:///testing.sqlite',
//    'driver' => 'pdo_sqlite',
//);
//$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);



$platform = $conn->getDatabasePlatform();

echo "Platform name is: ".$platform->getName()."\n";



//$schemaManager->createDatabase('foo.sqlite');


$schema = $schemaManager->createSchema();

$fromSchema = $schemaManager->createSchema();
$toSchema = clone $fromSchema;
//$toSchema->dropTable('users');

$usersTable = $toSchema->getTable('users');
$usersTable->addColumn('foo', 'string', array("length" => 1024));

$sql = $fromSchema->getMigrateToSql($toSchema, $conn->getDatabasePlatform());

echo "migration is:\n";

var_dump($sql);

//$myTable = $schema->createTable("my_table");
//$myTable->addColumn("id", "integer", array("unsigned" => true));
//$myTable->addColumn("username", "string", array("length" => 32));
//$myTable->setPrimaryKey(array("id"));
//$myTable->addUniqueIndex(array("username"));

//$schema->createSequence("my_table_seq");

$conn = DriverManager::getConnection(array(/*..*/));
$queryBuilder = $conn->createQueryBuilder();



//$myForeign = $schema->createTable("my_foreign");
//$myForeign->addColumn("id", "integer");
//$myForeign->addColumn("user_id", "integer");
//$myForeign->addForeignKeyConstraint($myTable, array("user_id"), array("id"), array("onUpdate" => "CASCADE"));
//




// $queries = $schema->toSql($platform); // get queries to create this schema.
//$dropSchema = $schema->toDropSql($platform); // get queries to safely delete this schema.