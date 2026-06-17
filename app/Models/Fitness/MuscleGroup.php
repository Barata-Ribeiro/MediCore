<?php

namespace App\Models\Fitness;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('muscle_groups')]
#[Fillable(['name'])]
class MuscleGroup extends Model
{
    //
}
