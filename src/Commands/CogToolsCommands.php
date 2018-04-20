<?php

namespace Drupal\cog_tools\Commands;

use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class CogToolsCommands extends DrushCommands {

  /**
   * A helpful message.
   *
   * @param array $options
   *   An associative array of options whose values come from cli, aliases,
   *   config, etc.
   *
   * @option array option-name
   *   Description
   * @usage cog_tools-subTheme foo
   *   Usage description
   *
   * @command cog_tools:subTheme
   * @aliases cog
   */
  public function subTheme($options = ['option-name' => 'default']) {
    $this->logger()->notice(dt('Use `drush gen cog` to create a new cog subtheme.'));
  }

}
