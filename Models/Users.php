<?php
class Users
{

    public static function get($where = [], $order_by = [], $page = 1, $limit = 0)
    {
        $data = null;

        $sql = "SELECT id, name, email, subscribe, message, role, status FROM users";

        // generate sql where condition
        if (count($where) > 0) {
            $where_data = Helper::generateWhere($where);
            $sql .= " WHERE $where_data";
        }

        // set order by in sql
        if (count($order_by) == 2) {
            $sort_col = $order_by[0];
            $sort_by = $order_by[1];
            $sql .= " ORDER BY $sort_col $sort_by";
        }

        // pagination
        if ($limit > 0) {
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $result = DATABASE->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public static function create($data)
    {
        $columns_arr = [];
        $values_arr = [];

        // generate insert sql
        foreach ($data as $key => $value) {
            $columns_arr[] = $key;
            $values_arr[] = "'$value'";
        }

        $columns = join(", ", $columns_arr);
        $values = join(", ", $values_arr);
        $sql = "INSERT INTO users ($columns) VALUES ($values)";

        if (DATABASE->query($sql) === TRUE) {
            return DATABASE->insert_id; // return inserted id
        } else {
            throw new Exception(DATABASE->error);
        }
    }

    public static function first($where)
    {
        $data = null;

        // generate sql where condition
        $where_data = Helper::generateWhere($where);

        $sql = "SELECT id, name, email, subscribe, message, role, status FROM users WHERE $where_data";
        $result = DATABASE->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }

        return $data;
    }

    public static function update($data, $where)
    {

        // generate sql where condition
        $where_data = Helper::generateWhere($where);

        $set_data_arr = [];
        foreach ($data as $key => $value) {
            $set_data_arr[] = "$key='$value'";
        }
        $set_data = implode(", ", $set_data_arr);

        $sql = "UPDATE users SET $set_data WHERE $where_data";

        if (DATABASE->query($sql) === TRUE) {
            return true;
        } else {
            throw new Exception(DATABASE->error);
        }
    }

    public static function delete($where)
    {

        // generate sql where condition
        $where_data = Helper::generateWhere($where);

        $sql = "DELETE FROM users WHERE $where_data";

        if (DATABASE->query($sql) === TRUE) {
            return true;
        } else {
            throw new Exception(DATABASE->error);
        }
    }

    public static function query($where)
    {
        $data = null;

        $sql = "SELECT * FROM users WHERE $where";
        $result = DATABASE->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
}
