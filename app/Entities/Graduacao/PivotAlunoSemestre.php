<?php

namespace Seracademico\Entities\Graduacao;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Seracademico\Entities\SituacaoAluno;
use Seracademico\Entities\Turno;
use Seracademico\Uteis\SerbinarioDateFormat;

class PivotAlunoSemestre extends Pivot implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'fac_alunos_semestres';

    /**
     * @var array
     */
    protected $fillable = [
        'aluno_id',
        'semestre_id',
        'periodo'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function situacoes()
    {
        return $this->belongsToMany(SituacaoAluno::class, "fac_alunos_situacoes", "aluno_semestre_id", "situacao_id");
    }
}