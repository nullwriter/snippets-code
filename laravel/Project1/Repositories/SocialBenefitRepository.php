<?php

namespace App\Repositories\Backend\RRHH;

use App\Models\Job\RequestSocialBenefit;
use App\Repositories\Repository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Log;

/**
 * Class SocialBenefitRepository
 * @package App\Repositories\Backend\RRHH
 */
class SocialBenefitRepository extends Repository
{
	/**
	 * Associated Repository Model
	 */
	const MODEL = RequestSocialBenefit::class;

    /**
     */
    public function __construct()
    {
    }

	/**
	 * Returns PDF object with data.
	 * @param $employee
	 * @param $requestObj
	 * @return mixed
	 */
	public function getPDFObj($employee, $requestObj){

		$view = View::make('backend.RRHH.requestsocialbenefit.pdf.electronic_request',compact('employee','requestObj'))->render();

		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);

		return $pdf;
	}

	/**
	 * Returns DataTables query object.
	 * @param int $status
	 * @param bool $trashed
	 * @return mixed
	 */
	public function getForDataTable($status = 1, $trashed = false, $tipo = 'pendiente')
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
		$dataTableQuery = $this->query()
			->with(['employee','reason_social']);

		if($tipo != 'pendiente'){
			$dataTableQuery->where('estado','!=',null);
		}
		else {
			$dataTableQuery->where('estado','=',null);
		}

		if ($trashed == "true") {
			return $dataTableQuery->onlyTrashed();
		}

		return $dataTableQuery;
    }


	/**
	 * Changes status to a SocialBenefit.
	 * @param int $status
	 */
	public function changeStatus($id,$status = 0){
		$rsb = RequestSocialBenefit::find($id);
		$rsb->estado = $status;
		$rsb->save();
	}

}
