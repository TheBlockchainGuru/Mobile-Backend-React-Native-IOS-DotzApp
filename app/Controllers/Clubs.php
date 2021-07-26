<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClubsModel;

class Clubs extends BaseController
{
    public function index()
    {
		$modelClubs = new ClubsModel;
		$data['page'] = 'clubs';
		$data['isAdmin'] = session()->get('role') === 'ADMIN' || session()->get('role') === 'SUPERADMIN' ? true : false;
		if (session()->get('role') === 'SUPERADMIN') $data['isSAdmin'] =  true;

		$data['clubs'] = $modelClubs->findAll();
        $data['msg'] = session()->getFlashdata('msg');

		echo view('templates/header', $data);
		echo view('pages/clubs');
		echo view('templates/footer');
	}
	
	public function addClub()
	{
		if (empty($_FILES['club_img']['name'])) return 'No file uploaded';
		
        $img = $this->request->getFile('club_img');
        if ($img->isValid() && ! $img->hasMoved()) {
            $img->move('./uploads/clubs',$img->getClientName());
        }
		$modelClubs = new ClubsModel;
		$club_name = $this->request->getVar('club_name');

		$data = array(
			'club_name' => $club_name,
			'club_description' => $this->request->getVar('club_description'),
			'club_img' => $img->getClientName(),
		);

		if ($modelClubs->save($data)) {
			$session = session();
			$session->setFlashdata('msg', "\"{$club_name}\" added successfuly!");
			return redirect()->to(base_url('/clubs'));
		} else {
			$session = session();
			$session->setFlashdata('msg', "Error adding \"{$club_name}\"");
		}
	}

    public function updateClub()
    {
		if ($this->request->getMethod() !== 'post') return;
		if (!empty($_FILES['club_img']['name'])) {
			$img = $this->request->getFile('club_img');
			if ($img->isValid() && ! $img->hasMoved()) {
				$img->move('./uploads/clubs',$img->getClientName());
			}
		} else {
			$img = null;
		}
	
		$modelClubs = new ClubsModel();
		$club_name = $this->request->getVar('club_name');

		$id = $this->request->getVar('club_id');
		$newData = [
			'club_name' => $this->request->getVar('club_name'),
			'club_description' => $this->request->getVar('club_description'),
		];
		if ($img) $newData['club_img'] = $img->getClientName();

		if ($modelClubs->update($id, $newData)) {
			$session = session();
			$session->setFlashdata('msg', "\"{$club_name}\" updated!");
			return redirect()->to(base_url('/clubs'));
		} else {
			$session = session();
			$session->setFlashdata('msg', "Error updating \"{$club_name}\"");
		}
    }

	public function delete_club()
	{
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'club_id' => 'integer|matches[club_id]',
			];
			$club_id = $this->request->getVar('club_id');
			$club_name = $this->request->getVar('club_name');

			if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
                echo $club_id;
                echo $this->validator->listErrors();
			} else {
                $model = new ClubsModel();
                
				$model->delete($club_id);
				$session = session();
                $session->setFlashdata('msg', "{$club_name} was deleted");
                
				return redirect()->to('/clubs');
			}
		}
	}
}