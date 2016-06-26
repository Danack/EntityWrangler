<?php

namespace EntityWrangler\Query;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\EntityWranglerException;
use EntityWrangler\SafeAccess;
use EntityWrangler\QueryFragment;
use EntityWrangler\QueryFragment\WhereFragment;
use EntityWrangler\QueryFragment\WhereInFragment;
use EntityWrangler\QueryFragment\SelectColumnFragment;
use EntityWrangler\QueryFragment\TableFragment;
use EntityWrangler\QueryFragment\InsertFragment;
use EntityWrangler\QueryFragment\AncestorFragment;
use EntityWrangler\QueryFragment\LeftTableFragment;
use EntityWrangler\QueryFragment\GroupFragment;
use EntityWrangler\QueryFragment\OrderFragment;
use EntityWrangler\QueryFragment\NullFragment;
use EntityWrangler\QueryFragment\ValueFragment;
use EntityWrangler\QueryFragment\LimitFragment;
use EntityWrangler\QueryFragment\OffsetFragment;
use EntityWrangler\QueryFragment\RandOrderFragment;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class Query 
{
    /**
     * @var QueryFragment[]
     */
    private $queryFragments = array();

    const QUERY_TYPE_INSERT = 'insert';
    
    const QUERY_TYPE_SELECT = 'select';
    
    const QUERY_TYPE_UPDATE = 'update';
    
    /**
     * @var array List of the names of the table names or aliases already used, so that if a table
     * is used multiple times in a query, the subsequent uses will use different alias.
     */
    protected $entityNamesUsed = array();

    /** @var int Number of aliases used so we can throw an exception if we run out. */
    protected $aliasCount = 0;

    /**
     * @var array Stores the data returned from a query before being returned.
     */
    private  $data = array();

    //This binds the result
    private $columnsArray = array();

    /** @var \EntityWrangler\Query\QueriedTable  */
    private $previousTable = null;

    /** @var array */
    protected $outputClassnames = array();
    
    /** @var   */
    protected $dbalQueryBuilder;
    
    
    public function __construct(DBALQueryBuilder $dbalQueryBuilder)
    {
        $this->dbalQueryBuilder = $dbalQueryBuilder;
    }

    /**
     * @return DBALQueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->dbalQueryBuilder;

    }

    /**
     * @param EntityTableDefinition $entity
     * @param $entityClassName
     * @param QueriedTable $joinEntity
     * @return QueriedTable
     */
    function table(EntityTableDefinition $entity, $entityClassName, QueriedTable $joinEntity = null)
    {
        if ($joinEntity == null) {
            $joinEntity = $this->previousTable;
        }

        $queriedTable = $this->aliasEntity($entity, $entityClassName);
        $newFragment = new TableFragment($queriedTable, $joinEntity);
        $this->queryFragments[] = $newFragment;
        $this->previousTable = $queriedTable;

        return $newFragment->queriedEntity;
    }

    function leftTable(EntityTableDefinition $entity,  $entityClassName, QueriedTable $joinEntity = null)
    {
        if ($joinEntity == null) {
            if ($this->previousTable == null) {
                throw new EntityWranglerException("Cannot join, no previous table.");
            }
            $joinEntity = $this->previousTable;
        }
        
        $queriedTable = $this->aliasEntity($entity, $entityClassName);
        $newFragment = new LeftTableFragment($queriedTable, $joinEntity);
        $this->queryFragments[] = $newFragment;
        $this->previousTable = $queriedTable;

        return $newFragment->queriedEntity;
    }

//    /**
//     * @param QueriedTable $joinTableMap
//     * @param $ancestorID
//     */
//    function ancestor(QueriedTable $joinTableMap, $ancestorID, $isDescendant = false) {
//        $relation = $joinTableMap->getTableMap()->getSelfClosureRelation();
//        $closureTableName = $relation->getTableName();
//        $closureTable = new $closureTableName();
//        $queriedClosureTable = $this->aliasTableMap($closureTable);
//        $newFragment = new AncestorFragment($joinTableMap, $queriedClosureTable, $ancestorID, $isDescendant);
//        $this->sqlFragments[] = $newFragment;
//
//        return null;
//    }
//
//    function descendant(QueriedTable $joinTableMap, $ancestorID) {
//        return $this->ancestor($joinTableMap, $ancestorID, true);
//    }




    /**
     * @param QueriedTable $queriedEntity
     * @param $column
     */
    public function select(QueriedTable $queriedEntity, $column)
    {
        $newFragment = new SelectColumnFragment($queriedEntity, $column);
        $this->queryFragments[] = $newFragment;
    }

    /**
     * @param EntityTableDefinition $entity
     * @return mixed
     */
    function getAliasForTable(EntityTableDefinition $entity)
    {
        $tableName = $entity->getName();
        if(in_array($tableName, $this->entityNamesUsed) == FALSE){
            $this->entityNamesUsed[] = $tableName;

            return $tableName;
        }

        $tableAliases = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',  );

        if($this->aliasCount >= 0 && $this->aliasCount < count($tableAliases)){
            $this->aliasCount++;
        }
        
        $alias = $tableAliases[$this->aliasCount];
        $this->entityNamesUsed[] = $alias;

        return $alias;
    }

    /**
     * Adds a WHERE fragment to a query.
     *
     * @param $condition
     * @param null $value
     * @param null $type
     * @throws \Exception
     */
    function where($condition, $value, $type)
    {
        $this->queryFragments[] = new WhereFragment($condition, $value, $type);
    }

    function whereIn($condition, $values, $type)
    {
        $this->queryFragments[] = new WhereInFragment($condition, $values, $type);
    }


    /**
     * Adds a GROUP BY fragment to a query.
     *
     * @param QueriedTable $table
     * @param $column
     * @return string
     */
    function group(QueriedTable $table, $column) {
        $this->queryFragments[] = new SQLGroupFragment($table, $column);

        
        
        return $table->getAlias()."_".$column."_count";
    }

    /**
     * @param $tableMap
     * @param $column
     * @param string $orderValue
     */
    function order($tableMap, $column, $orderValue = 'ASC')
    {
        $this->queryFragments[] = new OrderFragment($column, $tableMap, $orderValue);
    }

    /**
     * @param QueriedTable $table
     * @param QueriedTable $table2
     */
    function rand(QueriedTable $table, QueriedTable $table2)
    {
        $this->queryFragments[] = new RandOrderFragment($table, $table2);
    }


    /**
     * Adds a limit fragment to a query.
     * @param $limit
     */
    public function limit($limit) {
        $this->queryFragments[] = new LimitFragment($limit);
    }

    /**
     * Adds an offset fragment to a query.
     * @param $offset
     * @throws \RuntimeException
     */
    public function offset($offset)
    {
        if (false) {
            $limitFragmentFound = false;
            foreach ($this->queryFragments as $sqlFragment) {
                if ($sqlFragment instanceof LimitFragment) {
                    $limitFragmentFound = true;
                }
            }
            if ($limitFragmentFound == false) {
                throw new EntityWranglerException("Cannot add offset without a limit, due to mysql limitation");
            }
        }
        
        $this->queryFragments[] = new OffsetFragment($offset);
    }

//    /**
//     * Adds a left outer join fragment to a query.
//     *
//     * TODO - rename this to leftOuter or similar.
//     *
//     * @param \EntityWrangler\QueriedTable|\EntityWrangler\TableMap $joinTableMap
//     * @param TableMap $nullTableMap
//     * @param array $columnValues
//     * @return \EntityWrangler\QueriedSQLTable
//     * @internal param $nullTable
//     */
//    //TODO this should be $queriedTable $queriedTable
//    function nullTable(QueriedTable $joinTableMap, TableMap $nullTableMap, $columnValues = array()) {
//
//        $queriedTable = $this->aliasTableMap($nullTableMap);
//
//        $newFragment = new SQLNullFragment(
//            $joinTableMap,
//            $queriedTable,
//            $queriedTable->alias,
//            $columnValues
//        );
//
//        $this->sqlFragments[] = $newFragment;
//
//        return $queriedTable;
//    }

    /**
     * @param $name
     * @param $value
     */
    function setValue($name, $value){
        $newFragment = new ValueFragment($name, $value);
        $this->queryFragments[] = $newFragment;
    }


    /**
     * @param EntityTableDefinition $tableMap
     * @param $entityClassName
     * @return QueriedTable
     */
    function aliasEntity(EntityTableDefinition $tableMap, $entityClassName)
    {
        $tableAlias = $this->getAliasForTable($tableMap);

        return new $entityClassName($tableMap, $tableAlias, $this);
    }

//    /**
//     * @param $tableMap QueriedTable
//     */
//    private function addColumns(QueriedTable $tableMap) {
//        $columnDefinitions = $tableMap->getColumns();
//        foreach($columnDefinitions as $columnDefinition){
//            $this->addColumn($tableMap, $columnDefinition[0]);
//        }
//    }
//
//    /**
//     * @param QueriedTable $tableMap
//     * @param $column
//     */
//    private function addColumn(QueriedTable $tableMap, $column) {
//        $this->addColumnFromTableAlias($tableMap->getAlias(), $column);
//    }

//    /**
//     * @param $tableAlias
//     * @param $column
//     */
//    private function addColumnFromTableAlias($tableAlias, $column) {
//        $this->queryString .= $this->commaString;
//        $this->queryString .= " ".$tableAlias.".".$column;
//        $this->commaString = ', ';
//        $resultName = $tableAlias.'.'.$column;
//        $this->columnsArray[] = &$this->data[$resultName];
//    }


    /**
     * 
     */
    function delete() {
        $this->fetch(false, true);
    }


//    /**
//     * Find the join column between two tables, where the second table
//     * has a foreign key to the first table
//     * @TODO replace with the relation stuff.
//     * 
//     * @param QueriedTable $tableMap
//     * @param QueriedTable $joinTableMap
//     * @return bool|null
//     */
//    function getJoinColumn(QueriedTable $tableMap, QueriedTable $joinTableMap) {
//
//        //Try and join on the primary column of the previous table
//        $joinColumn = $joinTableMap->getPrimaryColumn();
//        foreach ($tableMap->getColumns() as $column) {
//            if ($column[0] == $joinColumn) {
//                return $joinColumn;
//            }
//        }
//
//        //Try and join on the primary column of the this table
//        $joinColumn = $tableMap->getPrimaryColumn();
//        foreach ($joinTableMap->getColumns() as $column) {
//            if ($column[0] == $joinColumn) {
//                return $joinColumn;
//            }
//        }
//
//        return null;
//    }

//    /**
//     * @return mixed
//     */
//    function count() {
//        return $this->fetch(true);
//    }

//    /**
//     * Inserts SQLTableFragment's to allow tables to be joined. The SQLTableFragment are
//     * created either from the tables defined relations or an examination of their
//     * columns.
//     * This kind of gets repeated later when the join is actually done.
//     * @throws \Exception
//     */
    function addJoiningRelationTables() {
//
//        $modifiedSQLFragments = [];
//
//        $previousTableMap = null;
//        $first = true;
//
//        foreach ($this->sqlFragments as $sqlFragment) {
//
//            if($sqlFragment instanceof SQLTableFragment){
//
//                if ($first == true) {
//                    goto endSQLFragment; //yolo
//                }
//
//                $joinTableMap = $sqlFragment->queriedJoinTableMap;
//
//                if ($joinTableMap == null) {            //If we were not told explicitly which table t join to
//                    $joinTableMap = $previousTableMap;  //try to join to the previous one.
//                }
//
//                //Try and find a column to join on automatically.
//                $autoJoinColumn = $this->getJoinColumn($sqlFragment->queriedTableMap, $joinTableMap);
//
//                if ($autoJoinColumn == null) {
//                    //We failed to join automatically - lets try the proper relation stuff
//                    $relatedTable = $this->findRelationTable($sqlFragment->queriedTableMap, $joinTableMap);
//
//                    if ($relatedTable) {
//                        $joinFragment = $this->makeTableFragment($relatedTable);
//                        $joinFragment->setFetchColumns(false);
//                        $modifiedSQLFragments[] = $joinFragment;
//                    }
//                }
//
//endSQLFragment:
//                $previousTableMap = $sqlFragment->queriedTableMap;
//            }
//
//            $modifiedSQLFragments[] = $sqlFragment;
//            $first = false;
//        }
//
//        $this->sqlFragments = $modifiedSQLFragments;
    }

//    /**
//     * @param QueriedTable $queriedTableMap
//     * @param QueriedTable $joinTableMap
//     * @throws \Exception
//     * @return null
//     */
//    function findRelationTable(QueriedTable $queriedTableMap, QueriedTable $joinTableMap) {
//        $joinTable = null;
//
//        $relations = $queriedTableMap->getTableMap()->getRelations();
//        $relations = array_merge($relations, $joinTableMap->getTableMap()->getRelations());
//
//        /** @var $relations Relation[] */
//        foreach($relations as $relation) {
//            $owningType = $relation->getOwning();
//            $inverseType = $relation->getInverse();
//            
//            if ($queriedTableMap->getTableMap() instanceof $owningType && 
//                $joinTableMap->getTableMap() instanceof $inverseType) {
//                $tableName = $relation->getTableName();
//                return new $tableName();
//            }
//            if ($queriedTableMap->getTableMap() instanceof $inverseType &&
//                $joinTableMap->getTableMap() instanceof $owningType) {
//                $tableName = $relation->getTableName();
//                return new $tableName();
//            }
//        }
//
//        throw new \Exception("Could not find relation in ".var_export($relations, true));
//    }

//    /**
//     * @param $className
//     * @return array|null
//     * @throws \Exception
//     */
//    function fetchSingle($className) {
//        $results = $this->fetch();
//        
//        if (count($results) == 0) {
//            return null;
//        }
//        if (count($results) == 1) {
//            return castToObject($className, $results[0]);    
//        }
//
//        throw new \Exception("multiple rows found, when only one expected.");
//    }





    
    
    protected function buildQuery($type)
    {
        //Automatically add all columns from tables i.e. no specific columns
        //were queried.
        $autoAddColumns = TRUE; 

        $this->addJoiningRelationTables();
        
        $fns = [];
        $fields = [];

        foreach($this->queryFragments as $fragment) {
            $newFields = $fragment->selectBit($this);
            if ($newFields != null) {
                $fields = array_merge($fields, $newFields);
            }
        }

        foreach($this->queryFragments as $fragment) {
            $fns[] = $fragment->tableBit($this);
        }

        foreach($this->queryFragments as $fragment) {
            $fns[] = $fragment->insertBit($this);
        }

        foreach($this->queryFragments as $fragment) {
            $fns[] = $fragment->whereBit($this);
        }

        foreach($this->queryFragments as $fragment) {
            $fns[] = $fragment->offsetBit($this);
        }
        
        foreach($this->queryFragments as $fragment) {
            $fns[] = $fragment->orderBit($this);
        }
        
        foreach($this->queryFragments as $fragment) {
            $fns[] = $fragment->limitBit($this);
        }
        
        if ($type == Query::QUERY_TYPE_INSERT) {

        }
        else {
            if (count($fields) !== 0) {
                call_user_func_array([$this->dbalQueryBuilder, 'select'], $fields);
            }
        }

        foreach ($fns as $fn) {
            if ($fn === null) {
                continue;
            }
            if (!is_callable($fn)) {
                throw new EntityWranglerException('Callback is not callable.');
            }

            $this->dbalQueryBuilder = $fn($this->dbalQueryBuilder);
        }

        return $this->dbalQueryBuilder;
    }


    function fetch()
    {
        $this->buildQuery(self::QUERY_TYPE_SELECT);
        $statement = $this->dbalQueryBuilder->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
        

        

//        if ($doADelete == true) {
//            $this->queryString = "";
//            $schema = null;
//
//            foreach($this->queryFragments as $sqlFragment) {
//                if ($sqlFragment instanceof SQLTableFragment) {
//                    if ($schema == null) {
//                        $schema = $sqlFragment->queriedTableMap->getSchema();
//                    }
//                }
//            }
//
//            if ($schema == null) {
//                throw new \Exception("Trying to do a delete, but table has no schema.");
//            }
//
//            $this->dbConnection->selectSchema($schema);
//
//            $this->queryString .= "delete ";
//
//            $whereCount = 0;
//            $separator = '';
//            foreach($this->queryFragments as $sqlFragment){
//                if ($sqlFragment instanceof SQLTableFragment) {
//                    /** @var  $sqlFragment SQLTableFragment */
//                    $tableMap = $sqlFragment->queriedTableMap;
//                    $this->addSQL($separator.$tableMap->getAlias());
//                    $separator = ", ";
//                }
//
//                if ($sqlFragment instanceof SQLWhereFragment) {
//                    $whereCount += 1;
//                }
//            }
//            if ($whereCount == 0) {
//                throw new \Exception("Trying to do a delete with no where fragments, which is too dangerous.");
//            }
//        }
//        else if ($doACount == true) {
//            $this->addSQL("COUNT(*)");
//        }
//        else{
//            foreach($this->queryFragments as $sqlFragment) {
//                if($sqlFragment instanceof SQLSelectColumnFragment){
//                    /** @var $sqlFragment SQLSelectColumnFragment */
//                    $this->addColumn($sqlFragment->tableMap, $sqlFragment->column);
//                    $autoAddColumns = FALSE;
//                }
//            }
//
//            foreach($this->queryFragments as $sqlFragment) {

//
//                    if($sqlFragment instanceof AncestorFragment) {
//                        /** @var $sqlFragment AncestorFragment */
//                        $this->addColumns($sqlFragment->queriedClosureTable);
//                    }
//                }
//                if($sqlFragment instanceof SQLGroupFragment){
//                    /** @var $sqlFragment SQLGroupFragment */
//                    $this->addSQL(", count(1) as ".$sqlFragment->tableMap->getAlias()."_".$sqlFragment->column."_count ");
//                    $resultName = $sqlFragment->tableMap->getAlias().'.count';
//                    $this->columnsArray[] = &$this->data[$resultName];
//                }
//            }
//        //}
//
//        //$this->addSQL(" from ");
//        
//        
////FROM bit
//
//        $previousTableMap = NULL;
//        $tableMap = null;

//        foreach($this->queryFragments as $sqlFragment) {
//            if($sqlFragment instanceof TableFragment){
//                /** @var  $sqlFragment SQLTableFragment */
//                $tableMap = $sqlFragment->queriedTableMap;
//
//                $joinTableMap = $sqlFragment->queriedJoinTableMap;
//
//                if ($joinTableMap == null){
//                    $joinTableMap = $previousTableMap;
//                }
//
//                if($joinTableMap != NULL){
//                    $this->addSQL(" inner join ");
//                    $this->addSQL($tableMap->getSchema().".".$tableMap->getTableName().' as '.$tableMap->getAlias());
//                    $joinColumn = $this->getJoinColumn($tableMap, $joinTableMap);
//
//                    if ($joinColumn == null) {
//                        throw new \Exception("Could not figure out the join columns between ".$tableMap->getTableName()." and ".$joinTableMap->getTableName());
//                    }
//
//                    $this->addSQL(' on ('.$joinTableMap->getAlias().".".$joinColumn.' = '.$tableMap->getAlias().'.'.$joinColumn.") ");
//                }
//                else{
//                    $this->addSQL($tableMap->getSchema().".".$tableMap->getTableName().' as '.$tableMap->getAlias());
//                }
//            }
//            else if($sqlFragment instanceof SQLNullFragment) {
//                /** @var  $sqlFragment SQLNullFragment */
//                $tableMap = $sqlFragment->tableMap;
//                $nullTableMap = $sqlFragment->nullTableMap;
//                $this->addSQL(" left outer join ");
//                $this->addSQL($nullTableMap->getSchema().".".$nullTableMap->getTableName().' as '.$nullTableMap->getAlias());
//
//                $aliasedJoinColumn = $tableMap->getAliasedPrimaryColumn();
//                $joinColumnName = $tableMap->getPrimaryColumn();
//                $this->addSQL(' on ('.$aliasedJoinColumn.' = '.$nullTableMap->getAlias().'.'.$joinColumnName);
//                $columnValues = $sqlFragment->columnValues;
//                foreach($columnValues as $column => $value){
//                    $this->addSQL(" && ".$nullTableMap->getAlias().'.'."$column = '$value'");
//                }
//
//                $this->addSQL(" ) ");
//            }
//            else if($sqlFragment instanceof AncestorFragment) {
//                $sqlFragment->joinBit($this);
//                $sqlFragment->onBit($this);
//            }
//
//            $sqlFragment->randBit($this, $tableMap);
//            
////            if ($sqlFragment instanceof SQLRandOrderFragment) {
////                //http://jan.kneschke.de/projects/mysql/order-by-rand/
////                /** @var  $sqlFragment SQLRandOrderFragment */
////                $tableMap = $sqlFragment->tableMap;
////                $tableMap2 = $sqlFragment->tableMap2;
////
////                $this->addSQL(" inner join  (SELECT (RAND() *
////                             (SELECT MAX(".$tableMap->getPrimaryColumn().")
////                        FROM ".$tableMap2->getSchema().".".$tableMap2->getTableName().")) as ".$tableMap->getPrimaryColumn()." )
////                    AS ".$tableMap2->getAlias()."_rand");
////
////                $this->addSQL( " where ".$tableMap->getAliasedPrimaryColumn()."  >= ".$tableMap2->getAlias()."_rand.".$tableMap2->getPrimaryColumn() );
////
////            }
//
//            $previousTableMap = $tableMap;
//        }


//        $andString = '';
//
//        foreach($this->queryFragments as $sqlFragment){
//
//            if($sqlFragment instanceof SQLNullFragment){
//                /** @var $nullTableMap QueriedTable  */
//                $nullTableMap = $sqlFragment->nullTableMap;
//
//                //Add the ID column
//                $this->addSQL($whereString);
//                $this->addSQL($andString.' '.$nullTableMap->getAliasedPrimaryColumn()." is null " );
//                $andString = ' and';
//                $whereString = '';
//
//                //Add the actual columns with values
//                foreach($sqlFragment->columnValues as $column => $value){
//                    $this->addSQL($whereString);
//                    //TODO - alias should be $nullTableMapAlias?
//                    $this->addSQL($andString.' '.$nullTableMap->getAlias().'.'."$column is null " );
//                    $andString = ' and';
//                    $whereString = '';
//                }
//            }

//            if($sqlFragment instanceof SQLWhereFragment){
//                /** @var $sqlFragment SQLWhereFragment */
//                $this->addSQL( $whereString.$andString);
//                $this->addSQL(' '.$sqlFragment->whereCondition);
//                $whereString = '';
//                $andString = ' and';
//                $this->bindParams($sqlFragment);
//            }
            
            //$sqlFragment->whereBit($this);

//            if ($sqlFragment instanceof AncestorFragment) {
//                $this->addSQL( $whereString.$andString);
//                /** @var $sqlFragment AncestorFragment */
//                $whereString = '';
//                $andString = ' and';
//                $this->bindParams($sqlFragment);
//                $sqlFragment->whereBit($this);
//            }
        }

//        $groupByString = " group by ";
//        foreach($this->sqlFragments as $sqlFragment){
//            if($sqlFragment instanceof SQLGroupFragment){
//                /** @var $sqlFragment SQLGroupFragment */
//                $sqlGroup = $groupByString.$sqlFragment->tableMap->getAlias().".".$sqlFragment->column;
//                $this->addSQL($sqlGroup);
//                $groupByString = "";
//            }
//        }

//        $commaString = "";
//        $orderByString = " order  by ";
//
//        foreach($this->sqlFragments as $sqlFragment){
//            if($sqlFragment instanceof SQLOrderFragment){
//                /** @var $sqlFragment SQLOrderFragment */
//                $this->addSQL($commaString);
//                $this->addSQL($orderByString);
//
//                if ($sqlFragment->tableMap == null){
//                    // The 'column' may actually be a group by result, and so isn't part of a table
//                    // or tableAlias
//                    $this->addSQL($sqlFragment->column);
//                }
//                else{
//                    $this->addSQL($sqlFragment->tableMap->getAlias().".".$sqlFragment->column);
//                }
//
//                $this->addSQL(" ".$sqlFragment->orderValue);
//
//                $commaString = ", ";
//                $orderByString = "";
//            }
//        }

//        foreach($this->sqlFragments as $sqlFragment){
//            if($sqlFragment instanceof SQLLimitFragment){
//                /** @var $sqlFragment SQLLimitFragment */
//                $this->addSQL(" limit ".$sqlFragment->limit);
//            }
//
//            if($sqlFragment instanceof SQLOffsetFragment){
//                /** @var $sqlFragment SQLOffsetFragment */
//                $this->addSQL(" offset ".$sqlFragment->offset);
//            }
//        }

//        $this->queryString .= ';';
//
//        if($this->showSQL == TRUE){
//            echo "Query is [";
//            //echo str_replace("\n", "<br/>\n", $this->queryString);
//            echo $this->queryString;
//            echo "]\r\n";
//        }

//        if($this->showSQLAndExit == true){
//            echo "Query is [";
//            //echo str_replace("\n", "<br/>\n", $this->queryString);
//            echo $this->queryString;
//            echo "]\r\n";
//
//            var_dump($this->paramsTypes);
//            var_dump($this->params);
//            exit(0);
//        }
        
        // prepare and execute
        

//        $statementWrapper = $this->dbConnection->prepareStatement($this->queryString);
//        
//        if(count($this->params) > 0) {
//            $bindParams = array();
//            $bindParams[] = $this->paramsTypes;
//            $bindParams = array_merge($bindParams, $this->params);
//            call_user_func_array(array($statementWrapper->statement, 'bind_param'), $bindParams);
//        }
//
//        $result = $statementWrapper->execute();
//
//        if (!$result) {
//            throw new DBException("Error executing query :".$this->dbConnection->getLastError());
//        }
//
//        if ($doADelete == true) {
//            return null;
//        }
//        else if ($doACount == true) {
//            $count = 0;
//            $statementWrapper->statement->bind_result($count);
//
//            if ($statementWrapper->statement->fetch()) {
//                $statementWrapper->close();
//                return $count;
//            }
//            throw new \Exception("Failed to get count");
//        }
//        else{
//            call_user_func_array(array($statementWrapper->statement, 'bind_result'), $this->data);
//
//            $linksArray = array();
//
//            $i = 0;
//
//            while($statementWrapper->statement->fetch()){
//                foreach($this->data as $key => $value){
//                    $linksArray[$i][$key] = $value;
//                }
//                $i++;
//            }
//
//            $statementWrapper->close();
//            return $linksArray;
//        }
//    }


    //This stays table map
    function insertIntoMappedTable(EntityTableDefinition $tableMap, $entityClassName, $data, $foreignKeys = array())
    {
        $queriedTable = $this->aliasEntity($tableMap, $entityClassName);
        $this->queryFragments[] = new InsertFragment($queriedTable, $data);
        $this->buildQuery(self::QUERY_TYPE_INSERT);
        $result = $this->dbalQueryBuilder->execute();

        return $result; 

//        if ($closureRelation = $tableMap->getSelfClosureRelation()) {
//            $closureTableMapName = $closureRelation->getTableName();
//            $closureTableMap = new $closureTableMapName();
//            $this->insertIntoTreePaths($closureTableMap, $insertID, $data['parent']);
//        }
//
//        $this->insertIntoRelationTables($foreignKeys, $tableMap);
    }

//    function insertIntoRelationTables($foreignKeys, TableMap $tableMap) {
//
//        $relations = $tableMap->getRelations();
//
//        foreach ($relations as $relation) {
//            
//            if ($relation->getType() == Relation::SELF_CLOSURE) {
//                //already handled outside of here, which is bad, but hey.
//            }
//            else{
//                $tableToInsert = $relation->getOwningJoinTable($tableMap);
//                if ($tableToInsert) {
//                    $this->insertIntoMappedTable($tableToInsert, $foreignKeys);
//                }
//            }
//        }
//    }
//    
//    /**
//     * @param Connection $dbConnection
//     * @throws \Intahwebz\Exception\UnsupportedOperationException
//     */
//    function deleteFromMappedTableCount(Connection $dbConnection) {
//        unused($dbConnection);
//        throw new UnsupportedOperationException("deleteFromMappedTableCount is not yet implemented.");
//    }

//    /**
//     * @param TableMap $tableMap
//     * @param $params
//     * @throws \Exception
//     */
//    function updateMappedTable(TableMap $tableMap, $params) {
//
      // probably need this.
//    }

//    /**
//     * @param TableMap $closureTableMap
//     * @param $insertID
//     * @param $parentID
//     */
//    function insertIntoTreePaths(TableMap $closureTableMap, $insertID, $parentID) {
//
//        $treePathTablename = $closureTableMap->schema.'.'.$closureTableMap->tableName;
//
//        $queryString = ' insert into '.$treePathTablename.' (ancestor, descendant, depth)
//                values (?, ?, 0)';
//
//        $connection = $this->dbConnection;
//        $statementWrapper = $connection->prepareStatement($queryString);
//
//        $statementWrapper->bindParam('ii', $insertID, $insertID);
//
//        $statementWrapper->execute();
//        $statementWrapper->close();
//
//        $queryString = 'Insert into '.$treePathTablename.' (ancestor, descendant, depth)
//            select ancestor, ?, (depth + 1) from '.$treePathTablename.'
//            where descendant = ? and 
//            ancestor != ?;';
//        
//        $statementWrapper = $connection->prepareStatement($queryString);
//        $statementWrapper->bindParam('iii', $insertID, $parentID, $insertID);
//
//        $statementWrapper->execute();
//        $statementWrapper->close();
//    }

//    /**
//     * Deletes a node.
//     * @param TableMap $tableMap
//     * @param $nodeID
//     */
//    function deleteNode(TableMap $tableMap, $nodeID) {
//        $this->reset();
//        $this->queryString = "";
//
//        $tableName = $tableMap->schema.".".$tableMap->tableName;
//        $this->addSQL("delete from ".$tableName."_TreePaths where descendant = ?");
//        //TODO - shouldn't this also have
//        //delete FROM `mocks`.`mockComment_TreePaths` where ancestor = 4;
//        //And also update depths?
//
//        $statementWrapper = $this->dbConnection->prepareStatement($this->queryString);
//        $statementWrapper->bindParam('i', $nodeID);
//
//        $statementWrapper->execute();
//        $statementWrapper->close();
//    }

//    /**
//     * Deletes the descendants of a node.
//     * @param TableMap $tableMap
//     * @param $nodeID
//     */
//    function deleteDescendants(TableMap $tableMap, $nodeID) {
//
//        $this->reset();
//        $this->queryString = "";
//
//        $tableName = $tableMap->schema.".".$tableMap->tableName;
//        $this->addSQL("delete ".$tableName."_TreePaths from ".$tableName."_TreePaths
//    join ".$tableName."_TreePaths a using (descendant)
//    where a.ancestor = ?;");
//                
//        $statementWrapper = $this->dbConnection->prepareStatement($this->queryString);
//        $statementWrapper->bindParam('i', $nodeID);
//        $statementWrapper->execute();
//        $statementWrapper->close();
//    }




}


