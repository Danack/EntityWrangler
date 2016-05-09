<?php

namespace EntityWranglerTest\TableGateway;

class IssueTableGateway
{
    private $data;
    private $prefix;
    
    public function fetchAll()
    {
        
    }
    
    public static function fromResultSet(array $data, $prefix)
    {
        $instance = new self();
        $instance->data = $data;
        $instance->prefix = $prefix;

        return $instance;
    }
}
