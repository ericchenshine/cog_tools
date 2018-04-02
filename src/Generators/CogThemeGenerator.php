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

    $output->writeln('Creating sub theme!');

    $vars['base_theme'] = Utils::human2machine($vars['base_theme']);

    // Where (inside themes/) to put this stuff, hardcoded for now.
    $location = 'custom/';

    $prefix = $vars['machine_name'] . '/' . $vars['machine_name'];

    $this->addFile()
      ->path($location . $prefix . '.info.yml')
      ->template('starterkit/theme-info.twig');

    $this->addFile()
      ->path($location . $prefix . '.libraries.yml')
      ->template('starterkit/theme-libraries.twig');

    $this->addFile()
      ->path($location . $prefix . '.breakpoints.yml')
      ->template('starterkit/breakpoints.twig');

    $this->addFile()
      ->path($location . $prefix . '.theme')
      ->template('starterkit/theme.twig');

    $this->addFile()
      ->path($location . '{machine_name}/js/' . str_replace('_', '-', $vars['machine_name']) . '.js')
      ->template('starterkit/javascript.twig');

    $this->addFile()
      ->path($location . '{machine_name}/theme-settings.php')
      ->template('starterkit/theme-settings-form.twig');

    $this->addFile()
      ->path($location . '{machine_name}/config/install/{machine_name}.settings.yml')
      ->template('starterkit/theme-settings-config.twig');

    $this->addFile()
      ->path($location . '{machine_name}/config/schema/{machine_name}.schema.yml')
      ->template('starterkit/theme-settings-schema.twig');

    $this->addFile()
      ->path($location . '{machine_name}/logo.svg')
      ->template('starterkit/theme-logo.twig');

    $this->addDirectory()
      ->path($location . '{machine_name}/templates');

    $this->addDirectory()
      ->path($location . '{machine_name}/images');

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
        ->path($location . '{machine_name}/sass/' . $file)
        ->content('');
    }

    $this->addFile()
      ->path($location . '{machine_name}/package.json')
      ->template('starterkit/package.twig');

    $this->addFile()
      ->path($location . '{machine_name}/.eslintignore')
      ->template('starterkit/.eslintignore');

    $this->addFile()
      ->path($location . '{machine_name}/.stylelintrc.json')
      ->template('starterkit/.stylelintrc.json');

    $this->addFile()
      ->path($location . '{machine_name}/logo.png')
      ->template('starterkit/logo.png');

    $this->addFile()
      ->path($location . '{machine_name}/install-node.sh')
      ->template('starterkit/install-node.sh');

    $this->addFile()
      ->path($location . '{machine_name}/screenshot.png')
      ->template('starterkit/screenshot.png');

    $dir    = $this->templatePath . '/starterkit/_readme';
    $files = array_diff(scandir($dir), array('..', '.'));
    foreach ($files as $file) {
      $filename = basename($file, '.twig');
      $this->addFile()
        ->path($location . '{machine_name}/_readme/' . $filename . '.md')
        ->template('starterkit/_readme/' . $file);
    }

    // @TODO need panel_layouts (layouts)
//    $dir    = $this->templatePath . '/starterkit/panel_layouts';
//    $files = array_diff(scandir($dir), array('..', '.'));
//    foreach ($files as $file) {
//      $output->writeln($file);
//
//      $this->addFile()
//        ->path($location . '{machine_name}/panel_layouts/' . $file)
//        ->template('starterkit/panel_layouts/' . $file);
//    }

    // @TODO need _theming-guide
    $dir    = $this->templatePath . '/starterkit/_theming-guide';
    $files = array_diff(scandir($dir), array('..', '.'));
    foreach ($files as $file) {
      $filename = basename($file, '.twig');
      $this->addFile()
        ->path($location . '{machine_name}/_theming-guide/' . $filename . '.md')
        ->template('starterkit/_theming-guide/' . $file);
    }

    // Additional files.
    $option_questions['gulp_tasks'] = new ConfirmationQuestion('Would you like to create gulp files?', TRUE);

    $options = $this->collectVars($input, $output, $option_questions);

    if ($options['gulp_tasks']) {

      $this->addFile()
        ->path($location . '{machine_name}/gulpfile.js')
        ->template('optional/gulpfile.twig');

      // @TODO This seems dense.
      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/browser-sync.js')
        ->template('optional/gulp-tasks/browser-sync.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/build.js')
        ->template('optional/gulp-tasks/build.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/clean.js')
        ->template('optional/gulp-tasks/clean.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/clean-css.js')
        ->template('optional/gulp-tasks/clean-css.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/clean-styleguide.js')
        ->template('optional/gulp-tasks/clean-styleguide.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/compile-js.js')
        ->template('optional/gulp-tasks/compile-js.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/compile-sass.js')
        ->template('optional/gulp-tasks/compile-sass.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/compile-styleguide.js')
        ->template('optional/gulp-tasks/compile-styleguide.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/default.js')
        ->template('optional/gulp-tasks/default.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/lint-css.js')
        ->template('optional/gulp-tasks/lint-css.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/lint-js.js')
        ->template('optional/gulp-tasks/lint-js.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/minify-css.js')
        ->template('optional/gulp-tasks/minify-css.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/pa11y.js')
        ->template('optional/gulp-tasks/pa11y.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/serve.js')
        ->template('optional/gulp-tasks/serve.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/test-css.js')
        ->template('optional/gulp-tasks/test-css.js');

      $this->addFile()
        ->path($location . '{machine_name}/gulp-tasks/watch.js')
        ->template('optional/gulp-tasks/watch.js');
    }

  }

}
