<?php

namespace EntityWrangler\MagicGenerator;

use EntityWrangler\Entity;
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

class MagicQueryGenerator
{
    private $savePath;
    
    /** @var  ClassGenerator */
    private $classGenerator;
    
    /** @var \EntityWrangler\Entity[] */
    private $entities = [];

    public function __construct(SavePath $savePath)
    {
        $this->savePath = $savePath;
    }
    
    public function addEntity(Entity $entity)
    {
        $this->entities[] = $entity;
    }

    public function addTableFunctions()
    {
        foreach ($this->entities as $entity) {
            $this->addTableFunction($entity);
        }
    }
    
    public function addTableFunction(Entity $entity)
    {
        $tableName = lcfirst($entity->getName()).'Table';
        
        $names = [
            '%QUERIED_TABLE_NAME%' => "Queried".$entity->getName()."Table",
            '%TABLE_NAME%' => $tableName,
        ];
        
        $body = str_replace(
            array_keys($names),
            $names,
            'return $this->table($this->%TABLE_NAME%, %QUERIED_TABLE_NAME%::class, $joinEntity);'
        );

        $docBlockTags = [
            new ParamTag('joinEntity', '\EntityWranglerTest\Table\QueriedUserTable'),
            new ReturnTag('\EntityWranglerTest\Table\QueriedUserTable')
        ];
        
        
        $docBlock = new DocBlockGenerator(
            "Join the $tableName table.",
            null, 
            $docBlockTags
        );
        
        
        
        $params = [
            new ParameterGenerator(
                'joinEntity', 
                'EntityWranglerTest\Table\QueriedUserTable',
                new ValueGenerator()
            )
        ];


        $method = new MethodGenerator(
            $tableName,
            $params,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
            
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }

    public function setupClass()
    {
        $this->classGenerator = new ClassGenerator('MagicQuery', 'EntityWranglerTest\Magic');     
        $this->classGenerator->setExtendedClass('Query');
        $this->classGenerator->addUse('EntityWrangler\Query\Query');
        $this->classGenerator->addUse('EntityWrangler\SafeAccess');
        $this->classGenerator->addUse('EntityWranglerTest\Table\UserTable');
        $this->classGenerator->addUse('Doctrine\DBAL\Query\QueryBuilder', 'DBALQueryBuilder');
        $this->classGenerator->addUse('EntityWranglerTest\Table\QueriedUserTable');
    }

    public function generate()
    {
        $this->setupClass();
        $this->addTableFunctions();
        $this->save();
    }

    private function save()
    {
        $code = $this->classGenerator->generate();
        @mkdir($this->savePath->getPath(), 0755, true);
        $code = "<?php \n\n".$code;
        file_put_contents($this->savePath->getPath().'/MagicQuery.php', $code);
    }
}
