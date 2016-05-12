<?php

namespace EntityWranglerTest\Model;

class Issue
{
    public $issueId;
    
    public $description;
    
    public $text;

    function __construct($issueId, $description, $text)
    {
        $this->issueId = $issueId;
        $this->description = $description;
        $this->text = $text;
    }
}
