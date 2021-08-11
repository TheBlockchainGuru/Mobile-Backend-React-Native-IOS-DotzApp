<?php namespace App\Controllers;

use App\Models\LocationsModel;
use App\Models\LocationsPendingModel;
use App\Models\ActivitiesLocsRelsModel;
use App\Models\ActivitiesModel;

class Locs_pending extends BaseController
{
    public function index()
    {
		$LocationsPendingModel = new LocationsPendingModel;
		$data['page'] = 'locs_pending';
		$data['isAdmin'] = session()->get('role') === 'ADMIN' || session()->get('role') === 'SUPERADMIN' ? true : false;
		if (session()->get('role') === 'SUPERADMIN') $data['isSAdmin'] =  true;

		$data['locs'] = $LocationsPendingModel->findAll();
		// $data['locs'] = $LocationsPendingModel->select('loc_p_cors_start, loc_p_cors_finish, loc_p_cors_all')->findAll();
        

		echo view('templates/header', $data);
		echo view('pages/locs_pending');
		echo view('templates/footer');
	}
	
	public function approve()
	{
		$data = [];

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'loc_p_id' => 'integer|matches[loc_p_id]',
			];

			if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
                echo $this->request->getVar('loc_p_id');
                echo $this->validator->listErrors();
			} else {
				$modelLocationsPending = new LocationsPendingModel();
				$modelAc_loc_rels = new ActivitiesLocsRelsModel;
				$modelActivities = new ActivitiesModel;
				$approved_loc = $modelLocationsPending->find($this->request->getVar('loc_p_id'));

				if ( $modelActivities->find( $approved_loc["loc_p_activity_id"] ) ) {
					$activity_id = $approved_loc["loc_p_activity_id"];
				} else {
					return redirect()->to('/locs_pending');
				}
				
				$modelLocations = new LocationsModel();
				$data = [
					"loc_title" => $approved_loc["loc_p_title"],
					"loc_cors_start" => $approved_loc["loc_p_cors_start"],
					"loc_cors_finish" => $approved_loc["loc_p_cors_finish"],
					"loc_cors_all" => $approved_loc["loc_p_cors_all"],
					"loc_city" => $approved_loc["loc_p_city"],
				];
				$loc_id = $modelLocations->insert($data);
				
				if ($loc_id) {
					if ($modelAc_loc_rels->save( ["loc_id" => $loc_id, "activity_id" => $activity_id] )) {
						$modelLocationsPending->delete($this->request->getVar('loc_p_id'));
						$session = session();
						$session->setFlashdata('success', 'Successful Deletion');
						
						return redirect()->to('/locs_pending');
					}
				}
			}
		}
	}
	
	public function delete()
	{
		$data = [];

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'loc_p_id' => 'integer|matches[loc_p_id]',
			];

			if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
                echo $this->request->getVar('loc_p_id');
                echo $this->validator->listErrors();
			} else {
                $model = new LocationsPendingModel();
                
				$model->delete($this->request->getVar('loc_p_id'));
				$session = session();
                $session->setFlashdata('success', 'Successful Deletion');
                
				return redirect()->to('/locs_pending');
			}
		}
	}
}