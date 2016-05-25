<?php

namespace Seracademico\Http\Controllers\Graduacao;

use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Seracademico\Entities\Graduacao\Curriculo;
use Seracademico\Http\Requests;
use Seracademico\Http\Controllers\Controller;
use Seracademico\Services\Graduacao\VestibulandoService;
use Seracademico\Validators\Graduacao\VestibulandoValidator;
use Yajra\Datatables\Datatables;

class VestibulandoController extends Controller
{
    /**
     * @var VestibulandoService
     */
    private $service;

    /**
     * @var VestibulandoValidator
     */
    private $validator;

    /**
     * @var array
     */
    private $loadFields = [
        'Graduacao\\Semestre',
        'Turno',
        'Sexo',
        'EstadoCivil',
        'GrauInstrucao',
        'Profissao',
        'CorRaca',
        'TipoSanguinio',
        'Estado',
        'CorRaca',
        'Graduacao\\Vestibular',
        'Graduacao\\Curso|ativo,1',
        'Turno',
        'Sala',
        'LinguaExtrangeira'
    ];

    /**
     * @param VestibulandoService $service
     * @param VestibulandoValidator $validator
     */
    public function __construct(VestibulandoService $service, VestibulandoValidator $validator)
    {
        $this->service    = $service;
        $this->validator  = $validator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        #Carregando os dados para o cadastro
        $loadFields = $this->service->load($this->loadFields);

        return view('vestibulando.index', compact('loadFields'));
    }

    /**
     * @return mixed
     */
    public function grid(Request $request)
    {
        #Criando a consulta
        $alunos = \DB::table('fac_vestibulandos')
            ->join('pessoas', 'pessoas.id', '=', 'fac_vestibulandos.pessoa_id')
            ->join('fac_vestibulares', 'fac_vestibulares.id', '=' , 'fac_vestibulandos.vestibular_id')
            ->join('fac_semestres', 'fac_semestres.id', '=', 'fac_vestibulares.semestre_id')
            ->leftJoin('fac_cursos as curso1', 'curso1.id', '=', 'fac_vestibulandos.primeira_opcao_curso_id')
            ->leftJoin('fac_cursos as curso2', 'curso2.id', '=', 'fac_vestibulandos.segunda_opcao_curso_id')
            ->leftJoin('fac_cursos as curso3', 'curso3.id', '=', 'fac_vestibulandos.terceira_opcao_curso_id')
            ->leftJoin('fac_turnos as turno1', 'turno1.id', '=', 'fac_vestibulandos.primeira_opcao_turno_id')
            ->leftJoin('fac_turnos as turno2', 'turno2.id', '=', 'fac_vestibulandos.segunda_opcao_turno_id')
            ->leftJoin('fac_turnos as turno3', 'turno3.id', '=', 'fac_vestibulandos.terceira_opcao_turno_id')
            ->select([
                'fac_vestibulandos.id',
                'pessoas.nome',
                'pessoas.cpf',
                'pessoas.celular',
                'fac_vestibulandos.inscricao',
                'curso1.nome as nomeCurso1',
                'curso2.nome as nomeCurso2',
                'curso3.nome as nomeCurso3',
                'turno1.nome as nomeTurno1',
                'turno2.nome as nomeTurno2',
                'turno3.nome as nomeTurno3',
                'fac_vestibulares.nome as vestibular'
            ]);

        #Editando a grid
        return Datatables::of($alunos)
            ->filter(function ($query) use ($request) {
                if ($request->has('semestre')) {
                    $query->where('fac_semestres.id', '=', $request->get('semestre'));
                }
            })
            ->addColumn('action', function ($aluno) {
                return '<div class="fixed-action-btn horizontal">
                        <a class="btn-floating btn-main"><i class="large material-icons">dehaze</i></a>
                        <ul>
                            <li><a class="btn-floating" href="edit/'.$aluno->id.'" title="Editar aluno"><i class="material-icons">edit</i></a></li>
                            <li><a class="btn-floating" id="inclusao" title="Trasnferir para aluno"><i class="material-icons">chrome_reader_mode</i></a></li>
                            <li><a class="btn-floating" id="notas" title="Notas"><i class="material-icons">chrome_reader_mode</i></a></li>
                        </ul>
                        </div>';
            })->make(true);
    }

