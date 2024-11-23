<?php
namespace BatoiBook\Controllers\Models;

use DateTimeImmutable as Date;
use Exception;

class Book
{
   public function __construct(
       public int $id = 0,
       public int $userId = 0,
       public int $moduleCode = 0,
       public string $publisher = '',
       public int $price  = 0,
       public int $pages = 0,
       public string $status = '',
       public string $photo = '',
       public string $comments = '',
       public $soldDate = null // Permite null para hacer la comprobación
   ) {
       // Si soldDate es null, asigna la fecha actual
       $this->soldDate = $soldDate ?? new Date();
   }
}
