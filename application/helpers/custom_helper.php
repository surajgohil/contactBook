<?php

function renderUserContact($offset = 0, $length = 10, $likeValue = '', $orderBy = []){

    $CI =& get_instance();

    $userNumbersList = $CI->ProjectModel->selectData(
        0,
        'usernumbers',
        '*',
        ['rel_id' => $CI->session->userdata('id')],
        $orderBy,
        $length,
        $offset,
        $likeValue
    );

    return $userNumbersList;
}

?>