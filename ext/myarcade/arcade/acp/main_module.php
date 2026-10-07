<?php
namespace myarcade\arcade\acp;

class main_module
{
    public $page_title;
    public $tpl_name;
    public $u_action;

    public function main($id, $mode)
    {
        global $db, $config, $template, $user, $request;

        $this->page_title = 'ACP_ARCADE_TITLE';
        $this->tpl_name = 'arcade_acp';

        if ($request->is_set_post('submit'))
        {
            $game_name = $request->variable('game_name', '', true);
            $game_desc = $request->variable('game_description', '', true);
            $game_file = $request->variable('game_file', '', true);
            $game_type = $request->variable('game_type', 'html5', true);

            if ($game_name)
            {
                $game_slug = strtolower(str_replace(' ', '-', $game_name));
                $game_slug = preg_replace('/[^a-z0-9-_]+/', '', $game_slug);

                $sql = 'INSERT INTO ' . $config['db_prefix'] . 'arcade_games
                        (game_name, game_slug, game_description, game_type, game_file, game_enabled, game_order, game_imported_from)
                        VALUES (
                            ' . $db->sql_escape($game_name) . ',
                            ' . $db->sql_escape($game_slug) . ',
                            ' . $db->sql_escape($game_desc) . ',
                            ' . $db->sql_escape($game_type) . ',
                            ' . $db->sql_escape($game_file) . ',
                            1,
                            0,
                            ' . $db->sql_escape('manual') . '
                        )';

                $db->sql_query($sql);
            }
        }

        $sql = 'SELECT *
                FROM ' . $config['db_prefix'] . 'arcade_games
                ORDER BY game_order ASC, game_name ASC';
        $result = $db->sql_query($sql);

        $games = [];
        while ($row = $db->sql_fetchrow($result))
        {
            $games[] = [
                'ID' => (int) $row['game_id'],
                'NAME' => $row['game_name'],
                'SLUG' => $row['game_slug'],
            ];
        }
        $db->sql_freeresult($result);

        $template->assign_vars([
            'U_ACTION' => $this->u_action,
            'ARCADE_ACP_TITLE' => $user->lang('ACP_ARCADE_TITLE'),
            'ARCADE_ACP_DESCRIPTION' => $user->lang('ACP_ARCADE_SETTINGS'),
        ]);

        $template->assign_block_vars_array('games', $games);
    }
}
