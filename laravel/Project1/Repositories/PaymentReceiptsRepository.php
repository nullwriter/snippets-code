<?php

namespace App\Repositories\Backend\RRHH;

use App\Models\Employee\Paysheet;
use App\Models\RRHH\Concept;
use App\Models\RRHH\Constant;
use App\Models\RRHH\HistoricConcept;
use App\Repositories\Repository;
use Illuminate\Support\Facades\App;
use Log;

/**
 * Class PaymentReceiptsRepository
 * @package App\Repositories\User
 */
class PaymentReceiptsRepository extends Repository
{
	/**
	 * Associated Repository Model
	 */
	const MODEL = Concept::class;

    /**
     */
    public function __construct()
    {
    }

	/**
	 * Returns calculated Cesta Ticket.
	 * @return float
	 */
	Public function cestaticket(){
		$cupones = Constant::where('code_constant','LIKE','CUPONES')->first();
		return $valor = round($cupones->value*30,2);
	}

	/**
	 * Returns DataTables query object executed.
	 * @return dataTable obj
	 */
	public function getForDataTable(){

		$dataTableQuery = $this->query()
			->with(['type_concept','historic_concepts' => function($query){
				$query->where('closed_date'); // IS NULL (implicit)
			}]);

		return $dataTableQuery->get();
    }

	/**
	 * Returns historic data for a specific Paysheet.
	 * @param int $id
	 * @return array
	 */
	public function paysheetHistoric($id){

		$paysheetObj = Paysheet::find($id);
		$date = $paysheetObj->start_date;

		return $Paysheets = Paysheet::where('id',$id)
				->with('employee')
				->with('contract')
				->with(['paysheet_details' => function($query) use ($date){
					$query->with(['concept'=> function($query) use ($date){
						if($date > date("2017-03-08")){
							$query->whereHas('historic_concepts', function($query)  use ($date){
								$query->where('opened_date','<=',date($date));
								$query->where('closed_date','>',date($date));
							});
						}
					}]);
				}])->get();
	}


	/**
	 * Activates a concept.
	 * @param null $conceptId
	 */
	public function activateConcept($conceptId = null){

		$concept = $this->find($conceptId);
		if(!empty($concept)){

			$historic = HistoricConcept::where('concept_id',$conceptId)
										->where('opened_date','=',date("Y-m-d"))
										->where('closed_date','=',null)->get();

			if(sizeof($historic) <= 0){
				$historic_concept = new HistoricConcept;
				$historic_concept->concept_id = $conceptId;
				$historic_concept->opened_date = date("Y-m-d H:i:s");
				$historic_concept->save();
			}
		}
	}

	/**
	 * Deactivates a concept.
	 * @param null $conceptId
	 */
	public function deactivateConcept($conceptId = null){

		$concept = $this->find($conceptId);
		if(!empty($concept)){

			$historic = HistoricConcept::where(['concept_id'=>$conceptId,'closed_date'=>null])->first();

			if(!empty($historic)){
				$historic = HistoricConcept::find($historic->id);
				$historic->closed_date = date("Y-m-d H:i:s");
				$historic->save();
			}
		}
	}

	/**
	 * Returns a list of active concepts.
	 */
	public function getActiveConcepts(){
		return HistoricConcept::where('opened_date','!=',null)->where('closed_date','=',null)->with('concept')->get();
	}

	/**
	 * Creates a PDF from a view passed as parameter.
	 * @param $view
	 */
	public function viewPDF($view){
		
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);

		return $pdf;
	}

}
