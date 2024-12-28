<?php

function renderUserContact($offset = 0, $length = 10, $likeValue = '', $orderBy = [], $groupId){

    $CI =& get_instance();

    $where = ['usernumbers.rel_id' => $CI->session->userdata('id')];
    if($groupId > 0){
        $where = [
            'usernumbers.rel_id' => $CI->session->userdata('id'),
            'usernumbers.group_id' => $groupId
        ];
    }

    $userNumbersList = $CI->ProjectModel->selectData(
        0,
        'usernumbers',
        '
            usernumbers.id,
            usernumbers.first_name,
            usernumbers.last_name,
            usernumbers.email,
            usernumbers.number,
            usernumbers.rel_id,
            usernumbers.group_id,
            usernumbers.create_at,
            usernumbers.update_at,
            contact_groups.user_id,
            contact_groups.name,
            contact_groups.create_at
        ',
        $where,
        $orderBy,
        $length,
        $offset,
        $likeValue,
        [
            'tableName' => 'contact_groups',
            'on' => 'contact_groups.user_id = usernumbers.id',
            'type' => 'left'
        ]
    );

    return $userNumbersList;
}


function upload_and_resize_image($field_name, $upload_path, $resize_config = []){

    $CI =& get_instance();
    $CI->load->library('upload');
    $CI->load->library('image_lib');

    $upload_config = [
        'upload_path'   => $upload_path,
        'allowed_types' => 'jpg|jpeg|png|gif',
        // 'max_size'      => 2048,
    ];

    $CI->upload->initialize($upload_config);

    if (!$CI->upload->do_upload($field_name)) {

        return [
            'success' => false,
            'error' => $CI->upload->display_errors()
        ];
    }

    $upload_data = $CI->upload->data();

    if (!empty($resize_config)) {

        $resize_defaults = [
            'image_library'  => 'gd2',
            'source_image'   => $upload_data['full_path'],
            'maintain_ratio' => TRUE,
            'width'          => 200,
            'height'         => 200,
        ];

        $resize_config = array_merge($resize_defaults, $resize_config);

        $CI->image_lib->initialize($resize_config);

        if (!$CI->image_lib->resize()) {

            return [
                'success' => false,
                'error' => $CI->image_lib->display_errors()
            ];
        }
    }


    return [
        'success' => true,
        'file_data' => $upload_data
    ];
}


?>