<?php
namespace myarcade\arcade\model;

class import_ipb
{
    protected $db;
    protected $config;

    public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    public function importGames($ipbPrefix)
    {
        $sql = 'SELECT *
                FROM ' . $ipbPrefix . 'arcade_games
                WHERE game_enabled = 1';

        $result = $this->db->sql_query($sql);

        while ($row = $this->db->sql_fetchrow($result))
        {
            $slug = $this->slugify($row['game_name'] ?? 'game');

            $checkSql = 'SELECT game_id
                         FROM ' . $this->config['db_prefix'] . 'arcade_games
                         WHERE game_slug = ' . $this->db->sql_escape($slug);

            $checkResult = $this->db->sql_query($checkSql);
            $existing = $this->db->sql_fetchrow($checkResult);
            $this->db->sql_freeresult($checkResult);

            if ($existing)
            {
                continue;
            }

            $insertSql = 'INSERT INTO ' . $this->config['db_prefix'] . 'arcade_games
                (game_name, game_slug, game_description, game_type, game_file, game_enabled, game_order, game_imported_from)
                VALUES (
                    ' . $this->db->sql_escape($row['game_name'] ?? '') . ',
                    ' . $this->db->sql_escape($slug) . ',
                    ' . $this->db->sql_escape($row['game_description'] ?? '') . ',
                    ' . $this->db->sql_escape('html5') . ',
                    ' . $this->db->sql_escape($row['game_file'] ?? '') . ',
                    1,
                    ' . (int) ($row['game_order'] ?? 0) . ',
                    ' . $this->db->sql_escape('ipb') . '
                )';

            $this->db->sql_query($insertSql);
        }

        $this->db->sql_freeresult($result);
    }

    public function importScores($ipbPrefix)
    {
        $sql = 'SELECT *
                FROM ' . $ipbPrefix . 'arcade_scores';

        $result = $this->db->sql_query($sql);

        while ($row = $this->db->sql_fetchrow($result))
        {
            $gameName = $row['game_name'] ?? null;

            if (!$gameName)
            {
                continue;
            }

            $gameSql = 'SELECT game_id
                        FROM ' . $this->config['db_prefix'] . 'arcade_games
                        WHERE game_name = ' . $this->db->sql_escape($gameName);

            $gameResult = $this->db->sql_query($gameSql);
            $gameRow = $this->db->sql_fetchrow($gameResult);
            $this->db->sql_freeresult($gameResult);

            if (!$gameRow)
            {
                continue;
            }

            $insertSql = 'INSERT INTO ' . $this->config['db_prefix'] . 'arcade_scores
                (game_id, user_id, score_value, score_data, score_date, score_is_best)
                VALUES (
                    ' . (int) $gameRow['game_id'] . ',
                    ' . (int) $row['user_id'] . ',
                    ' . (int) $row['score_value'] . ',
                    ' . $this->db->sql_escape($row['score_data'] ?? '') . ',
                    ' . (int) ($row['score_date'] ?? time()) . ',
                    1
                )';

            $this->db->sql_query($insertSql);
        }

        $this->db->sql_freeresult($result);
    }

    protected function slugify($string)
    {
        $string = strtolower(trim((string) $string));
        $string = preg_replace('/[^a-z0-9]+/i', '-', $string);
        $string = trim($string, '-');

        return $string ?: 'game';
    }
}
