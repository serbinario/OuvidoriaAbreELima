<?php

namespace Seracademico\Http\Controllers;

use Illuminate\Http\Request;

use Seracademico\Http\Requests;
use Seracademico\Services\TurmaService;
use Yajra\Datatables\Datatables;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Seracademico\Validators\TurmaValidator;

class TurmaController extends Controller
{
    /**
    * @var TurmaService
    */
    private $service;

    /**
    * @var TurmaValidator
    */
    private $validator;

    /**
    * @var array
    */
    private $loadFields = [
        'Curso',
        'Turno',
        'Sala|situacao,1',
        'Professor'
    ];

    /**
    * @param TurmaService $service
    * @param TurmaValidator $validator
    */
    public function __construct(TurmaService $service, TurmaValidator $validator)
    {
        $this->service   =  $service;
        $this->validator =  $validator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        #Carregando os dados para o cadastro
        $loadFields = $this->service->load($this->loadFields);

        #retorno
        return view('turma.index', compact('loadFields'));
    }

    /**
     * @return mixed
     */
    public function grid()
    {
        #Criando a consulta
        $rows = \DB::table('fac_turmas')
            ->leftJoin('fac_curriculos', 'fac_curriculos.id', '=', 'fac_turmas.curriculo_id')
            ->leftJoin('fac_cursos', 'fac_curriculos.curso_id', '=', 'fac_cursos.id')
            ->leftJoin('fac_turnos', 'fac_turnos.id', '=', 'fac_turmas.turno_id')
            ->select([
                'fac_turmas.id',
                'fac_turmas.codigo as codigo_turma',
                'fac_turmas.valor_turma',
                'fac_turmas.valor_disciplina',
                \DB::raw('DATE_FORMAT(fac_turmas.aula_inicio, "%d/%m/%Y") as aula_inicio'),
                \DB::raw('DATE_FORMAT(fac_turmas.aula_final, "%d/%m/%Y") as aula_final'),
                'fac_cursos.codigo',
                'fac_cursos.nome',
                'fac_turnos.nome as turno'
            ]);

        #Editando a grid
        return Datatables::of($rows)->addColumn('action', function ($row) {
            return '<a href="edit/'.$row->id.'"  title="Editar da turma" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
            <a title="Calendário da turma" data-id="'. $row->id  .'" href="#" class="modal-calendario btn btn-xs btn-primary"><i class="glyphicon glyphicon-book"></i></a>';
        })->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        #Carregando os dados para o cadastro
        $loadFields = $this->service->load($this->loadFields);

        #Retorno para view
        return view('turma.create', compact('loadFields'));
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

            #Validando a requisição
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

            #Executando a ação
            $this->service->store($data);

            #Retorno para a view
            return redirect()->back()->with("message", "Cadastro realizado com sucesso!");
        } catch (ValidatorException $e) {
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        } catch (\Throwable $e) {print_r($e->getMessage()); exit;
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            #Recuperando a empresa
            $model = $this->service->find($id);

            #Carregando os dados para o cadastro
            $loadFields = $this->service->load($this->loadFields);

            #retorno para view
            return view('turma.edit', compact('model', 'loadFields'));
        } catch (\Throwable $e) {dd($e);
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            #Recuperando os dados da requisição
            $data = $request->all();

            #Validando a requisição
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            #Executando a ação
            $this->service->update($data, $id);

            #Retorno para a view
            return redirect()->back()->with("message", "Alteração realizada com sucesso!");
        } catch (ValidatorException $e) {
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

}
