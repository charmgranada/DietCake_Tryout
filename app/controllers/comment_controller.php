<?php
class CommentController extends AppController
{
    const COMMENTS_PER_PAGE = 5;

    /**
     *VIEW ALL COMMENTS OF A THREAD
     *@throws ValidationException
     */
    public function view()
    {       
        check_user_logged_out();     
        $user_id = $_SESSION['user_id'];
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment();
        $comment->thread_id = $thread->thread_id;
        $body = Param::get('body');
        // FOR FILTERING RESULTS OF COMMENTS
        $filter_by = Param::get('filter_by', 'All Comments');
        $filter_options = array(
            'All Comments', 
            'My Comments', 
            'Other people\'s Comments'
        );
        // FOR PAGINATION //
        $cur_page = Param::get('pn');
        $num_rows = $comment->count($filter_by, $user_id);
        $pagination = pagination($num_rows, $cur_page, self::COMMENTS_PER_PAGE, 
            array('thread_id' => $thread->thread_id));
        // PASS LIMIT TO THE COMMENT QUERY //
        $all_comments = $comment->getAll($pagination['limit'], $filter_by, $user_id);
        if (isset($body)) {
            try {
                $comment->user_id = $user_id;
                $comment->body = $body;
                $comment->create();
                redirect('comment', 'view', array('thread_id' => $thread->thread_id));
            } catch (ValidationException $e) {

            }
        }
        $this->set(get_defined_vars());
    }

    /**
     *EDIT A COMMENT
     *@throws ValidationException
     */
    public function edit()
    {       
        check_user_logged_out();   
        $thread = Thread::get(Param::get('thread_id'));   
        $comment = Comment::get(Param::get('comment_id'));
        $body = Param::get('body');
        if (isset($body)) {
            try {
                $comment->body = $body;
                $comment->update();
                redirect('comment', 'view', array('thread_id' => $thread->thread_id));
            } catch (ValidationException $e) {

            }
        }
        $this->set(get_defined_vars());
    }
    
    /**
     *DELETE A COMMENT
     */
    public function delete()
    {
        check_user_logged_out();
        $thread = Thread::get(Param::get('thread_id'));
        $comment = Comment::get(Param::get('comment_id'));
        $comment->thread_id = $thread->thread_id;
        $comment->delete(); 
        redirect('comment', 'view', array('thread_id' => $thread->thread_id));
        $this->set(get_defined_vars());
    }
}