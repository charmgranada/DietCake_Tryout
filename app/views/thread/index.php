<?php $title = "Board Exercise in PHP DietCake" ?>

<form method='post' action='<?php eh(url('')) ?>'>
    <input type='text' style='float:left;margin-right:10px;' name='search_item' placeholder='Enter your search here' value='<?php eh(Param::get('search_item'))?>'>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php if($num_rows > 0): ?>
    <p align='right' style='font-size:12px;line-height:12px;'>
        Total of 
            <b>
                <?php eh($num_rows); ?>
            </b>
        <?php echo ($num_rows==1) ? "thread" : "threads"; ?><br/>
        Page 
            <b>
                <?php eh($pagination['cur_page']); ?>
            </b> 
        of 
            <b>
                <?php eh($pagination['last_page']); ?>
            </b>
    </p>
<?php endif; ?>

<center>
    <?php foreach ($threads as $v): ?>
        <div class = "alert" style="border:1px dashed #bbb;width:70%;margin:2px;">
        	<a href="<?php eh(url('comment/view', array('thread_id' => $v->thread_id))) ?>">
                <?php eh($v->title) ?>
            </a>

            <?php // THE EDIT AND DELETE CONTROLS FOR THE THREAD, ACCESSED ONLY BY THE USER WHO CREATED IT
            if($v->user_id == $_SESSION['user_id']): ?>
                <p align='right'>
                    <a href="<?php eh(url('thread/edit', array('thread_id' => $v->thread_id)))?>"><button 
                        class="btn btn-success btn-small">Edit Title</button></a>
                    <a href="<?php eh(url('thread/delete', array('thread_id' => $v->thread_id)))?>"><button 
                        class="btn btn-danger btn-small">Delete Thread</button></a>
                </p>
            <?php endif; ?>

            <p align="left" style="font-size:10px;font-style:italic;">
                Posted by: <?php eh($v->username) ?>
                <font style='float:right;'>
                    <?php eh(date_format(new DateTime($v->created),"F d, Y h:ia")) ?>
                </font>
            </p>
        </div>
    <?php endforeach; 
    echo $pagination["controls"]; ?>
<br/>
<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create')) ?>">Create a new thread</a>
<br/><br/>
</center>