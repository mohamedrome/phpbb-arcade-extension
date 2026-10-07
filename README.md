# Arcade extension for phpBB 3.3.5

This extension provides a complete arcade system for phpBB 3.3.5 with:
- arcade index page
- game pages with iframe support
- score ranking system
- trophy model
- ACP management panel
- IPB import foundation

## Installation

1. Copy the `ext/myarcade/arcade` folder into your phpBB `ext` directory.
2. Run:
   ```bash
   php bin/phpbbcli.php db:migrate
   ```
3. Enable the extension in the ACP.
4. Add games from the ACP or import them from IPB.

## Important

This is a starter project and must be adapted to your real IPB Arcade tables and your actual game files.

## Next steps

- Import exact IPB tables
- Integrate phpBB user profiles
- Add trophy conditions
- Add admin deletion/edit form
- Add reputation and achievements

## Features

### Public Side
- List all available games
- Play games in iframe
- View top scores per game
- Trophy display

### ACP
- Add new games
- View game list
- Import from IPB Arcade
- Manage game settings

### Database
- `arcade_games` - Games table
- `arcade_scores` - Scores table
- `arcade_trophies` - Trophies table

## Configuration

To import games from IPB, you need to adapt the `import_ipb.php` model with your exact IPB table names and structure.
