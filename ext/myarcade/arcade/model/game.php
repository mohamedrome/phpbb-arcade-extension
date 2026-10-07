<?php
namespace myarcade\arcade\model;

class game
{
    protected $db;
    protected $config;

    public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    public function getAllGames()
    {
        $sql = 'SELECT *
                FROM ' . $this->config['db_prefix'] . 'arcade_games
                WHERE game_enabled = 1
                ORDER BY game_order ASC, game_name ASC';

        $result = $this->db->sql_query($sql);
        $games = [];

        while ($row = $this->db->sql_fetchrow($result))
        {
            $games[] = $row;
        }

        $this->db->sql_freeresult($result);

        return $games;
    }

    public function getGameBySlug($slug)
    {
        $sql = 'SELECT *
                FROM ' . $this->config['db_prefix'] . 'arcade_games
                WHERE game_slug = ' . $this->db->sql_escape($slug) . '
                AND game_enabled = 1';

        $result = $this->db->sql_query_limit($sql, 1);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        return $row;
    }

    public function getGameById($gameId)
    {
        $sql = 'SELECT *
                FROM ' . $this->config['db_prefix'] . 'arcade_games
                WHERE game_id = ' . (int) $gameId;

        $result = $this->db->sql_query_limit($sql, 1);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        return $row;
    }

    public function createGame($name, $description = '', $file = '', $type = 'html5')
    {
        $slug = $this->slugify($name);

        $sql = 'INSERT INTO ' . $this->config['db_prefix'] . 'arcade_games
                (game_name, game_slug, game_description, game_type, game_file, game_enabled, game_order, game_imported_from)
                VALUES (
                    ' . $this->db->sql_escape($name) . ',
                    ' . $this->db->sql_escape($slug) . ',
                    ' . $this->db->sql_escape($description) . ',
                    ' . $this->db->sql_escape($type) . ',
                    ' . $this->db->sql_escape($file) . ',
                    1,
                    0,
                    ' . $this->db->sql_escape('manual') . '
                )';

        $this->db->sql_query($sql);

        return $this->db->sql_nextid();
    }

    protected function slugify($string)
    {
        $string = strtolower(trim((string) $string));
        $string = preg_replace('/[^a-z0-9]+/i', '-', $string);
        $string = trim($string, '-');

        return $string ?: 'game';
    }
}
