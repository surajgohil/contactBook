<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index(){

        $userId = $this->session->userdata('id');

        if($userId > 0){
            $this->load->view('header');
            $this->load->view('dashboard');
            $this->load->view('footer');
        }else{
            redirect('signIn');
        }
    }

    public function saveChanges(){

        $this->form_validation->set_rules('firstName', 'First Name', 'required|alpha');
        $this->form_validation->set_rules('lastName', 'Last Name', 'required|alpha');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        $this->form_validation->set_rules('number', 'Number', 'required|min_length[10]');

        if($this->form_validation->run() == FALSE){

            $responseArray = [
                'status' => 3,
                'data' => [
                    'firstName' => form_error('firstName'),
                    'lastName'  => form_error('lastName'),
                    'email'     => form_error('email'),
                    'number'    => form_error('number'),
                ]
            ];

            echo json_encode($responseArray, true);

        }else{

            $post      = $this->input->post();
            $firstName = $post['firstName'];
            $lastName  = $post['lastName'];
            $email     = $post['email'];
            $number    = $post['number'];
            $groupId   = $post['groupId'];
            $id        = ($post['id'] > 0) ? $post['id'] : 0;

            if($id == 0){

                $insertData = [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'number'     => $number,
                    'rel_id'     => $this->session->userdata('id'),
                ];

                if($groupId > 0){
                    $insertData['group_id'] = $groupId;
                }

                $saveChanges = $this->ProjectModel->insertData('usernumbers', $insertData);

            }else{

                $saveChanges = $this->ProjectModel->updateData('usernumbers', ['id' => $id], [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'number'     => $number,
                    'rel_id'     => $this->session->userdata('id'),
                ]);
            }

            if($saveChanges){
                echo json_encode(['status' => 1, 'message' => 'Data is save succefully.'], true);
            }else{
                echo json_encode(['status' => 0, 'message' => 'Data is not save.'], true);
            }
        }
    }

    public function contactListing(){

        $post        = $this->input->post();
        $start       = $post['start'];
        $length      = $post['length'];
        $draw        = $post['draw'];
        $searchValue = $post['search_value'];
        $orderColumn = $post['order_column'];
        $orderDir    = $post['order_dir'];
        $groupId     = $post['groupId'];
        $data        = [];

        if(!empty($orderColumn) || !empty($orderDir)){
            $userListing = renderUserContact($start, $length, $searchValue, ['usernumbers'.'.'.$orderColumn, $orderDir], $groupId);
        }else{
            $userListing = renderUserContact($start, $length, $searchValue, ['usernumbers'.'.id', $orderDir], $groupId);
        }

        // Get groups
        $groupList = $this->ProjectModel->selectData(0, 'contact_groups', '*', ['user_id' => $this->session->userdata('id')]);


        if(!empty($userListing)){

            foreach ($userListing as $key => $value) {

                $html = [
                    // 'DT_RowId'   => 'userId_'.$value['id'],
                    'id'         => '<label for="checkBoxId_'.$value['id'].'" class="form-check-label d-flex justify-content-center align-items-center w-100 h-100" style="cursor: pointer;">
                                        <input type="checkbox" id="checkBoxId_'.$value['id'].'" class="form-check-input checkBox" name="selectRow[]" value="'.$value['id'].'" style="cursor: pointer;">
                                    </label>',
                    'first_name' => $value['first_name'],
                    'last_name'  => $value['last_name'],
                    'email'      => $value['email'],
                    'number'     => $value['number'],
                ];

                // $html['action'] = '
                //         <div class="d-flex justify-content-evenly align-items-center">
                //             <button class="btn btn-warning editContact" userid="'.$value['id'].'">Edit</button>
                //             <button class="btn btn-danger deleteContact" userid="'.$value['id'].'">Delete</button>
                //             <select class="btn btn-secondary" name="groupDropdown" groupid="'.$value['group_id'].'" style="width: 115px;">
                //                 <option class="bg-white text-left" disabled selected>';

                //                 $html['action'] .= array_map(function($val){
                //                     echo '<pre>';print_r($value);exit;
                //                     if($value['group_id'] === $val['id']){
                //                         return $val['name'];
                //                     }
                //                 }, $groupList);

                //                 $html['action'] .= '</option>';
                //                     foreach ($groupList as $group) {
                //                         $html['action'] .= '<option value="'.$group['id'].'" class="bg-white text-left">'.$group['name'].'</option>';
                //                     }
                //                 $html['action'] .= '
                //             </select>
                //         </div>
                // ';

                $html['action'] = '
                    <div class="d-flex justify-content-evenly align-items-center">
                        <button class="btn btn-warning editContact" userid="'.$value['id'].'">Edit</button>
                        <button class="btn btn-danger deleteContact" userid="'.$value['id'].'">Delete</button>
                        <select class="btn btn-secondary selectGroup" name="groupDropdown" numberid="'.$value['id'].'" value="'.$value['group_id'].'" style="text-transform: capitalize;width: 115px; overflow: scroll;">';

                        $selected = false;
                        foreach ($groupList as $group) {

                            $displayName = strlen($group['name']) > 7 ? substr($group['name'], 0, 7) . '...' : $group['name'];

                            if(($group['id'] === $value['group_id'])){
                                $selected = true;
                                $html['action'] .= '<option value="'.$group['id'].'" class="bg-white text-left" selected>'.$displayName.'</option>';
                            }else{
                                $html['action'] .= '<option value="'.$group['id'].'" class="bg-white text-left">'.$displayName.'</option>';
                            }
                        }

                        if($selected === false){
                            $html['action'] .= '<option class="bg-white text-left d-none" disabled selected>Move To</option>';
                        }

                        $html['action'] .= '
                        </select>
                    </div>
                ';

                $data['data'][] = $html;
            }

            $data["draw"] = (int) $draw;
            $data["recordsTotal"] = count($data['data']);
            $data["recordsFiltered"] = (int) $this->ProjectModel->countData('usernumbers', 'id', $where = ['rel_id' => $this->session->userdata('id')]);

        }else{
            $data = [
                "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ];
        }

        echo json_encode($data, true);
    }

    public function deleteContact(){

        $post = $this->input->post();
        $id = ($post['userId'] > 0) ? (int) $post['userId'] : 0;

        if($id == 0){
            $responseArray = ['status' => 0, 'message' => 'l_user_id_is_required'];
            echo json_encode($responseArray, true);
        }else{

            $deleteContact = $this->ProjectModel->deleteData('usernumbers', ['id' => $id]);

            if($deleteContact){

                $responseArray = [
                    'status' => 1,
                    'message' => 'Contant delete successfully.'
                ];
    
                echo json_encode($responseArray, true);
            }
        }
    }

    public function getContactToEdit(){

        $post = $this->input->post();
        $id = ($post['userId'] > 0) ? (int) $post['userId'] : 0;

        if($id == 0){
            $responseArray = ['status' => 0, 'message' => 'l_user_id_is_required'];
            echo json_encode($responseArray, true);
        }else{

            $data = $this->ProjectModel->selectData(1,'usernumbers','*',['id' => $id]);

            if(!empty($data)){
                $responseArray = ['status' => 1, 'data' => $data, 'message' => 'Data is found'];
            }else{
                $responseArray = ['status' => 0, 'message' => 'Data not found'];
            }

            echo json_encode($responseArray, true);
        }
    }

    public function deleteMultipleContacts(){

        $post = $this->input->post();
        $rowIds = (!empty($post['selectRow'])) ? $post['selectRow'] : array();

        if(empty($rowIds)){
            echo json_encode(['status' => 0, 'message' => 'l_row_id_is_required'], true);
        }else{
            foreach($rowIds as $id){
                $this->ProjectModel->deleteData('usernumbers', ['id' => $id]);
            }
            echo json_encode(['status' => 1, 'message' => 'delete all record successfully'], true);
        }
    }

    public function saveGroup(){

        $this->form_validation->set_rules('groupName', 'First Name', 'required');

        if($this->form_validation->run() == FALSE){
            echo json_encode([
                'status' => 3,
                'data' => [
                        'groupName' => form_error('groupName')
                    ]
            ], true);
        }else{

            $post = $this->input->post();
            $name = $post['groupName'];
            $userId = $this->session->userdata('id');

            $saveGroup = $this->ProjectModel->insertData('contact_groups', [
                'user_id' => $userId,
                'name' => $name
            ]);

            if($saveGroup){

                echo json_encode([
                    'status' => 1,
                    'meesage' => 'Save group successfully.'
                ], true);

            }else{

                echo json_encode([
                    'status' => 0,
                    'meesage' => 'l_something_went_wrong'
                ], true);
            }
        }
    }

    public function groupListing(){

        $saveGroup = $this->ProjectModel->selectData(0, 'contact_groups', '*', ['user_id' => $this->session->userdata('id')]);

        if($saveGroup){
            echo json_encode([
                'status' => 1,
                'data' => $saveGroup,
                'meesage' => 'Save group successfully.'
            ], true);

        }else{

            echo json_encode([
                'status' => 0,
                'meesage' => 'l_something_went_wrong'
            ], true);
        }    
    }

    public function contactMoveToGroup(){

        $post = $this->input->post();
        $numberId = $post['numberId'];
        $groupId = $post['groupId'];

        $update = $this->ProjectModel->updateData('usernumbers', ['id' => $numberId], ['group_id' => $groupId]);

        if($update){
            echo json_encode([
                'status' => 1,
                'message' => 'Save changes successfully.'
            ], true);
        }else{
            echo json_encode([
                'status' => 0,
                'message' => 'l_something_went_wrong'
            ], true);
        }
    }
}

?>