<?php

namespace App\Http\Controllers\Backend\RRHH;

use App\Http\Controllers\Controller;
use App\Models\RRHH\Concept;
use App\Models\RRHH\HistoricConcept;
use App\Repositories\Backend\RRHH\PaymentReceiptsRepository;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Log;

class PaymentReceiptsController extends Controller
{
    /**
     * @param PaymentReceiptsRepository $paymentR
     */
    public function __construct(PaymentReceiptsRepository $paymentR)
    {
        $this->payment_receipts = $paymentR;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(){
        return view('backend.RRHH.paymentreceipts.index');
    }

    /**
     * Vista que retorna una lista de conceptos.
     */
    public function conceptsList(){
        $concepts = $this->payment_receipts->getActiveConcepts();
        return view('backend.RRHH.paymentreceipts.concepts-list')
                ->withConcepts($concepts);
    }

    /**
     * Retorna conceptos activos.
     */
    public function getActiveConcepts(){
        return $this->payment_receipts->getActiveConcepts();
    }

    /**
     * Activa un concepto.
     * @param Request $request
     */
    public function activateConcept(Request $request){
        $id = $request->get('id');
        $this->payment_receipts->activateConcept($id);
        return $id;
    }

    /**
     * Desactiva un concepto.
     * @param Request $request
     */
    public function deactivateConcept(Request $request){
        $id = $request->get('id');
        $this->payment_receipts->deactivateConcept($id);
        return $id;
    }

    /**
     * Funcion que retorna la lista de conceptos para DataTables.
     */
    public function getConcepts(){

        return Datatables::of($this->payment_receipts->getForDataTable())
            ->addColumn('status', function($p) {
                $estado = 'Activo';

                if(sizeof($p->historic_concepts) <= 0){
                    $estado = "Inactivo";
                }

                return $estado;
            })
            ->addColumn('actions', function($p) {
                return  view('backend.RRHH.paymentreceipts.actions', compact('p'));
            })
            ->withTrashed()
            ->make(true);
    }
}
