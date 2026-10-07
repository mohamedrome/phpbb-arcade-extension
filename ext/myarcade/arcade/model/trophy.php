<?php
namespace myarcade\arcade\model;

class trophy
{
    protected $db;
    protected $config;

    public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    public function awardTrophy($gameId, $userId, $name)
    {
        $sql = 'INSERT INTO ' . $this->config['db_prefix'] . 'arcade_trophies
                (game_id, user_id, trophy_name, trophy_date)
                VALUES (
                    ' . (int) $gameId . ',
                    ' . (int) $userId . ',
                    ' . $this->db->sql_escape($name) . ',
                    ' . time() . '
                )';

        $this->db->sql_query($sql);
    }

    public function getUserTrophies($userId)
    {
        $sql = 'SELECT t.*, g.game_name
                FROM ' . $this->config['db_prefix'] . 'arcade_trophies t
                LEFT JOIN ' . $this->config['db_prefix'] . 'arcade_games g ON g.game_id = t.game_id
                WHERE t.user_id = ' . (int) $userId . '
                ORDER BY t.trophy_date DESC';

        $result = $this->db->sql_query($sql);
        $trophies = [];

        while ($row = $this->db->sql_fetchrow($result))
        {
            $trophies[] = $row;
        }

        $this->db->sql_freeresult($result);

        return $trophies;
    }
}
