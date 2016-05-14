<?php

namespace EntityWrangler\MagicGenerator;

use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityTable;
use EntityWrangler\SavePath;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\TypeGenerator;
use Zend\Code\Generator\ValueGenerator;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableColumns;
use EntityWrangler\Definition\TableColumn;
use EntityWrangler\Definition\TableInfo;

class MagicTableGenerator
{
    private $savePath;
    
    /** @var  ClassGenerator */
    private $classGenerator;

    public function __construct(SavePath $savePath)
    {
        $this->savePath = $savePath;
    }

    public function addColumn(TableColumn $column)
    {
        $columnName = $column->getName();
        $body = "return '$columnName';";
        $docBlockTags = [
            new ReturnTag('string')
        ];
        $docBlock = new DocBlockGenerator(
            $column->getDescription(),
            null, 
            $docBlockTags
        );
        $method = new MethodGenerator(
            "columnName".ucfirst($column->getName()),
            [],
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
            
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }

    public function setupClass(EntityDefinition $entityDefinition)
    {
        $name = $entityDefinition->getTableInfo()->tableName.'Table';
        $this->classGenerator = new ClassGenerator($name, 'EntityWranglerTest\Table');
        $this->classGenerator->setExtendedClass('EntityTable');
        $this->classGenerator->addUse('EntityWrangler\EntityTable');
        $this->classGenerator->addUse('EntityWrangler\Query\Query');
        $this->classGenerator->addUse('EntityWrangler\Query\QueriedEntity');
    }

    public function setupQueriedClass(EntityDefinition $entityDefinition)
    {
        $name = 'Queried'.$entityDefinition->getTableInfo()->tableName.'Table';
        $this->classGenerator = new ClassGenerator($name, 'EntityWranglerTest\Table');
        $this->classGenerator->setExtendedClass('QueriedEntity');
        $this->classGenerator->addUse('EntityWrangler\EntityTable');
        $this->classGenerator->addUse('EntityWrangler\Query\Query');
        $this->classGenerator->addUse('EntityWrangler\Query\QueriedEntity');
    }

    public function generate(EntityDefinition $entityDefinition)
    {
        $this->generateTable($entityDefinition);
        $this->generateQueriedTable($entityDefinition);
    }

    public function addWhereColumnEqualsMethod(TableColumn $column)
    {
        $columnName = $column->getName();
        $body = 'return $this->whereColumn("'.lcfirst($columnName).'", $string);';
        $docBlockTags = [
            //new ReturnTag('string')
        ];

        $docBlock = new DocBlockGenerator(
            "blah blah.",
            null, 
            $docBlockTags
        );

        $method = new MethodGenerator(
            'where'.ucfirst($column->getName()).'Equals',
            [new ParameterGenerator('string')],
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
            
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }
    
    function addQueriedColumn(TableColumn $tableColumn)
    {
        $this->addWhereColumnEqualsMethod($tableColumn);
    }
    
    
    private function generateQueriedTable(EntityDefinition $entityDefinition)
    {
        $this->setupQueriedClass($entityDefinition);
        $tableColumns = TableColumns::fromDefinition($entityDefinition);
        foreach ($tableColumns->getColumns() as $tableColumn) {
            $this->addQueriedColumn($tableColumn);
        }
        $this->saveQueried($entityDefinition->getTableInfo());
    }

    private function generateTable(EntityDefinition $entityDefinition)
    {
        $this->setupClass($entityDefinition);
        $tableColumns = TableColumns::fromDefinition($entityDefinition);
        foreach ($tableColumns->getColumns() as $tableColumn) {
            $this->addColumn($tableColumn);
        }
        $this->save($entityDefinition->getTableInfo());
    }
    
    
    private function save(TableInfo $tableInfo)
    {
        $code = $this->classGenerator->generate();
        $fullPath = $this->savePath->getPath().'/EntityWranglerTest/Table';
        
        @mkdir($fullPath, 0755, true);
        $code = "<?php \n\n".$code;
        file_put_contents($fullPath.'/'.$tableInfo->tableName.'Table.php', $code);
    }
    
    private function saveQueried(TableInfo $tableInfo)
    {
        $code = $this->classGenerator->generate();
        $fullPath = $this->savePath->getPath().'/EntityWranglerTest/Table';
        
        @mkdir($fullPath, 0755, true);
        $code = "<?php \n\n".$code;
        file_put_contents($fullPath.'/Queried'.$tableInfo->tableName.'Table.php', $code);
    }
}
