<?php

class Page extends Model
{

    public function getList($only_published = false)
    {
        $sql = "
        SELECT  p.*, LEFT(p.content, 100) as content, COUNT(com.post_id) AS countcom
        FROM    pages p
        LEFT JOIN
                comments com
        ON      com.post_id = p.id
        GROUP BY
                p.id
        ";
        if ( $only_published ){
            $sql .= " and is_published = 1";
        }
        return $this->db->query($sql);
    }

    public function getByAlias($alias)
    {
        $alias =$this->db->escape($alias);
        $sql = "select * from pages where alias = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "select * from pages where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $img, $id = null)
    {
        if( !isset($data['alias']) || !isset($data['title']) || !isset($data['content']) ){
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $is_published = isset($data['is_published']) ? 1 : 0;
        if ($img){
            unlink(ROOT.'/webroot/img/'.self::getImage($id));
            $imgName = $img['image']['name'];
            copy($img['image']['tmp_name'], ROOT.'/webroot/img/'. $imgName);
        }else{
            $imgName = null;
        }
        $date = date("Y-m-d H:i:s");
        if( !$id ){
            $sql = "
                insert into pages
                    set alias = '{$alias}',
                        title = '{$title}',
                        content = '{$content}',
                        is_published = '{$is_published}',
                        image = '{$imgName}'
           ";
        } else {
            $sql = "
                update pages
                    set alias = '{$alias}',
                        title = '{$title}',
                        content = '{$content}',
                        is_published = '{$is_published}',
                        image = '{$imgName}'
                    where id = '{$id}'
           ";
        }
        return $this->db->query($sql);
    }

    public function savePost($data)
    {
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $author = $this->db->escape($data['name']);
        $date = date("Y-m-d H:i:s");
        $sql = "
                insert into pages
                    set alias = '{$alias}',
                        title = '{$title}',
                        content = '{$content}',
                        is_published = 1,
                        author = '{$author}',
                        date = '{$date}'
           ";
        return $this->db->query($sql);
    }

    public function getImage($id)
    {
        $sql = "select image from pages where id = '{$id}'";
        $result = $this->db->query($sql);
        return $result[0]['image'];
    }

    public function delete($id)
    {
        $id = (int)$id;
        unlink(ROOT.'/webroot/img/'.self::getImage($id));
        $sql = "delete from pages where id = {$id}";
        return $this->db->query($sql);
    }

    public function getMutchViews($alias)
    {
        $redis = new Redis();
        $redis->connect('redis', 6379);
        $views = $redis->get($alias);
        if ( empty($views) ){
            $redis->set($alias, 1);
            return 1;
        } else {
            return $views;
        }
    }

    public function setMutchViews($alias, $views)
    {
        $views = $views + 1;
        $redis = new Redis();
        $redis->connect('redis', 6379);
        $redis->set($alias, $views);
    }

    public function saveComment($postId, $name, $comment)
    {
        $sql = "
                insert into comments
                    set post_id = '{$postId}',
                        comment = '{$comment}',
                        author = '{$name}'
           ";
        return $this->db->query($sql);
    }

    public function getListCommentsByPostId($postId)
    {
        $sql = "
                select * from comments where post_id = '{$postId}'
        ";
        return $this->db->query($sql);
    }
}