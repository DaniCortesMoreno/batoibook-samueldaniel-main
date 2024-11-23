<?php
namespace BatoiBook\Controllers\Models;

class Module
{
   public function __construct(
   public $code = '',
   public $cliteral = '', 
   public $vliteral = '',
   public int $courseId = 0
) 
   {}

}