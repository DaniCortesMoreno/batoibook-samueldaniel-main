<?php
namespace BatoiBook\Controllers\Models;

class User 
{
    public function __construct(
        public int $id = 0,            // Assuming 'id' is an integer
        public string $email = '',     // Email as a string
        public string $nick = '',      // Nickname as a string
        public string $password = ''   // Password as a string
    ) {}
}
