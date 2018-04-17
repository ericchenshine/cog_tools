<?php

namespace Drupal\cog_tools\Generators;

use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;


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

    // Additional files.
    $option_questions['gulp_tasks'] = new ConfirmationQuestion('Would you like to create gulp files?', TRUE);
    $option_questions['layouts'] = new ConfirmationQuestion('Would you like to add layout files?', FALSE);
    $option_questions['documentation'] = new ConfirmationQuestion('Would you like to add documentation files?', FALSE);
    $option_questions['theme_settings'] = new ConfirmationQuestion('Would you like to add starter theme settings files?', FALSE);
    $option_questions['style_guide'] = new ChoiceQuestion('Would you like to include the style guide?', ['No', 'KSS'], 'No');

    $options = $this->collectVars($input, $output, $option_questions);

    $output->writeln('Creating sub theme!');

    $vars['base_theme'] = Utils::human2machine($vars['base_theme']);

    // Where (inside themes/) to put this stuff, hardcoded for now.
    $location = 'custom/';

    $prefix = $vars['machine_name'] . '/' . $vars['machine_name'];

    // Core cog stuff.
    $this->addFile()
      ->path($location . $prefix . '.info.yml')
      ->template('starterkit/theme-info.twig');

    $this->addFile()
      ->path($location . '{machine_name}/logo.svg')
      ->template('starterkit/theme-logo.twig');

    $this->addFile()
      ->path($location . $prefix . '.libraries.yml')
      ->template('starterkit/theme-libraries.twig');

    $this->addFile()
      ->path($location . $prefix . '.breakpoints.yml')
      ->template('starterkit/breakpoints.twig');

    $this->addFile()
      ->path($location . $prefix . '.theme')
      ->template('starterkit/theme.twig');

    // Front end tools.
    $this->addFile()
      ->path($location . '{machine_name}/package.json')
      ->template('starterkit/package.twig');

    $this->addFile()
      ->path($location . '{machine_name}/.eslintignore')
      ->template('starterkit/.eslintignore');

    $this->addFile()
      ->path($location . '{machine_name}/.stylelintrc.json')
      ->template('starterkit/.stylelintrc.json');

    // Example and starter files.
    if ($options['theme_settings']) {
      $this->addFile()
        ->path($location . '{machine_name}/theme-settings.php')
        ->template('starterkit/theme-settings-form.twig');

      $this->addFile()
        ->path($location . '{machine_name}/config/install/{machine_name}.settings.yml')
        ->template('starterkit/theme-settings-config.twig');

      $this->addFile()
        ->path($location . '{machine_name}/config/schema/{machine_name}.schema.yml')
        ->template('starterkit/theme-settings-schema.twig');
    }
    // Example js file.
    $this->addFile()
      ->path($location . '{machine_name}/js/' . str_replace('_', '-', $vars['machine_name']) . '.js')
      ->template('starterkit/javascript.twig');

    // Empty directories.
    $this->addDirectory()
      ->path($location . '{machine_name}/templates');

    $this->addDirectory()
      ->path($location . '{machine_name}/images');

    // Blank starter css files.
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
      'theme/print.scss',
    ];
    foreach ($css_files as $file) {
      $this->addFile()
        ->path($location . '{machine_name}/sass/' . $file)
        ->content('');
    }

    // Node and nvm install script.
    $this->addFile()
      ->path($location . '{machine_name}/install-node.sh')
      ->template('starterkit/install-node.sh');

    // Documentation.
    if ($options['documentation']) {
      $output->writeln('Adding documentation.');

      $dir    = $this->templatePath . '/starterkit/_readme';
      $files = array_diff(scandir($dir), array('..', '.'));
      foreach ($files as $file) {
        $filename = basename($file, '.twig');
        $this->addFile()
          ->path($location . '{machine_name}/_readme/' . $filename . '.md')
          ->template('starterkit/_readme/' . $file);
      }

      $dir    = $this->templatePath . '/starterkit/_theming-guide';
      $files = array_diff(scandir($dir), array('..', '.'));
      foreach ($files as $file) {
        $filename = basename($file, '.twig');
        $this->addFile()
          ->path($location . '{machine_name}/_theming-guide/' . $filename . '.md')
          ->template('starterkit/_theming-guide/' . $file);
      }
    }


    if ($options['style_guide'] == 'KSS') {
      $output->writeln('Do style guide!');
    }

    // Gulp build tasks.
    if ($options['gulp_tasks']) {
      $output->writeln('Adding gulp tasks.');

      $this->addFile()
        ->path($location . '{machine_name}/gulpfile.js')
        ->template('optional/gulpfile.twig');

//      $dir    = $this->templatePath . '/optional/gulp-tasks';
//      $files = array_diff(scandir($dir), array('..', '.'));
//
//      foreach ($files as $file) {
//        $this->addFile()
//          ->path($location . '{machine_name}/gulp-tasks/' .  $file)
//          ->template('/optional/gulp-tasks/' . $file);
//      }
      // @TODO
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/browser-sync.js')
        ->template('optional/gulp-tasks/browser-sync.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/build.js')
        ->template('optional/gulp-tasks/build.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/clean.js')
        ->template('optional/gulp-tasks/clean.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/clean-css.js')
        ->template('optional/gulp-tasks/clean-css.twig');

      if ($options['style_guide'] == 'KSS') {
        $this->addFile()
          ->path($location . '{machine_name}/gulp-tasks/clean-styleguide.js')
          ->template('optional/gulp-tasks/clean-styleguide.twig');
        $this->addFile()
          ->path($location . '{machine_name}/gulp-tasks/compile-styleguide.js')
          ->template('optional/gulp-tasks/compile-styleguide.twig');
      }

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/compile-js.js')
        ->template('optional/gulp-tasks/compile-js.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/compile-sass.js')
        ->template('optional/gulp-tasks/compile-sass.twig');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/default.js')
        ->template('optional/gulp-tasks/default.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/lint-css.js')
        ->template('optional/gulp-tasks/lint-css.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/lint-js.js')
        ->template('optional/gulp-tasks/lint-js.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/minify-css.js')
        ->template('optional/gulp-tasks/minify-css.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/pa11y.js')
        ->template('optional/gulp-tasks/pa11y.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/serve.js')
        ->template('optional/gulp-tasks/serve.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/test-css.js')
        ->template('optional/gulp-tasks/test-css.twig');
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/watch.js')
        ->template('optional/gulp-tasks/watch.twig');
    }

    // Layouts.
    if ($options['layouts']) {
      $output->writeln('Adding layout files.');

      $this->addFile()
        ->path($location . $prefix . '.layouts.yml')
        ->template('optional/layouts.twig');

      $this->addFile()
        ->path($location . '{machine_name}/sass/layouts/layouts.scss')
        ->template('optional/layouts.scss');

      $dir = $this->templatePath . '/optional/layouts';
      $directories = array_diff(scandir($dir), ['..', '.']);

      foreach ($directories as $directory) {
        $dir = $this->templatePath . '/optional/layouts/' . $directory;
        $files = array_diff(scandir($dir), ['..', '.']);
        foreach ($files as $file) {
          $this->addFile()
            ->path($location . '{machine_name}/layouts/' . $directory . '/' . $file)
            ->template('optional/layouts/' . $directory . '/' . $file);
        }
      }
    }

  }

}
