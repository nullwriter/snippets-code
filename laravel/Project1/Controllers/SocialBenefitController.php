<?php
/**
 * Created by PhpStorm.
 * User: cfeo
 * Date: 25/1/2017
 * Time: 10:19 AM
 */

namespace App\Http\Controllers\Backend\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Models\Job\RequestSocialBenefit;
use App\Repositories\Backend\RRHH\SocialBenefitRepository;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Facades\Datatables;
use Log;

class SocialBenefitController extends Controller
{

    /**
     * @param SocialBenefitRepository $prestaciones
     */
    public function __construct(SocialBenefitRepository $prestaciones)
    {
        $this->social_benefits = $prestaciones;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(){
        return view('backend.RRHH.requestsocialbenefit.index');
    }

    /**
     * Cambia el estatus de una solicitud de prestacion
     * @param Request $request
     */
    public function changeStatus(Request $request){

        $id = $request->get('id');
        if($id != null)
            $this->social_benefits->changeStatus($id,$request->get('status'));
    }

    /**
     * Genera el PDF de una prestacion y retorna una descarga del mismo.
     * @param Request $request
     */
    public function downloadRequest($id){

        $requestObj =  RequestSocialBenefit::find($id);
        $employee = $requestObj->employee;

        $pdf = $this->social_benefits->getPDFObj($employee,$requestObj);

        return $pdf->download('Anticipo_de_Prestaciones.pdf');
    }

    /**
     * Abre un PDF de una prestacion en una pestaña nueva.
     * @param Request $request
     */
    public function viewRequest($id){

        $requestObj =  RequestSocialBenefit::find($id);
        $employee = $requestObj->employee;

        $pdf = $this->social_benefits->getPDFObj($employee,$requestObj);

        return $pdf->stream('Anticipo_de_Prestaciones.pdf');
    }

    /**
     * Genera la data para el DataTables.
     * @param Request $request
     */
    public function solicitudesTable(ManageUserRequest $request) {

        return Datatables::of($this->social_benefits->getForDataTable($request->get('status'), $request->get('trashed'), $request->get('tipo')))
            ->editColumn('estado', function($p) {

                $estado = 'Pendiente';

                if($p->estado === 1)
                    $estado = 'Aprobado';
                elseif($p->estado === 0)
                    $estado = 'Negado';

                return $estado;
            })
            ->filter(function ($query) use ($request) {
                if($request->has('search'))
                    if ($this->isLookingForState($request->get('search')['value'])) {

                        $search = null;
                        $term = strtolower($request->get('search')['value']);

                        if(!empty($term)) {

                            if (strpos('aprobado', $term) !== false) {
                                $search = 1;
                            } else if (strpos('rechazado', $term) !== false) {
                                $search = 0;
                            }

                            $query->where('estado', '=', $search);
                        }
                    }
            })
            ->addColumn('acciones', function($p) {
                return  view('backend.RRHH.requestsocialbenefit.actions', compact('p'));
            })
            ->addColumn('created_at', function($p){
                return date('j M Y h:ia',strtotime($p->created_at));
            })
            ->withTrashed()
            ->make(true);
    }

    /**
     * Funcion helper para DataTables que retorna un booleano si el search term tiene que 
     * ver con la busqueda de un estatus.
     * @param $term
     */
    private function isLookingForState($term){

        if(empty($term))
            return false;

        $term = strtolower($term);

        if (strpos('aprobado',$term) !== false) {
            return true;
        }
        else if (strpos('rechazado',$term) !== false){
            return true;
        }
        else if (strpos('pendiente',$term) !== false){
            return true;
        }

        return false;
    }
}