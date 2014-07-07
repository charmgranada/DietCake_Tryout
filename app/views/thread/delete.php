<h2>
    <?php
        $title = "Successfully deleted '".$thread_title."' thread";
        eh($thread_title); 
    ?>
</h2>
<p class="alert alert-success">
    You successfully deleted this thread.
</p>
<a href="<?php eh(url('thread/index')) ?>">
    &larr; Back to All Threads
</a>