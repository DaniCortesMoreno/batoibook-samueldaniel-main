<?php
namespace BatoiBook\Controllers\Models;

class Course
{
   public function __construct(
   public int $id = 0,
   public string $course = '',
   public int $familyId = 0,
   public string $vliteral = '',
   public string $cliteral = '' ) 
   {}

}