<?php
namespace myarcade\arcade\controller;

use phpbb\controller\helper;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\user;
use phpbb\request\request;

class game
{
    protected $config;
    protected $db;
    protected $template;
    protected $user;
    protected $request;
    protected $helper;
    protected $auth;

    public function __construct(config $config, driver_interface $db, template $template, user $user, request $request, helper $helper, \phpbb\auth\auth $auth)
    {
        $this->config = $config;
        $this->db = $db;
        $this->template = $template;
        $this->user = $user;
        $this->request = $request;
        $this->helper = $helper;
        $this->auth = $auth;
    }

    public function show($game_slug)
    {
        $sql = 'SELECT *
                FROM ' . $this->config['db_prefix'] . 'arcade_games
                WHERE game_slug = ' . $this->db->sql_escape($game_slug) . '
                AND game_enabled = 1';

        $result = $this->db->sql_query_limit($sql, 1);
        $game = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if (!$game)
        {
            trigger_error('ARCADE_GAME_NOT_FOUND');
        }

        $scoresSql = 'SELECT s.user_id, s.score_value, u.username
                      FROM ' . $this->config['db_prefix'] . 'arcade_scores s
                      LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = s.user_id
                      WHERE s.game_id = ' . (int) $game['game_id'] . '
                      ORDER BY s.score_value DESC, s.score_date DESC
                      LIMIT 10';

        $scoreResult = $this->db->sql_query($scoresSql);
        $scores = [];
        $i = 1;

        while ($score = $this->db->sql_fetchrow($scoreResult))
        {
            $scores[] = [
                'NUMBER' => $i,
                'USERNAME' => $score['username'],
                'SCORE' => (int) $score['score_value'],
            ];
            $i++;
        }

        $this->db->sql_freeresult($scoreResult);

        $this->template->assign_vars([
            'ARCADE_GAME_NAME' => $game['game_name'],
            'ARCADE_GAME_DESCRIPTION' => $game['game_description'],
            'ARCADE_GAME_FILE' => $game['game_file'],
        ]);

        $this->template->assign_block_vars_array('arcade_scores', $scores);

        return $this->helper->render('arcade_game.html', $game['game_name']);
    }

    public function ranking($game_slug)
    {
        return $this->show($game_slug);
    }
}
