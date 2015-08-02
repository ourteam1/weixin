<?php

class DB_PDO
{

    public $dbh           = null;
    public $host          = '';
    public $user          = '';
    public $password      = '';
    public $table_prefix  = '';
    public $bind_param    = array();
    public $where_data    = '';
    public $select_fields = '';
    public $group_by      = '';
    public $order_by      = '';
    public $limit         = '';
    public $options       = array();

    public function __construct($host = '127.0.0.1', $user = 'root', $password = '')
    {
        $this->set_host($host);
        $this->set_user($user);
        $this->set_password($password);
    }

    /**
     * 设置host
     */
    public function set_host($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * 设置user
     */
    public function set_user($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * 设置password
     */
    public function set_password($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * 设置table prefix
     */
    public function set_table_prefix($prefix)
    {
        $this->table_prefix = $prefix;
        return $this;
    }

    /**
     * 设置options
     */
    public function set_options($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * 连接
     */
    public function connect()
    {
        $this->dbh = new PDO($this->host, $this->user, $this->password, $this->options);
        return $this;
    }

    /**
     * 获取table全名
     */
    public function get_table($table)
    {
        return '`' . $this->table_prefix . $table . '`';
    }

    /**
     * 重置初始化初级
     */
    public function reset_init()
    {
        $this->bind_param    = array();
        $this->where_data    = '';
        $this->select_fields = '';
        $this->group_by      = '';
        $this->order_by      = '';
        $this->limit         = '';
        return $this;
    }

    /**
     * 添加数据
     * $this->db->insert("test", array("name"=>"noname1"));
     */
    public function insert($table, $data)
    {
        if (!$this->dbh) {
            $this->connect();
        }

        if (!$data) {
            return false;
        }

        $fields = $values = array();
        foreach ($data as $k => $v) {
            $fields[]           = "`$k`";
            $values[]           = ":data_$k";
            $this->bind_param[] = array('key' => ":data_$k", 'value' => $v);
        }
        $sql = "insert into " . $this->get_table($table) . "(" . implode(',', $fields) . ") values(" . implode(',', $values) . ")";
        $sth = $this->dbh->prepare($sql);
        if (!$sth) {
            return false;
        }

        foreach ($this->bind_param as $i) {
            $sth->bindValue($i['key'], $i['value']);
        }
        $result = $sth->execute();
        $this->reset_init();
        return $result;
    }

    /**
     * 修改数据
     * $this->db->update("test", array("name"=>"noname1"));
     */
    public function update($table, $data)
    {
        if (!$this->dbh) {
            $this->connect();
        }

        if (!$data) {
            return false;
        }

        $value = array();
        foreach ($data as $k => $v) {
            $value[]            = "`$k`=:data_$k";
            $this->bind_param[] = array('key' => ":data_$k", 'value' => $v);
        }
        $where = trim($this->where_data) != '' ? ' where ' . $this->where_data : '';
        $sql   = "update " . $this->get_table($table) . ' set ' . implode(',', $value) . $where;
        $sth   = $this->dbh->prepare($sql);
        if (!$sth) {
            return false;
        }

        foreach ($this->bind_param as $i) {
            $sth->bindValue($i['key'], $i['value']);
        }
        $result = $sth->execute();
        $this->reset_init();
        return $result;
    }

    /**
     * 删除数据
     * $this->db->delete("test");
     */
    public function delete($table)
    {
        if (!$this->dbh) {
            $this->connect();
        }

        $where = trim($this->where_data) != '' ? ' where ' . $this->where_data : '';
        $sql   = "delete from " . $this->get_table($table) . $where;
        $sth   = $this->dbh->prepare($sql);
        if (!$sth) {
            return false;
        }

        foreach ($this->bind_param as $i) {
            $sth->bindValue($i['key'], $i['value']);
        }
        $result = $sth->execute();
        $this->reset_init();
        return $result;
    }

    /**
     * 执行查询
     * $this->query("select * from test");
     */
    public function query($sql)
    {
        if (!$this->dbh) {
            $this->connect();
        }

        $sth = $this->dbh->prepare($sql);
        if (!$sth) {
            return false;
        }

        foreach ($this->bind_param as $i) {
            $sth->bindValue($i['key'], $i['value']);
        }
        $sth->execute();
        $this->reset_init();
        return $sth;
    }

    /**
     * 查询一行
     * $this->db->row("test");
     */
    public function row($table, $fetch_style = PDO::FETCH_ASSOC)
    {
        $where         = trim($this->where_data) != '' ? ' where ' . $this->where_data : '';
        $select_fields = trim($this->select_fields) != '' ? $this->select_fields : '*';
        $order_by      = trim($this->order_by) != '' ? ' order by ' . $this->order_by : '';
        $group_by      = trim($this->group_by) != '' ? ' GROUP BY ' . $this->group_by : '';
        $sql           = "select " . $select_fields . " from " . $this->get_table($table) . $where . $group_by . $order_by;
        $sth           = $this->query($sql);
        if ($sth === false) {
            return false;
        }

        $result = $sth->fetch($fetch_style);
        return $result;
    }

    /**
     * 查询一行
     * $this->db->column("test", "id");
     */
    public function column($table, $fields = "")
    {
        $where = trim($this->where_data) != '' ? ' where ' . $this->where_data : '';
        $this->select_fields .= " $fields ";
        $order_by = trim($this->order_by) != '' ? ' order by ' . $this->order_by : '';
        $group_by = trim($this->group_by) != '' ? ' GROUP BY ' . $this->group_by : '';
        $sql      = "select " . $this->select_fields . " from " . $this->get_table($table) . $where . $group_by . $order_by;
        $sth      = $this->query($sql);
        if ($sth === false) {
            return false;
        }

        $result = $sth->fetchColumn();
        return $result;
    }

    /**
     * 查询多行
     * $this->db->result("test");
     */
    public function result($table, $fetch_style = PDO::FETCH_ASSOC)
    {
        $where         = trim($this->where_data) != '' ? ' where ' . $this->where_data : '';
        $limit         = trim($this->limit) != '' ? ' LIMIT ' . $this->limit : '';
        $select_fields = trim($this->select_fields) != '' ? $this->select_fields : '*';
        $order_by      = trim($this->order_by) != '' ? ' order by ' . $this->order_by : '';
        $group_by      = trim($this->group_by) != '' ? ' GROUP BY ' . $this->group_by : '';
        $sql           = "select " . $select_fields . " from " . $this->get_table($table) . $where . $group_by . $order_by . $limit;
        $sth           = $this->query($sql);
        if ($sth === false) {
            return false;
        }

        $result = $sth->fetchAll($fetch_style);
        return $result;
    }

    /**
     * 获取最后插入的ID
     */
    public function last_insert_id()
    {
        if (!$this->dbh) {
            $this->connect();
        }

        return $this->dbh->lastInsertId();
    }

    /**
     * 查询多行
     * $this->db->num_rows("test");
     */
    public function num_rows($table)
    {
        $result = $this->column($table, 'count(*)');
        return $result ? $result : 0;
    }

    /**
     * 查询字段
     * $this->db->select("id, name");
     */
    public function select($field = '')
    {
        $fields = array();
        $arr    = explode(',', $field);
        foreach ($arr as $i) {
            $i = trim($i);
            if (preg_match("/(.*)\sas\s(.*)/", $i, $m)) {
                $fields[] = "`" . trim($m[1]) . "` as " . trim($m[2]) . "";
            } elseif (preg_match("/(.*)\s(.*)/", $i, $m)) {
                $fields[] = "`" . trim($m[1]) . "` as " . trim($m[2]) . "";
            } else {
                $fields[] = "`" . trim($i) . "`";
            }
        }
        $this->select_fields .= " " . implode(',', $fields) . " ";
        return $this;
    }

    /**
     * 分组查询
     * $this->db->group_by("id");
     */
    public function group_by($field)
    {
        $this->group_by .= " `" . trim($field) . "` ";
        return $this;
    }

    /**
     * 排序查询
     * $this->db->order_by("id", "asc");
     */
    public function order_by($field, $order = "desc")
    {
        $this->order_by .= " `" . trim($field) . "` $order ";
        return $this;
    }

    /**
     * 分页查询
     * $this->db->limit(0, 10);
     */
    public function limit($offset = 0, $limit = 10)
    {
        $this->limit .= " $offset,$limit ";
        return $this;
    }

    /**
     * 去重查询字段
     * $this->db->distinct("id");
     */
    public function distinct($field)
    {
        $this->select_fields .= " distinct(`" . trim($field) . "`) ";
        return $this;
    }

    /**
     * 拼接条件
     * $this->db->where("name", "noname1");
     * $this->db->where("name", "!=", "noname1");
     * $this->db->where("or", "name", "!=", "noname1");
     */
    public function where($and_or = 'and', $name = '', $condition = null, $value = null)
    {
        if (trim($and_or) == '' && $name == '') {
            return false;
        }

        // 如果是：$this->db->where("name", "noname1");
        if ($condition === null) {
            $value     = $name;
            $name      = $and_or;
            $condition = '=';
            $and_or    = 'and';
        } else if ($value === null) {
            $value     = $condition;
            $condition = $name;
            $name      = $and_or;
            $and_or    = 'and';
        }

        if (trim($this->where_data) != '') {
            $this->where_data .= $and_or;
        }
        $this->where_data .= " `$name` $condition ";

        if (trim($condition) == 'in' || trim($condition) == 'not in') {
            $arr = array();
            foreach ($value as $k => $v) {
                $arr[]              = ":where_{$k}_{$name}";
                $this->bind_param[] = array("key" => ":where_{$k}_{$name}", "value" => $v);
            }
            $this->where_data .= " (" . implode(',', $arr) . ") ";
        } else {
            $this->where_data .= " :where_$name ";
            $this->bind_param[] = array("key" => ":where_$name", "value" => $value);
        }

        return $this;
    }

    /**
     * 启动事务
     * $this->db->trans_start();
     */
    public function trans_start()
    {
        if (!$this->dbh) {
            $this->connect();
        }

        $this->dbh->beginTransaction();
        return $this;
    }

    /**
     * 提交事务
     * $this->db->trans_commit();
     */
    public function trans_commit()
    {
        if (!$this->dbh) {
            $this->connect();
        }

        $this->dbh->commit();
        return $this;
    }

    /**
     * 回滚事务
     * $this->db->trans_rollback();
     */
    public function trans_rollback()
    {
        if (!$this->dbh) {
            $this->connect();
        }

        $this->dbh->rollBack();
        return $this;
    }

}
