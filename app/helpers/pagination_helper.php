<?php

function getPageLimit($totalCount,$rows_seen,$pn){
    $pagination = array();
    $last_page = (int) ($totalCount/$rows_seen);
    if($last_page < 1){
        $last_page = 1;
    }
    $cur_page = 1;
    if(!is_null($pn)){
        $cur_page = preg_replace('/[^0-9]/', '', $pn);
    }
    if($cur_page < 1){
        $cur_page = 1;
    }else if($cur_page > $last_page){
        $cur_page = $last_page;
    }    
        $limitPage = ($cur_page - 1) * $rows_seen;
        $limit = "LIMIT " . $limitPage . ',' . $rows_seen;
    $pagination['last_page'] = $last_page;
    $pagination['cur_page'] = $cur_page;
    $pagination['limit'] = $limit;
    return $pagination; 
}

?>