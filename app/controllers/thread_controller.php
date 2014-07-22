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
        $user = User::get($_SESSION['user_id']);
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
            $search = "title LIKE '%".mysql_real_escape_string($search_item)."%'";
            if (!$search_item && $filter_by == 'All Threads') {
                redirect('thread', 'index');
            }
        }
        $num_rows = Thread::getNumRows($search, $filter_by, $user->user_id);
        $pagination = pagination($num_rows, $cur_page, self::THREADS_PER_PAGE);
        // TODO: Get all threads
        $threads = Thread::getAll($pagination['limit'], $search, $filter_by, $user->user_id);
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
        $thread_title = Param::get('title');
        $description = Param::get('description');
        if(isset($title) || isset($description)) {
            try {
                $thread->title = $thread_title;
                $thread->user_id = $_SESSION['user_id'];
                $thread->description = $description;
                $new_thread_id = $thread->create();
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
        $thread = Thread::get($thread_id);
        $thread_title = Param::get('title');
        $description = Param::get('description');
        if(isset($title) || isset($description)){
            try {
                $thread->title = $thread_title;
                $thread->user_id = $_SESSION['user_id'];
                $thread->description = $description;
                $thread->update();
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
        $thread->delete(); 
        redirect('thread', 'index');
        $this->set(get_defined_vars());
    }
}
