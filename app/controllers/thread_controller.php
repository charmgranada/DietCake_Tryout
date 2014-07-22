<?php
class ThreadController extends AppController
{
    const THREADS_PER_PAGE = 5;

    /**
     *VIEW ALL THREADS
     */        
    public function index()
    {
        check_user_logged_out();
        $user_id = $_SESSION['user_id'];
        $cur_page = Param::get('pn');
        $search_item = Param::get('search_item');
        $filter_by = Param::get('filter_by');
        $filter_options = array(
            'All Threads', 
            'My Threads', 
            'Threads I commented', 
            'Other people\'s Threads'
        );
        $search = null;
        if (!$filter_by) {
            $filter_by = 'All Threads';
        }
        if (isset($search_item)) {
            $search = "title LIKE '%{$search_item}%'";
            if (!$search_item && $filter_by == 'All Threads') {
                redirect('thread', 'index');
            }
        }
        $num_rows = Thread::getNumRows($search, $filter_by, $user_id);
        $pagination = pagination($num_rows, $cur_page, self::THREADS_PER_PAGE);
        // TODO: Get all threads
        $threads = Thread::getAll($pagination['limit'], $search, $filter_by, $user_id);
        $this->set(get_defined_vars());
    }

    /**
     *CREATE A NEW THREAD
     *@throws ValidationException
     */
    public function create()
    {
        check_user_logged_out();
        $thread = new Thread;
        $comment = new Comment;
        $title = Param::get('title');
        $body = Param::get('body');
        if(isset($title) || isset($body)) {
            try {
                $thread->title = $title;
                $thread->user_id = $_SESSION['user_id'];
                $comment->user_id = $thread->user_id;
                $comment->body = $body;
                $new_thread_id = $thread->create($comment);
                redirect('comment', 'view', array('thread_id' => $new_thread_id));
            } catch (ValidationException $e) {
                
            }                
        }
        $this->set(get_defined_vars());
    }

    /**
     *EDIT A THREAD
     *@throws ValidationException
     */
    public function edit()
    {            
        check_user_logged_out();
        $thread_id = Param::get('thread_id');
        $title = Param::get('title');
        $body = Param::get('body');
        $thread = Thread::get($thread_id);
        $thread_title = $thread->title;
        $comment = new Comment;
        if(isset($title) || isset($body)){
            try {
                $thread->title = Param::get('title');
                $thread->user_id = $_SESSION['user_id'];
                $comment->user_id = $thread->user_id;
                $comment->body = Param::get('body');
                $thread->update($comment);
                redirect('comment', 'view', array('thread_id' => $thread_id));
            } catch (ValidationException $e) {

            }
        }
        $this->set(get_defined_vars());
    }
    
    /**
     *DELETE A THREAD
     */
    public function delete()
    {
        check_user_logged_out();
        $thread = Thread::get(Param::get('thread_id'));
        $thread_title = $thread->title;
        $thread->delete(); 
        redirect('thread', 'index');
        $this->set(get_defined_vars());
    }
}
