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
            // $this->load->view('login');
            $this->load->view('signUp');
        }
    }

    public function redirectToLogin(){
        $this->load->view('login');
    }
    
    public function redirectToSignUp(){
        $this->load->view('signUp');
    }

    public function redirectToLogOut(){
        $this->session->sess_destroy();
        echo json_encode(['status' => 1], true);
    }

    public function signUp() {

        $this->form_validation->set_rules('firstName', 'First Name', 'required|alpha_numeric_spaces|max_length[20]');
        $this->form_validation->set_rules('lastName', 'Last Name', 'required|alpha_numeric_spaces|max_length[20]');
        $this->form_validation->set_rules('number', 'Mobile Number', 'required|min_length[10]|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[10]');
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
                'password'   => password_hash($password, PASSWORD_BCRYPT)
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

                // $this->session->set_userdata($userData);

                // redirect('Dashboard/index');
                echo json_encode(['status' => 1, 'message' => 'User registered successfully!']);   
            }
        }
    }

    public function login(){


        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[10]');

        if($this->form_validation->run() === FALSE){

            $responseArray = [
                'status' => 3,
                'data' => [
                    'email' => form_error('email'),
                    'password' => form_error('password'),
                ]
            ];

            echo json_encode($responseArray, true);

        }else{

            $post = $this->input->post();
            $email = $post['email'];
            $password = $post['password'];

            $userData = $this->ProjectModel->selectData(1,'userDetails','*',['email' => $email]);

            if(!empty($userData)){

                if(password_verify($password, $userData['password'])){

                    $this->session->set_userdata([
                        'id'         => $userData['id'],
                        'first_name' => $userData['first_name'],
                        'last_name'  => $userData['last_name'],
                        'email'      => $userData['email'],
                        'number'     => $userData['number'],
                    ]);

                    $responseArray = [
                        'status' => 1,
                        'message' => 'Login Successfully.'
                    ];

                    echo json_encode($responseArray, true);

                }else{

                    $responseArray = [
                        'status' => 3,
                        'data' => [
                            'password' => 'Please enter valid password.'
                        ],
                    ];

                    echo json_encode($responseArray, true);
                }

            }else{

                $responseArray = [
                    'status' => 3,
                    'data' => [
                        'email' => 'Please enter valid email.'
                    ],
                ];

                echo json_encode($responseArray, true);
            }
        }
    }

}
