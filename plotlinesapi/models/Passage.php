<?php
namespace models;

class Passage
{
    public static function passages($storyId)
    {
        $query = null;
        $conn = Db::connection();
        $sql = 'select * from passage where story_id = :story_id';

        $query = $conn->prepare($sql);
        if (! $query->execute(array(":story_id" => $storyId))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        return $query->fetchAll();
    }

    public static function passage($id)
    {
        $query = null;
        $conn = Db::connection();
        $sql = 'select * from passage where id = :id';

        $query = $conn->prepare($sql);
        if (! $query->execute(array(":id" => $id))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        return $query->fetch();
    }

    public static function create($passage)
    {
        //throw new \Exception('A problem with validation?');
        $query = null;
        $conn = Db::connection();
        $sql = 'INSERT INTO passage (story_id, title, passage) VALUES (?, ?, ?)';

        $query = $conn->prepare($sql);
        $query->execute(array($passage->storyId, $passage->title, $passage->passage));

        return $conn->lastInsertId();
    }

    public static function delete($id)
    {
        $query = null;
        $conn = Db::connection();
        $sql = 'delete from passage where id = :id';
        $query = $conn->prepare($sql);
        if (! $query->execute(array(":id" => $id))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }
    }

    public static function update($passage)
    {
        //throw new \Exception('A problem with validation?');
        $query = null;
        $conn = Db::connection();
        $sql = 'UPDATE passage set title = :title, passage = :passage
                where id = :id';

        $query = $conn->prepare($sql);
        if (! $query->execute(
            array(
                ":title" => $passage->title,
                ":passage" => $passage->passage,
                ":id" => $passage->id))
        )
        {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        // TODO query story here to be sure of update?
        return $passage;
    }
}