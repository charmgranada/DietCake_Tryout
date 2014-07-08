<?php
    function getPageLimit($totalCount,$pn){
        $pagination = array();
        $last_page = ceil($totalCount/ROWS_PER_PAGE);
        if($last_page < 1){
            $last_page = 1;
        }
        // INITIALIZATION OF THE CURRENT PAGE //
        $cur_page = 1;
        if(!is_null($pn)){
            $cur_page = preg_replace('/[^0-9]/', '', $pn);
        }
        if($cur_page < 1){
            $cur_page = 1;
        }else if($cur_page > $last_page){
            $cur_page = $last_page;
        }
        // SETS THE LIMIT PER PAGE //    
        $limitPage = ($cur_page - 1) * ROWS_PER_PAGE;
        $limit = "LIMIT " . $limitPage . ',' . ROWS_PER_PAGE;
        $pagination['last_page'] = $last_page;
        $pagination['cur_page'] = $cur_page;
        $pagination['limit'] = $limit;
        return $pagination; 
    }
