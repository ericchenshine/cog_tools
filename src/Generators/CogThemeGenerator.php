<?php

namespace Drupal\cog_tools\Generators;

use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class CogThemeGenerator extends BaseGenerator
{
  protected $name = 'cog-theme';
  protected $description = 'Generates a cog theme.';
  protected $alias = 'cog';
  protected $templatePath = __DIR__ . "/../../templates";
  protected $destination = 'themes';

  protected function interact(InputInterface $input, OutputInterface $output) {
    $questions['name'] = new Question('Theme name');
    $questions['machine_name'] = new Question('Theme machine name');
    $questions['base_theme'] = new Question('Base theme', 'cog');
    $questions['description'] = new Question('Description', 'Acquia D8 starter theme');
    $questions['package'] = new Question('Package', 'Custom');


    $vars = $this->collectVars($input, $output, $questions);
    $vars['base_theme'] = Utils::human2machine($vars['base_theme']);

    $prefix = $vars['machine_name'] . '/' . $vars['machine_name'];

    $this->addFile()
      ->path($prefix . '.info.yml')
      ->template('starterkit/theme-info.twig');

    $this->addFile()
      ->path($prefix . '.libraries.yml')
      ->template('starterkit/theme-libraries.twig');

    $this->addFile()
      ->path($prefix . '.breakpoints.yml')
      ->template('starterkit/breakpoints.twig');

    $this->addFile()
      ->path($prefix . '.theme')
      ->template('starterkit/theme.twig');

    $this->addFile()
      ->path('{machine_name}/js/' . str_replace('_', '-', $vars['machine_name']) . '.js')
      ->template('starterkit/javascript.twig');

    $this->addFile()
      ->path('{machine_name}/theme-settings.php')
      ->template('starterkit/theme-settings-form.twig');

    $this->addFile()
      ->path('{machine_name}/config/install/{machine_name}.settings.yml')
      ->template('starterkit/theme-settings-config.twig');

    $this->addFile()
      ->path('{machine_name}/config/schema/{machine_name}.schema.yml')
      ->template('starterkit/theme-settings-schema.twig');

    $this->addFile()
      ->path('{machine_name}/logo.svg')
      ->template('starterkit/theme-logo.twig');

    $this->addDirectory()
      ->path('{machine_name}/templates');

    $this->addDirectory()
      ->path('{machine_name}/images');

    $css_files = [
      'base/elements.scss',
      'components/block.scss',
      'components/breadcrumb.scss',
      'components/field.scss',
      'components/form.scss',
      'components/header.scss',
      'components/menu.scss',
      'components/messages.scss',
      'components/node.scss',
      'components/sidebar.scss',
      'components/table.scss',
      'components/tabs.scss',
      'components/buttons.scss',
      'layouts/layout.scss',
      'theme/print.scss',
    ];
    foreach ($css_files as $file) {
      $this->addFile()
        ->path('{machine_name}/sass/' . $file)
        ->content('');
    }

    // Additional files.
    $option_questions['gulp_tasks'] = new ConfirmationQuestion('Would you like to create gulp files?', TRUE);

    $options = $this->collectVars($input, $output, $option_questions);

    if ($options['gulp_tasks']) {
      $this->addFile()
        ->path('{machine_name}/gulpfile.js')
        ->template('optional/gulpfile.twig');
    }

  }

}
