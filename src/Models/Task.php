<?php

namespace App\Models;

class Task extends Model
{
    protected array $propNames = [
        'id',
        'user',
        'email',
        'description',
        'edited',
        'done',
    ];
}