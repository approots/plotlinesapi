<?php
namespace models;

class Story
{
    public static function stories($accountId = null)
    {
        $query = null;
        $conn = Db::connection();

        if ($accountId)
        {
            $sql = 'select * from story where account_id = :account_id';
            $query = $conn->prepare($sql);
            if (! $query->execute(array(":account_id" => $accountId)))
            {
                $error = $query->errorInfo();
                throw new Exception($query->errorInfo($error[2]));
            }
        }
        else
        {
            $sql = 'select * from story';
            $query = $conn->query($sql);
        }

        return $query->fetchAll();
    }

    public static function story($id)
    {
        $query = null;
        $conn = Db::connection();
        $sql = 'select * from story where id = :id';

        $query = $conn->prepare($sql);
        if (! $query->execute(array(":id" => $id))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        return $query->fetch();
    }

    public static function otherSlugs($accountId, $id)
    {
        $data = array();
        $query = null;
        $conn = Db::connection();

        $sql = 'select slug from story where account_id = :account_id and id != :id';
        $query = $conn->prepare($sql);
        if (! $query->execute(array(":account_id"=>$accountId,":id" => $id))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        while ( $result = $query->fetchColumn()) {
            $data[] = $result;
        }

        return $data;
    }

    public static function existingSlugs($accountId)
    {
        $data = array();
        $query = null;
        $conn = Db::connection();

        $sql = 'select slug from story where account_id = :account_id';
        $query = $conn->prepare($sql);
        if (! $query->execute(array(":account_id"=>$accountId))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        while ( $result = $query->fetchColumn()) {
            $data[] = $result;
        }

        return $data;
    }

    public static function delete($accountId, $id)
    {
        //throw new \Exception('A problem with validation?');
        $query = null;
        $conn = Db::connection();
        $sql = 'delete from story where account_id = :account_id and id = :id';
        $query = $conn->prepare($sql);
        if (! $query->execute(array(":account_id" => $accountId, ":id" => $id))) {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }
    }

    public static function update($story)
    {
        //throw new \Exception('A problem with validation?');
        $query = null;
        $conn = Db::connection();
        $sql = 'UPDATE story set slug = :slug, title = :title, description = :description
                where account_id = :account_id and id = :id';

        $query = $conn->prepare($sql);
        if (! $query->execute(
            array(
            ":slug" => $story->slug,
            ":title" => $story->title,
            ":description" => $story->description,
            ":account_id" => $story->accountId,
            ":id" => $story->id))
        )
        {
            $error = $query->errorInfo();
            throw new Exception($query->errorInfo($error[2]));
        }

        // TODO query story here to be sure of update?
        return $story;
    }

    public static function create($story)
    {
        //throw new \Exception('A problem with validation?');
        $query = null;
        $conn = Db::connection();
        $sql = 'INSERT INTO story (account_id, slug, title, description) VALUES (?, ?, ?, ?)';

        $query = $conn->prepare($sql);
        $query->execute(array($story->accountId, $story->slug, $story->title, $story->description));

        return $conn->lastInsertId();
    }
}