<?php
function pagination($total_rows, $pn, $rows_per_page, array $url_query = null) 
{
    $pagination = array();
    $last_page = ceil($total_rows/$rows_per_page);
    if ($last_page < 1) {
        $last_page = 1;
    }
    // INITIALIZATION OF THE CURRENT PAGE //
    $cur_page = 1;
    if (!is_null($pn)) {
        $cur_page = preg_replace('/[^0-9]/', '', $pn);
    }
    if ($cur_page < 1) {
        $cur_page = 1;
    } else if ($cur_page > $last_page) {
        $cur_page = $last_page;
    }
    // SETS THE LIMIT PER PAGE //    
    $limit_page = ($cur_page - 1) * $rows_per_page;
    $limit = $limit_page. ',' .$rows_per_page;
    // PAGINATION CONTROLS WILL ONLY APPEAR IF THERE ARE MORE THAN 1 PAGES //   
    $controls = '';
    $page_link =& $url_query['pn'];
    if ($last_page != 1) {
        // THESE ARE THE PAGINATION CONTROLS SHOWN ON THE LEFT OF THE CURRENT PAGE //
        if ($cur_page > 1) {            
            $page_link = $cur_page - 1;
            $controls .= "<a href='" .url('', $url_query). "'>Previous </a> &nbsp; &nbsp;";
            for ($i = $cur_page - 4; $i < $cur_page; $i++) {
                if ($i > 0) { 
                    $page_link = $i;
                    $controls .= "<a href='" .url('', $url_query). "'>$i</a> &nbsp; ";
                }
            }
        }
        // ECHO THE CURRENT PAGE SINCE IT DOESN'T NEED A LINK ANYMORE 
        $controls .=  "" . $cur_page . "&nbsp ";
        // THESE ARE THE PAGINATION CONTROLS SHOWN ON THE RIGHT OF THE CURRENT PAGE //        
        for ($i = $cur_page + 1; $i <= $last_page; $i++) { 
            $page_link = $i;
            $controls .= "<a href='" .url('', $url_query). "'>$i</a> &nbsp; ";
            if ($i >= $cur_page + 4) {
                break;
            }
        }
        if ($cur_page != $last_page) {
            $page_link = $cur_page + 1;
            $controls .= " &nbsp; &nbsp;<a href='" .url('', $url_query). "'> Next </a> ";
        }
    }

    $pagination['last_page'] = $last_page;
    $pagination['cur_page'] = $cur_page;
    $pagination['limit'] = $limit;
    $pagination['controls'] = $controls;
    return $pagination; 
}
