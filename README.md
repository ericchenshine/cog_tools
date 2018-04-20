# cog_tools
A companion module to the Cog theme.

Enables sub theme generation via drush 9.

## Quickstart:

 Download the cog base theme and this module
 
 `composer require drupal/cog drupal/cog_tools`
 
Enable this module

`drush pm:enable cog_tools`

Create a sub theme with drush.

`drush generate cog`

Answer the questions.

Enable your new sub theme. For a theme with the machine name `durian`:

`drush theme:enable durian`

## Advanced topics

Passing in arguments via the command line:

`drush gen cog --answers '{"name":"Durian", "machine_name": "durian", "base_theme": "cog", "description": "What a nice theme.", "package": "Custom", "build_tasks": "yes", "layouts":"yes", "documentation":"yes", "theme_settings":"yes","style_guide":"yes"}'`

Any answers that are left off here will be asked still, so this could be handy if you have a few options you almost always select.
