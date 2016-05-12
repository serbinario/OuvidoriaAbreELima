<?php

namespace Seracademico\Http\Controllers;

use Illuminate\Http\Request;

use Seracademico\Http\Requests;
use Seracademico\Services\VestibularService;
use Yajra\Datatables\Datatables;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Seracademico\Validators\VestibularValidator;

class VestibularCursoMateriaController extends Controller
{
    /**
    * @var VestibularService
    */
    private $service;

    /**
    * @var array
    */
    private $loadFields = [
    ];

    /**
    * @param VestibularService $service
    */
    public function __construct(VestibularService $service)
    {
        $this->service   =  $service;
    }


    /**
     * @return mixed
     */
    public function grid($idVestibularCurso)
    {
        #Criando a consulta
        $rows = \DB::table('vestibular_curso_materia')
            ->join('fac_materias', 'fac_materias.id', '=', 'vestibular_curso_materia.materia_id')
            ->join('vestibulares_cursos', 'vestibulares_cursos.id', '=', 'vestibular_curso_materia.vestibular_curso_id')
            ->join('fac_cursos', 'fac_cursos.id', '=', 'vestibulares_cursos.curso_id')
            ->where('vestibulares_cursos.id', $idVestibularCurso)
            ->select([
                'vestibular_curso_materia.id',
                'fac_materias.id as idMateria',
                'fac_materias.nome',
                'fac_materias.codigo',
                'fac_cursos.id as idCurso',
                'vestibular_curso_materia.peso',
                'vestibular_curso_materia.qtd_questoes'
            ]);

        #Editando a grid
        return Datatables::of($rows)->addColumn('action', function ($row) {
            return '<a class="btn-floating indigo" id="removerCursoMateria" title="remover Curso"><i class="material-icons">delete</i></a>';
        })->make(true);
    }

    /**
     * @param Request $request
     * @return mixed
     *
     */
    public function getLoadFields(Request $request)
    {
        try {
            return $this->service->load($request->get("models"), true);
        } catch (\Throwable $e) {
            return \Illuminate\Support\Facades\Response::json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        try {
            #Recuperando os dados da requisição
            $data = $request->all();

            #Executando a ação
            $this->service->deleteCursoMateria($data);

            #retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => true,'msg' => 'Remoção realizada com sucesso!']);
        } catch (\Throwable $e) {
            #Retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return $this|array|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            #Recuperando os dados da requisição
            $data = $request->all();

            #Executando a ação
            $this->service->storeCursoMateria($data);

            #retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => true,'msg' => 'Cadastro realizado com sucesso!']);
        } catch (\Throwable $e) {
            #Retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
    }
}
