<?php
namespace myarcade\arcade\controller;

use phpbb\controller\helper;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\user;
use phpbb\request\request;

class main
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

    public function index()
    {
        $games = [];

        $sql = 'SELECT *
                FROM ' . $this->config['db_prefix'] . 'arcade_games
                WHERE game_enabled = 1
                ORDER BY game_order ASC, game_name ASC';

        $result = $this->db->sql_query($sql);

        while ($row = $this->db->sql_fetchrow($result))
        {
            $games[] = [
                'GAME_ID' => (int) $row['game_id'],
                'GAME_NAME' => $row['game_name'],
                'GAME_SLUG' => $row['game_slug'],
                'GAME_DESCRIPTION' => $row['game_description'],
            ];
        }

        $this->db->sql_freeresult($result);

        $this->template->assign_var('ARCADE_GAMES_COUNT', count($games));

        $this->template->assign_block_vars_array('arcade_games', $games);

        return $this->helper->render('arcade_index.html', 'Arcade');
    }
}
