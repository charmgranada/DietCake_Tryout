<?php $title = "Board Exercise in PHP DietCake" ?>
<h1>All threads</h1>
<p align='right' style='font-size:12px;line-height:12px;'>
    Total of 
        <b>
            <?php eh($num_rows); ?>
        </b>
    threads<br/>
    Page 
        <b>
            <?php eh($pagination['cur_page']); ?>
        </b> 
    of 
        <b>
            <?php eh($pagination['last_page']); ?>
        </b>
</p><center>
    <?php foreach ($threads as $v): ?>
        <div class = "alert" style="width:40%;margin:2px;">
        	<a href="<?php eh(url('comment/view', array('thread_id' => $v->id))) ?>">
                <?php eh($v->title) ?>
            </a>
        </div>
    <?php endforeach; 
    echo $pagination["controls"]; ?>
</center>
<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create')) ?>">Create</a>