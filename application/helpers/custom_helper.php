<?php

function renderUserContact($offset = 0, $length = 10, $likeValue = ''){

    $CI =& get_instance();

    $userNumbersList = $CI->ProjectModel->selectData(
        0,
        'usernumbers',
        '*',
        ['rel_id' => $CI->session->userdata('id')],
        ['id', 'DESC'],
        $length,
        $offset,
        $likeValue
    );

    return $userNumbersList;
}

?>