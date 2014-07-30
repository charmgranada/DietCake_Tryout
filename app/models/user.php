<?php    
class User extends AppModel
{
    const PASS_MIN_LENGTH = 8;
    const PASS_MAX_LENGTH = 16;
    
    public $validation = array(
        'username' => array(
            'length' => array(
                'validate_between', MIN_LENGTH, MAX_LENGTH
            ),
            'format' => array(
                'is_valid_username', 'Username is invalid'
            )
        ),
        'password' => array(
            'length' => array(
                'validate_between', self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH
            ), 
            'format' => array(
                'is_valid_num_spaces', 'Password has invalid number of spaces'
            )
        ),
    );

    /**
     *LOGIN A REGISTERED USER
     *@throws ValidationException
     *@throws RecordNotFoundException
     */
    public function authenticate()
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid Username/Password');
        }
        $db = DB::conn();
        $row = $db->row('SELECT * FROM users WHERE username = ? AND password = ?', 
            array($this->username, sha1($this->password)));
        if (!$row) {
            throw new RecordNotFoundException('Username/Password is incorrect');
        }        
        return new self ($row);
    }

    /**
     *SEARCH FOR A USER WITH THE GIVEN USERNAME
     *@param $username, $limit
     */
    public static function search($username, $limit)
    {
        $username = mysql_real_escape_string($username);
        $users_found = array();
        $db = DB::conn();
        $users = $db->search('users', 'username LIKE ?', array("%{$username}%"), 'username', $limit); 
        foreach ($users as $user) {
            $users_found[] = new self($user);
        }
        return $users_found;
    }

    /**
     *GET NUMBER OF USERS FOUND
     *@param $username
     */
    public static function countFound($username)
    {
        $db = DB::conn();
        $count = $db->value('SELECT COUNT(*) FROM users WHERE username LIKE ?', array("%{$username}%")); 
        return $count;
    }

    /**
     *RETURNS ALL INFO OF A USER
     *@param $id
     */
    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM users WHERE user_id = ?', array($id)); 
        return new self ($row);
    }

    /**
     *REGISTERS A NEW USER
     *@param PersonalInfo $personal_info
     *@throws ValidationException
     *@throws ExistingUserException
     */
    public function register(PersonalInfo $personal_info)
    {
        $db = DB::conn();
        $row = $db->search('users', 'username = ? OR email_add = ?', 
            array($this->username, $personal_info->email_add));
        if (!$this->validate() || !$personal_info->validate()) {
            throw new ValidationException(notice('Validation Error', 'error'));
        }
        if ($row) {
            throw new ExistingUserException(notice('Username/Email Address has already been used', 'error'));
        }
        $params = array(
            'username' => $this->username,
            'password' => sha1($this->password),
            'firstname' => $personal_info->firstname,
            'lastname' => $personal_info->lastname,
            'email_add' => $personal_info->email_add
        );
        $db->insert('users', $params);
    }

    /**
     *UPDATES A USER'S ACCOUNT INFO
     *@param PersonalInfo $personal_info
     *@throws ValidationException
     *@throws ExistingUserException
     */
    public function update(PersonalInfo $personal_info)
    {
        $db = DB::conn();
        $row = $db->search('users', '(username = ? OR email_add = ?) AND user_id != ?', 
            array($this->username, $personal_info->email_add, $this->user_id));
        if (!$this->validate() || !$personal_info->validate()) {
            throw new ValidationException(notice('Validation Error', 'error'));
        }
        if ($row) {
            throw new ExistingUserException(notice('Username/Email Address has already been used', 'error'));
        }
        $set_params = array(
            'username' => $this->username,
            'password' => sha1($this->password),
            'firstname' => $personal_info->firstname,
            'lastname' => $personal_info->lastname,
            'email_add' => $personal_info->email_add
        );
        $db->update('users', $set_params, array('user_id' => $this->user_id));
    }
}
