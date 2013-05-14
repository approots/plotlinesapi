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
        $id = null;
        $startPassageId = null;
        $query = null;
        $conn = Db::connection();

        $sql = 'INSERT INTO passage (story_id, title, body) VALUES (?, ?, ?)';
        $query = $conn->prepare($sql);
        $query->execute(array($passage->storyId, $passage->title, $passage->body));
        $id = $conn->lastInsertId();

        // All stories require a starting point
        // TODO join on passages to be sure the passage id in start_passage still exists.
        $sql = 'SELECT start_passage_id from story';
        $query = $conn->query($sql);
        $startPassageId = $query->fetchColumn();
        if (! $startPassageId)
        {
            $sql = 'UPDATE STORY SET start_passage_id = :start_passage_id where id = :id';
            $query = $conn->prepare($sql);
            if (! $query->execute(
                array(
                    ":start_passage_id" => $id,
                    ":id" => $passage->storyId)
                )
            )
            {
                $error = $query->errorInfo();
                throw new Exception($query->errorInfo($error[2]));
            }
        }

        return $id;
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
        $sql = 'UPDATE passage set title = :title, body = :body
                where id = :id';

        $query = $conn->prepare($sql);
        if (! $query->execute(
            array(
                ":title" => $passage->title,
                ":body" => $passage->body,
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