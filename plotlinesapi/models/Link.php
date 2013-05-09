<?php
namespace models;

class Link
{
    public static function links($passageId)
    {
        $query = null;
        $conn = Db::connection();
        $sql = 'select * from link where passage_id = :passage_id';
        $query = $conn->prepare($sql);
        if (! $query->execute(array(":passage_id" => $passageId)))
        {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        return $query->fetchAll();
    }
}
