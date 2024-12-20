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
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
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
            $id        = ($post['id'] > 0) ? $post['id'] : 0;

            if($id == 0){

                $saveChanges = $this->ProjectModel->insertData('usernumbers', [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'number'     => $number,
                    'rel_id'     => $this->session->userdata('id'),
                ]);

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
        $data        = [];

        if(!empty($orderColumn) || !empty($orderDir)){
            $userListing = renderUserContact($start, $length, $searchValue, [$orderColumn, $orderDir]);
        }else{
            echo '<pre>';print_r($orderDir);exit;
            $userListing = renderUserContact($start, $length, $searchValue, ['id', $orderDir]);
        }

        if(!empty($userListing)){

            foreach ($userListing as $key => $value) {

                $data['data'][] = [
                    // 'DT_RowId'   => 'userId_'.$value['id'],
                    'id'         => '<label for="checkBoxId_'.$value['id'].'" class="form-check-label d-flex justify-content-center align-items-center w-100 h-100" style="cursor: pointer;">
                                        <input type="checkbox" id="checkBoxId_'.$value['id'].'" class="form-check-input checkBox" name="selectRow[]" value="'.$value['id'].'" style="cursor: pointer;">
                                    </label>',
                    'first_name' => $value['first_name'],
                    'last_name'  => $value['last_name'],
                    'email'      => $value['email'],
                    'number'     => $value['number'],
                    'action'     => '
                        <div class="d-flex justify-content-evenly align-items-center">
                            <button class="btn btn-warning editContact" userid="'.$value['id'].'">Edit</button>
                            <button class="btn btn-danger deleteContact" userid="'.$value['id'].'">Delete</button>
                        </div>
                    ',
                ];
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

    // public function editContactData(){

    //     $this->form_validation->set_rules('firstName', 'First Name', 'required|alpha');
    //     $this->form_validation->set_rules('lastName', 'Last Name', 'required|alpha');
    //     $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    //     $this->form_validation->set_rules('number', 'Number', 'required|min_length[10]');

    //     if($this->form_validation->run() == FALSE){

    //         $responseArray = [
    //             'status' => 3,
    //             'data' => [
    //                 'firstName' => form_error('firstName'),
    //                 'lastName'  => form_error('lastName'),
    //                 'email'     => form_error('email'),
    //                 'number'    => form_error('number'),
    //             ]
    //         ];

    //         echo json_encode($responseArray, true);

    //     }else{

    //         $post      = $this->input->post();
    //         $firstName = $post['firstName'];
    //         $lastName  = $post['lastName'];
    //         $email     = $post['email'];
    //         $number    = $post['number'];
    //         $id        = $post['id'];

    //         $saveChanges = $this->ProjectModel->updateData('usernumbers', ['id' => $id], [
    //             'first_name' => $firstName,
    //             'last_name'  => $lastName,
    //             'email'      => $email,
    //             'number'     => $number,
    //             'rel_id'     => $this->session->userdata('id'),
    //         ]);

    //         if(!empty($saveChanges)){
    //             echo json_encode(['status' => 1, 'message' => 'Data is save succefully.'], true);
    //         }else{
    //             echo json_encode(['status' => 0, 'message' => 'Data is not save.'], true);
    //         }
    //     }
    // }

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
}

?>