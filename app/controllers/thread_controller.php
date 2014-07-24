<?php
class ThreadController extends AppController
{
    const THREADS_PER_PAGE = 5;
    const USERS_PER_PAGE = 5;

    /**
     *VIEW ALL THREADS OR USERS
     */        
    public function index()
    {
        check_user_logged_out();
        $user = User::get($_SESSION['user_id']);
        $cur_page = Param::get('pn');
        $placeholder = 'Enter a thread title here';
        $search_item = Param::get('search_item');
        // FOR FILTERING RESULTS OF THREADS
        $filter_by = Param::get('filter_by');
        $filter_options = array(
            'All Threads', 
            'My Threads', 
            'Threads I commented', 
            'Other people\'s Threads'
        );
        // IF filter_by IS NOT SET, ALL THREADS IS THE DEFAULT FILTER
        if (!$filter_by) {
            $filter_by = 'All Threads';
        }
        // FOR SEARCHING THREADS OR USERS
        $search_by = Param::get('search_by');
        $search_options = array(
            'Thread', 
            'User'
        );
        $search = null;
        // IF search_by IS NOT SET, THREAD IS THE DEFAULT SEARCH
        if (!$search_by) {
            $search_by = 'Thread';
        }
        // FOR SORTING OF THREADS
        $order_by = Param::get('order_by');
        $order_options = array(
            'Latest First', 
            'Oldest First', 
            'Most Comments', 
            'Least Comments',
            'Most Likes', 
            'Least Likes'
        );
        // IF order_by IS NOT SET, LATEST FIRST IS THE DEFAULT ORDER
        if (!$order_by) {
            $order_by = 'Latest First';
        }
        if (isset($search_item)) {
            $search_item = mysql_real_escape_string($search_item);
            $search = 'title LIKE \'%' .$search_item. '%\'';
            if (!$search_item && $filter_by == 'All Threads' && $search_by == 'Thread' 
                && $order_by == 'Latest First') {
                redirect('thread', 'index');
            }
        }
        $num_rows = Thread::count($search, $filter_by, $user->user_id);
        $pagination = pagination($num_rows, $cur_page, self::THREADS_PER_PAGE);
        // TODO: Get all threads
        $threads = Thread::getAll($pagination['limit'], $search, $filter_by, $order_by, $user->user_id);
        // IF SEARCH OPTION IS USER, ONLY USER INFORMATION IS SEEN
        if ($search_by == 'User') {
            $num_rows = User::countFound($search_item);
            $pagination = pagination($num_rows, $cur_page, self::USERS_PER_PAGE);
            $users_found = User::search($search_item, $pagination['limit']);
            $placeholder = 'Enter a user\'s username here';
        }
        $this->set(get_defined_vars());
    }
    
    /**
     *LIKE OR DISLIKE A THREAD
     */
    public function addLikeDislike()
    {
        check_user_logged_out();
        $thread = Thread::get(Param::get('thread_id'));
        $like_status = Param::get('like_status');
        $home_page = Param::get('home_page');
        $thread->user_id = $_SESSION['user_id'];
        $thread->addLikeDislike($like_status); 
        redirect($home_page, null);
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
        if (isset($title) || isset($description)) {
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
        $thread = Thread::get(Param::get('thread_id'));
        $old_title = $thread->title;
        $new_title = Param::get('title');
        $old_description = $thread->description;
        $new_description = Param::get('description');
        if (isset($new_title) || isset($new_description)) {
            try {
                $thread->title = $new_title;
                $thread->user_id = $_SESSION['user_id'];
                $thread->description = $new_description;
                $thread->update();
                redirect('comment', 'view', array('thread_id' => $thread->thread_id));
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
