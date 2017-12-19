<?php 
namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    protected $invalids = [
        'Yahoo Customer Support',
        'Fcuk'
    ];
    
    public function detect($body)
    {
        foreach ($this->invalids as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Content contains spam!');
            }
        }
    }
}
