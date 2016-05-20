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
use EntityWrangler\CompositeEntity;

class MagicCompositeModelGenerator
{
    private $savePath;
    
    /** @var  ClassGenerator */
    private $classGenerator;
    
    /** @var \EntityWrangler\CompositeEntity[] */
    private $compositeEntities;

    public function __construct(
        SavePath $savePath,
        array $compositeEntities
    ) {
        $this->savePath = $savePath;
        $this->compositeEntities = $compositeEntities;
    }

    public function setupClass(CompositeEntity $compositeEntity)
    {
        $this->classGenerator = new ClassGenerator(
            $compositeEntity->getName(),
            'EntityWranglerTest\Model'
        );
        
        $classDocBlock = new DocBlockGenerator("Auto-generated.");
        
        $this->classGenerator->setDocBlock($classDocBlock);

        foreach($compositeEntity->getElements() as $element) {
            $property = new PropertyGenerator(lcfirst($element->getPropertyName()));
            $typeString = $element->getName();
            if ($element->getType() == CompositeEntity::TYPE_ARRAY) {
                $typeString .= '[]';
            }

            $property->setDocBlock("@var ".$typeString);
            
            $this->classGenerator->addPropertyFromGenerator($property);
        }
    }

    public function generate()
    {
        foreach ($this->compositeEntities as $compositeEntity) {
            $this->setupClass($compositeEntity);
            $this->addConstructorMethod($compositeEntity);
            $this->save($compositeEntity);
        }
    }

    private function addConstructorMethod(CompositeEntity $compositeEntity)
    {
        $body = '';
        $params = [];

        foreach ($compositeEntity->getElements() as $compositeElement) {
            $propertyName = lcfirst($compositeElement->getPropertyName());
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


    private function save(CompositeEntity $compositeEntity)
    {
        $code = $this->classGenerator->generate();
        $fullPath = $this->savePath->getPath().'/EntityWranglerTest/Model';
        
        @mkdir($fullPath, 0755, true);
        $code = "<?php \n\n".$code;
        file_put_contents($fullPath.'/'.$compositeEntity->getName().'.php', $code);
    }
}
