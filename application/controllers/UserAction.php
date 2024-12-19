<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserAction extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index() {

        $userId = $this->session->userdata('id');

        if($userId > 0){
            $this->load->view('header');
            $this->load->view('dashboard');
            $this->load->view('footer');
        }else{
            $this->load->view('signUp');
        }
    }

    public function signUp() {

        $this->form_validation->set_rules('firstName', 'First Name', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('lastName', 'Last Name', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('number', 'Mobile Number', 'required|min_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {

            $responseArray = [
                'status' => 3,
                'data' => [
                    'firstName' => form_error('firstName'),
                    'lastName' => form_error('lastName'),
                    'number' => form_error('number'),
                    'email' => form_error('email'),
                    'password' => form_error('password'),
                    'confirmPassword' => form_error('confirmPassword'),
                ]
            ];

            echo json_encode($responseArray, true);

        } else {

            $post            = $this->input->post();
            $firstName       = $post['firstName'];
            $lastName        = $post['lastName'];
            $email           = $post['email'];
            $number          = $post['number'];
            $password        = $post['password'];
            $confirmPassword = $post['confirmPassword'];

            $data = [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email,
                'number'     => $number,
                'password'   => $password
            ];

            $this->ProjectModel->insertData('userdetails', $data);
            $insertId = $this->db->insert_id();

            if($insertId > 0){

                $userData = $this->ProjectModel->selectData(
                    1,
                    'userDetails',
                    'id, first_name, last_name, email, number',
                    ['id' => $insertId]
                );

                $this->session->set_userdata($userData);

                // redirect('Dashboard/index');
                echo json_encode(['status' => 1, 'message' => 'User registered successfully!']);   
            }
        }
    }

}
