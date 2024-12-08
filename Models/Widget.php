<?php
class Widget
{

    public static function get($where = [])
    {
        $data = null;

        // get widgets
        $sql = "SELECT * FROM widget";

        // generate sql where condition
        if (count($where) > 0) {
            $where_data = Helper::generateWhere($where);
            $sql .= " WHERE $where_data";
        }

        $result = DATABASE->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $widget = [
                    "id" => $id,
                    "type" => $row["widget_type"],
                ];

                // get widget details
                $sql1 = "SELECT * FROM widget_details where widget_id=$id";
                $result1 = DATABASE->query($sql1);

                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {

                        $widget[$row1["widget_key"]] = $row1["widget_value"];
                    }
                }
                $data[] = $widget;
            }
        }

        return $data;
    }

    public static function create($widget_type, $data)
    {
        // Transaction
        DATABASE->autocommit(FALSE);

        // create widget
        $sql = "INSERT INTO widget (widget_type) VALUES ($widget_type)";

        if (DATABASE->query($sql) === TRUE) {
            $widget_id = DATABASE->insert_id;   // return inserted id

            // create widget details
            foreach ($data as $key => $value) {
                $value = "'" . DATABASE->real_escape_string($value)  . "'";

                $sql = "INSERT INTO widget_details (widget_key, widget_value, widget_id) VALUES ('$key', $value, $widget_id)";

                // not created throw error
                if (DATABASE->query($sql) !== TRUE) {
                    DATABASE->close();
                    throw new Exception(DATABASE->error);
                }
            }

            DATABASE->commit(); // commit the transaction
            DATABASE->close();
            return true;
        } else {
            DATABASE->close();
            throw new Exception(DATABASE->error);
        }
    }

    public static function first($where)
    {
        $data = null;

        // generate sql where condition
        $where_data = Helper::generateWhere($where);

        $sql = "SELECT * FROM widget WHERE $where_data";
        $result = DATABASE->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $data = [
                    "id" => $id,
                    "type" => $row["widget_type"],
                ];

                // get widget details
                $sql1 = "SELECT * FROM widget_details where widget_id=$id";
                $result1 = DATABASE->query($sql1);

                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {

                        $data[$row1["widget_key"]] = $row1["widget_value"];
                    }
                }
            }
        }

        return $data;
    }

    public static function update($data, $id)
    {
        // Transaction
        DATABASE->autocommit(FALSE);

        // update widget details
        foreach ($data as $key => $value) {
            $value = "'" . DATABASE->real_escape_string($value)  . "'";

            $sql = "UPDATE widget_details SET widget_value=$value WHERE widget_key='$key' and widget_id=$id";

            // not created throw error
            if (DATABASE->query($sql) !== TRUE) {
                DATABASE->close();
                throw new Exception(DATABASE->error);
            }
        }

        DATABASE->commit(); // commit the transaction
        DATABASE->close();
        return true;
    }

    public static function delete($id)
    {
        // Transaction
        DATABASE->autocommit(FALSE);

        // delete widget details
        $sql = "DELETE FROM widget_details WHERE widget_id=$id";

        if (DATABASE->query($sql) === TRUE) {

            // delete widget
            $sql = "DELETE FROM widget WHERE id=$id";

            if (DATABASE->query($sql) === TRUE) {
                DATABASE->commit(); // commit the transaction
                DATABASE->close();

                return true;
            } else {
                DATABASE->close();
                throw new Exception(DATABASE->error);
            }
        } else {
            DATABASE->close();
            throw new Exception(DATABASE->error);
        }
    }
}
