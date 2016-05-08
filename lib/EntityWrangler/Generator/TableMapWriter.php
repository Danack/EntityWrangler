<?php


namespace EntityMap;
use EntityWrangler\Entity;

/**
 * Class TableMapWriter
 * @TODO This doesn't need to be a class but PHP sucks at function loading.
 * 
 * @package EntityMap
 */
class EntityClassWriter
{
    private $fileWriter;
    
    public function __construct(FileWriter $fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    function generateObjectFile(Entity $entity, $namespace) {

        $output = "<?php\n\n";
        $output .= "namespace $namespace;\n\n";
        $output .= $this->getClassString($entity);
        $output .= "\n";

        $this->fileWriter->write($entity->getDTOClassName().'.php', $output);
    }


    /**
     * @param Entity $entity
     * @return string
     */
    function getClassString(Entity $entity) {

        $st = "    ";

        $output = "class ".$entity->getDTOClassname()." {\n";
        foreach($entity->columns as $column){
            $output .= $st."public \$".$column[0].";\n";
        }

        $output .= "\n";
        $output .= $st."public function __construct(";
        $separator = '';

        $primaryColumnName = null;

        foreach($entity->columns as $column){
            $output .= $separator.'$'.$column[0].' = null';
            $separator = ', ';

            if (array_key_exists('primary', $column) == true) {
                if ($column['primary']) {
                    $primaryColumnName = $column[0];
                }
            }
        }

        $output .= ") {\n";

        foreach($entity->columns as $column){
            $output .= $st.$st."\$this->".$column[0]." = \$".$column[0].";\n";
        }
        $output .= $st."} \n";

        foreach($entity->columns as $column){
            $fieldName = $column[0];
            $output .= $st."function set".mb_ucfirst($fieldName).'($'.$fieldName.") { \n";
            $output .= $st.$st."\$this->".$fieldName.' = $'.$fieldName.";\n";
            $output .= $st."}\n\n";
        }

//        $lcTableName = mb_lcfirst($tableMap->getTableName());

//        if ($tableMap instanceof \EntityMap\SQLTableMap) {
//            $queryType  = '\\EntityMap\SQLQuery';
//        }
//        else if ($tableMap instanceof \EntityMap\YAMLTableMap) {
//            $queryType  = '\\EntityMap\YAMLQuery';
//        }
//        else {
//            throw new \Exception("Unknown tablemap type [".get_class($tableMap)."]");
//        }

//        $fullClassName = '\\'.get_class($tableMap);

//        $output .= "
//
//    /**
//     * @param \$query $queryType
//     * @param \$$lcTableName $fullClassName
//     * @return int
//     */
//    function insertInto($queryType \$query, $fullClassName \$".$lcTableName."){\n
//        \$data = convertObjectToArray(\$this);
//        \$insertID = \$query->insertIntoMappedTable(\$".$lcTableName.", \$data);\n";
//
//        if ($primaryColumnName) {
//            $output .= $st."\$this->$primaryColumnName = \$insertID;\n";
//        }
//
//        $output .= "
//        return \$insertID;
//    }
//";

        $output .= "}\n\n";
        return $output;
    }
}

 