    /**
     * @return mixed
     */
    public function gridNotas($idVestibulando)
    {
        #Criando a consulta
        $alunos = \DB::table('fac_vestibulandos_notas_vestibulares')
            ->join('fac_materias', 'fac_materias.id', '=', 'fac_vestibulandos_notas_vestibulares.materia_id')
            ->join('fac_vestibulandos', 'fac_vestibulandos.id', '=', 'fac_vestibulandos_notas_vestibulares.vestibulando_id')
            ->where('fac_vestibulandos.id', $idVestibulando)
            ->select([
                'fac_vestibulandos_notas_vestibulares.id',
                'fac_materias.codigo',
                'fac_materias.nome',
                'fac_vestibulandos_notas_vestibulares.acertos',
                'fac_vestibulandos_notas_vestibulares.pontuacao'
            ]);

        #Editando a grid
        return Datatables::of($alunos)->addColumn('action', function ($aluno) {
            return '<a class="btn-floating" id="editarNotas" title="Editar notas do vestibulando"><i class="material-icons">edit</i></a>';
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
        return view('vestibulando.create', compact('loadFields'));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
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
        } catch (\Throwable $e) {var_dump($e); exit;
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
            #Recuperando o aluno
            $aluno = $this->service->find($id);

            #Carregando os dados para o cadastro
            $loadFields = $this->service->load($this->loadFields);

            #retorno para view
            return view('vestibulando.edit', compact('aluno', 'loadFields'));
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

            #tratando as rules
            $this->validator->replaceRules(ValidatorInterface::RULE_UPDATE, ":id", $id);

            #Validando a requisição
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            #Executando a ação
            $this->service->update($data, $id);

            #Retorno para a view
            return redirect()->back()->with("message", "Alteração realizada com sucesso!");
        } catch (ValidatorException $e) {
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        } catch (\Throwable $e) { dd($e);
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function editNota(Request $request)
    {
        try {
            #Recuperando o aluno
            $nota  = $this->service->findNota($request->all());

            # Preparando o array de retorno
            $dados = [
                'codigo' => $nota->materia->codigo,
                'materia' => $nota->materia->nome,
                'acertos' => $nota->acertos,
                'pontuacao' => $nota->pontuacao,
            ];

            #Retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => true,'data' => $dados]);
        } catch (\Throwable $e) {
            #Retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateNota(Request $request, $id)
    {
        try {
            #Recuperando os dados da requisição
            $data = $request->all();

            #Executando a ação
            $this->service->updateNota($data, $id);

            #Retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => true,'msg' => 'Alteração realizada com sucesso']);
        } catch (\Throwable $e) {
            #Retorno para a view
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
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
     * @param $idVestibulando
     * @return mixed
     */
    public function editInclusao($idVestibulando)
    {
        try {
            # Recuperando o vestibulando
            $vestibulando = $this->service->find($idVestibulando);
            $dadosRetorno = [];

            # Validando se existe uma inclusão cadastrada
            if(!$vestibulando->inclusao) {
                $inclusao = $vestibulando->inclusao()->create(['data_inclusao'=>null]);
            } else {
                $inclusao = $vestibulando->inclusao;
            }

            # Populando o array de retorno
            $dadosRetorno['curso_id'] = isset($inclusao->curriculo->id) ? $inclusao->curriculo->curso->id : null;
            $dadosRetorno['turno_id'] = isset($inclusao->turno->id) ? $inclusao->turno->id : null;
            $dadosRetorno['data_inclusao'] = $inclusao->data_inclusao;
            $dadosRetorno['forma_admissao_id'] = isset($inclusao->formaAdmissao->id) ? $inclusao->formaAdmissao->id : null;


            #retorno para view
            return \Illuminate\Support\Facades\Response::json(['success' => true, 'dados' => $dadosRetorno]);
        } catch (\Throwable $e) {dd($e);
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateInclusao(Request $request, $id)
    {
        try {
            #Recuperando os dados da requisição
            $data = $request->all();

            #Executando a ação
            $this->service->updateInclusao($data, $id);

            #retorno para view
            return \Illuminate\Support\Facades\Response::json(['success' => true, 'msg' => 'Vestibulando transferido com sucesso!']);
        } catch (\Throwable $e) {dd($e);
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        try {
            #Recuperando os dados da requisição
            $data = $request->all();

            #Executando a ação
            $dados = $this->service->search(key($data), $data[key($data)]);

            #retorno para view
            return \Illuminate\Support\Facades\Response::json(['success' => true, 'dados' => $dados]);
        } catch (\Throwable $e) {
            return \Illuminate\Support\Facades\Response::json(['success' => false,'msg' => $e->getMessage()]);
        }
    }
}