# Cog Tools

A Drupal 8 sub theme generator, with build tools.

Enables sub theme generation via drush 9.

## Quickstart:

 Download the cog_tools module.
 
 `composer require drupal/cog_tools`
 
Enable the cog_tools module

`drush pm:enable cog_tools`

Create a sub theme with drush.

`drush generate cog`

Answer the questions.

Enable your new sub theme. For a theme with the machine name `durian`:

`drush theme:enable durian`

## Advanced topics

Passing in arguments via the command line:

`drush gen cog --answers '{"name":"Durian", "machine_name": "durian", "base_theme": "classy", "description": "What a nice theme.", "package": "Custom", "build_tasks": "yes", "layouts":"yes", "theme_settings":"yes","style_guide":"yes"}'`

Any answers that are left off here will be asked still, so this could be handy if you have a few options you almost always select.
