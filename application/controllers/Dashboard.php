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

    public function saveContact(){

        $this->form_validation->set_rules('firstName', 'First Name', 'required|alpha');
        $this->form_validation->set_rules('lastName', 'Last Name', 'required|alpha');
        $this->form_validation->set_rules('email', 'First Name', 'required|valid_email');
        $this->form_validation->set_rules('number', 'First Name', 'required|min_length[10]');

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

            $saveContact = $this->ProjectModel->insertData('usernumbers', [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email,
                'number'     => $number,
                'rel_id'     => $this->session->userdata('id'),
            ]);

            if(!empty($saveContact)){
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
            $userListing = renderUserContact($start, $length, $searchValue, ['id', $orderDir]);
        }

        if(!empty($userListing)){

            foreach ($userListing as $key => $value) {

                $data['data'][] = [
                    'DT_RowId'   => 'userId_'.$value['id'],
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

}

?>