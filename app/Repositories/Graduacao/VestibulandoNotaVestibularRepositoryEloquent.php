<?php

namespace Seracademico\Repositories\Graduacao;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Seracademico\Entities\Graduacao\VestibulandoNotaVestibular;

/**
 * Class AlunoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class VestibulandoNotaVestibularRepositoryEloquent extends BaseRepository implements VestibulandoNotaVestibularRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return VestibulandoNotaVestibular::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
