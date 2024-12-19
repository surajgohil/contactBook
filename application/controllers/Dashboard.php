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
            $this->load->view('signUp');
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
        $data        = [];

        $userListing = renderUserContact($start, $length, $searchValue);

        if(!empty($userListing)){

            foreach ($userListing as $key => $value) {

                $data['data'][] = [
                    'DT_RowId'   => 'userId_'.$value['id'],
                    'First Name' => $value['first_name'],
                    'Last Name'  => $value['last_name'],
                    'Email'      => $value['email'],
                    'Number'     => $value['number'],
                    'Action'     => '
                        <button class="btn btn-warning editContact" userid="'.$value['id'].'">Edit</button>
                        <button class="btn btn-danger deleteContact" userid="'.$value['id'].'">Delete</button>
                    ',
                ];
            }

            $data["draw"] = (int) $draw;
            $data["recordsTotal"] = count($data['data']);
            $data["recordsFiltered"] = (int) $this->ProjectModel->countData('usernumbers', 'id', $where = ['rel_id' => $this->session->userdata('id')]);
        }

        echo json_encode($data, true);
    }

}

?>