# Acquia Cog Tools


Welcome to the [Acquia Cog Tools](https://github.com/acquia-pso/cog_tools) documentation site!


## Requirements

Cog Tools is a Drupal 8 module. It requires Drush 9.x to function.


## Generating a Cog Subtheme

Download the cog_tools module.
 
`composer require drupal/cog_tools`
 
Enable the cog_tools module

`drush pm:enable cog_tools`

Create a sub theme with drush.

`drush generate cog`

Drush will provide a series of questions to set options for the generated theme. The only value without a default is the theme name.

Enable your new sub theme. For a theme with the machine name `durian`:

`drush theme:enable durian`


## Command line options

Passing in arguments via the command line:

`drush gen cog --answers '{"name":"Durian", "machine_name": "durian", "base_theme": "classy", "description": "What a nice theme.", "package": "Custom", "build_tasks": "yes", "layouts":"yes", "theme_settings":"yes","style_guide":"yes"}'`

Any answers that are left off here will be asked still, so this could be handy if you have a few options you almost always select.


## Contributing to Cog Tools

Please feel free to edit any of the pages in this documentation via the 'Edit on GitHub' link at the top right. If you would like to help improve Cog Tools, please file issues via the GitHub issue queue. See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for contribution guidelines and instructions.
