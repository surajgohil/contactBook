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

?>