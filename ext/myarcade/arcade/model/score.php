<?php
namespace myarcade\arcade\model;

class score
{
    protected $db;
    protected $config;

    public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    public function saveScore($gameId, $userId, $scoreValue, $scoreData = '')
    {
        $sql = 'INSERT INTO ' . $this->config['db_prefix'] . 'arcade_scores
                (game_id, user_id, score_value, score_data, score_date, score_is_best)
                VALUES (
                    ' . (int) $gameId . ',
                    ' . (int) $userId . ',
                    ' . (int) $scoreValue . ',
                    ' . $this->db->sql_escape($scoreData) . ',
                    ' . time() . ',
                    1
                )';

        $this->db->sql_query($sql);
    }

    public function getTopScores($gameId, $limit = 10)
    {
        $sql = 'SELECT s.user_id, s.score_value, s.score_data, u.username
                FROM ' . $this->config['db_prefix'] . 'arcade_scores s
                LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = s.user_id
                WHERE s.game_id = ' . (int) $gameId . '
                ORDER BY s.score_value DESC, s.score_date DESC
                LIMIT ' . (int) $limit;

        $result = $this->db->sql_query($sql);
        $scores = [];

        while ($row = $this->db->sql_fetchrow($result))
        {
            $scores[] = $row;
        }

        $this->db->sql_freeresult($result);

        return $scores;
    }

    public function getUserBestScore($gameId, $userId)
    {
        $sql = 'SELECT score_value
                FROM ' . $this->config['db_prefix'] . 'arcade_scores
                WHERE game_id = ' . (int) $gameId . '
                AND user_id = ' . (int) $userId . '
                ORDER BY score_value DESC
                LIMIT 1';

        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        return ($row) ? (int) $row['score_value'] : 0;
    }
}
