<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreeEntry extends Model
{
    use HasFactory;

    protected $table = "tree_entry";

    public $timestamps = false;

}
