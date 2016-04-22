<?php

namespace Seracademico\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Titulacao extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "fac_titulacoes";

    protected $fillable = [
        'nome'
    ];

}
