<?php
namespace models;

class Story
{
    public static function stories($author_id = null)
    {
        $query = null;
        $conn = Db::connection();

        if ($author_id)
        {
            $sql = 'select * from story where authorid = ?';
            $query = $conn->prepare($sql)->execute($author_id);
        }
        else
        {
            $sql = 'select * from story';
            $query = $conn->query($sql);
        }
        //throw new \Exception('Testing...');

        return $query->fetchAll();
    }

    public static function create($story)
    {
        $query = null;
        $conn = Db::connection();
        $sql = 'INSERT INTO story (account_id, slug, title, description) VALUES (?, ?, ?, ?)';

        $query = $conn->prepare($sql);
        $query->execute(array($story->account_id, $story->slug, $story->title, $story->description));

        return $conn->lastInsertId();
    }
}