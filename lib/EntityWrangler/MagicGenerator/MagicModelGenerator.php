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
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\TypeGenerator;
use Zend\Code\Generator\ValueGenerator;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableColumns;
use EntityWrangler\Definition\TableColumn;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityField;

class MagicModelGenerator
{
    private $savePath;
    
    /** @var  ClassGenerator */
    private $classGenerator;
    
    /** @var \EntityWrangler\EntityDefinition */
    private $entityDefinition;

    public function __construct(
        SavePath $savePath,
        EntityDefinition $entityDefinition
    ) {
        $this->savePath = $savePath;
        $this->entityDefinition = $entityDefinition;
    }

    public function setupClass()
    {
        $name = $this->entityDefinition->getTableInfo()->tableName;
        $this->classGenerator = new ClassGenerator($name, 'EntityWranglerTest\Model');
        $this->classGenerator->addUse('Ramsey\Uuid\Uuid');
        $entityFields = getAllEntityFields($this->entityDefinition, true);

        foreach($entityFields as $entityField) {
            $const = new PropertyGenerator(
                strtoupper('COLUMN_'.$entityField->getDbName()),
                $entityField->getDbName(),
                PropertyGenerator::FLAG_CONSTANT
            );
            $this->classGenerator->addPropertyFromGenerator($const);
        }

        foreach($entityFields as $entityField) {
            $property = new PropertyGenerator($entityField->getPropertyName());
            $this->classGenerator->addPropertyFromGenerator($property);
        }
    }

    public function generate()
    {
        $this->setupClass();

        // ---------------
        $this->generateConstructor();
        $this->addCreateMethod();
        $this->addToDataMethod();
        $this->addFromDataMethod();
        

        $entityFields = getAllEntityFields($this->entityDefinition, true);
        foreach($entityFields as $entityField) {
            $this->generateFieldMethod($entityField);
        }
        
        $this->save();
    }

    private function generateConstructor()
    {
        $body = '';
        $params = [];
        $entityFields = getAllEntityFields($this->entityDefinition, true);

        foreach ($entityFields as $entityField) {
            $propertyName = $entityField->getPropertyName();
            $params[] = new ParameterGenerator($propertyName);
            $body .= sprintf(
                "\$this->%s = \$%s;\n",
                $propertyName,
                $propertyName
            );
        }

        $method = new MethodGenerator(
            '__construct',
            $params,
            MethodGenerator::FLAG_PUBLIC,
            $body
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }
    
    
    public function generateFieldMethod(EntityField $entityField)
    {
        $methodName = 'get'.ucfirst($entityField->getPropertyName());
        $body = 'return $this->'.$entityField->getPropertyName().';';

        $method = new MethodGenerator(
            $methodName,
            [],
            MethodGenerator::FLAG_PUBLIC,
            $body
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }

    
    private function addToDataMethod()
    {
        $body = <<< 'END'
$data = [];
%s

return $data;
END;

        $entityFields = getAllEntityFields($this->entityDefinition, true);
        $lines = [];

        foreach ($entityFields as $entityField) {
            $line = sprintf(
                '$data[\'%s\'] = $this->%s;',
                $entityField->getDbName(),
                $entityField->getPropertyName()
            );
            $lines[] = $line;
        }

        $method = new MethodGenerator(
            'toData',
            [],
            MethodGenerator::FLAG_PUBLIC,
            sprintf($body, implode("\n", $lines))
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }
    
    
    
    
    private function addFromDataMethod()
    {
        $body = <<< 'END'

$instance = new self(
    %s
);

return $instance;
END;

        $params = [
            new ParameterGenerator('data')
        ];
        $names = [];
        $entityFields = getAllEntityFields($this->entityDefinition, true);
        foreach ($entityFields as $entityField) {
            $names[] = '$data[\''.$entityField->getDbName().'\']';
        }

        $method = new MethodGenerator(
            'fromData',
            $params,
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            sprintf($body, implode(",\n    ", $names))
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }



    private function addCreateMethod()
    {
        $body = <<< 'END'
$uuid4 = UUID::uuid4();
$instance = new self(
    $uuid4->toString(),
    %s
);

return $instance;
END;

        $params = [];
        $names = [];
        
        $entityFields = getAllEntityFields($this->entityDefinition, false);

        foreach ($entityFields as $entityField) {
            $params[] = new ParameterGenerator($entityField->getPropertyName());
            $names[] = '$'.$entityField->getPropertyName();
        }

        $method = new MethodGenerator(
            'create',
            $params,
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            sprintf($body, implode(",\n    ", $names))
        );

        $this->classGenerator->addMethodFromGenerator($method);
    }

    private function save()
    {
        $tableInfo = $this->entityDefinition->getTableInfo();
        
        $code = $this->classGenerator->generate();
        $fullPath = $this->savePath->getPath().'/EntityWranglerTest/Model';
        
        @mkdir($fullPath, 0755, true);
        $code = "<?php \n\n".$code;
        file_put_contents($fullPath.'/'.$tableInfo->tableName.'.php', $code);
    }
}
