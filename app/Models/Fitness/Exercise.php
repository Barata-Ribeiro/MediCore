<?php

namespace App\Models\Fitness;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('exercises')]
#[Fillable(['name', 'description', 'video_url'])]
class Exercise extends Model
{
    //
}
