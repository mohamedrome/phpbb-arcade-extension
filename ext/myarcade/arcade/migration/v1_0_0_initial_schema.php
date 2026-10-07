<?php
/**
 *
 * @package myarcade/arcade
 */

namespace myarcade\arcade\migration;

class v1_0_0_initial_schema extends \phpbb\db\migration\migration
{
    public static function depends_on()
    {
        return ['\phpbb\db\migration\data\v33x\v333'];
    }

    public function update_schema()
    {
        return [
            'add_tables' => [
                $this->table_prefix . 'arcade_games' => [
                    'COLUMNS' => [
                        'game_id' => ['UINT', null, 'auto_increment'],
                        'game_name' => ['VCHAR:255', ''],
                        'game_slug' => ['VCHAR:255', ''],
                        'game_description' => ['TEXT_UNI', ''],
                        'game_type' => ['VCHAR:50', 'html5'],
                        'game_file' => ['VCHAR:255', ''],
                        'game_enabled' => ['TINT:1', 1],
                        'game_order' => ['UINT:11', 0],
                        'game_imported_from' => ['VCHAR:50', 'manual'],
                        'created_at' => ['TIMESTAMPS', 0],
                    ],
                    'PRIMARY_KEY' => 'game_id',
                ],
                $this->table_prefix . 'arcade_scores' => [
                    'COLUMNS' => [
                        'score_id' => ['UINT', null, 'auto_increment'],
                        'game_id' => ['UINT', 0],
                        'user_id' => ['UINT', 0],
                        'score_value' => ['INT:11', 0],
                        'score_data' => ['TEXT', ''],
                        'score_date' => ['TIMESTAMPS', 0],
                        'score_is_best' => ['TINT:1', 0],
                    ],
                    'PRIMARY_KEY' => 'score_id',
                ],
                $this->table_prefix . 'arcade_trophies' => [
                    'COLUMNS' => [
                        'trophy_id' => ['UINT', null, 'auto_increment'],
                        'game_id' => ['UINT', 0],
                        'user_id' => ['UINT', 0],
                        'trophy_name' => ['VCHAR:255', ''],
                        'trophy_date' => ['TIMESTAMPS', 0],
                    ],
                    'PRIMARY_KEY' => 'trophy_id',
                ],
            ],
        ];
    }

    public function revert_schema()
    {
        return [
            'drop_tables' => [
                $this->table_prefix . 'arcade_trophies',
                $this->table_prefix . 'arcade_scores',
                $this->table_prefix . 'arcade_games',
            ],
        ];
    }
}
