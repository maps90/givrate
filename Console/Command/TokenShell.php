<?php

App::uses('AppShell', 'Console/Command');
App::uses('CroogoPlugin', 'Extensions.Lib');

class TokenShell extends AppShell {

/**
 * Model we use
 *
 * @var array
 */
	public $uses = array('Givrate.Token');

/**
 * CroogoPlugin class
 *
 * @var CroogoPlugin
 */
	protected $_CroogoPlugin = null;

/**
 * Initialize
 *
 * @param type $stdout
 * @param type $stderr
 * @param type $stdin
 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);
		$this->_CroogoPlugin = new CroogoPlugin();
		$this->stdout->styles('success', array('text' => 'green'));
		$this->stdout->styles('failed', array('text' => 'red'));
		$this->stdout->styles('bold', array('bold' => true));
		$this->initialize();
	}

/**
 * Display help/options
 */
	public function getOptionParser() {
		return parent::getOptionParser()
			->description(__('Givrate Token Utilities'))
			->addSubCommand('generate', array(
				'help' => __('Generate Token for model'),
				'parser' => array(
					'description' => 'Generate Token for model',
					'arguments' => array(
						'modelName' => array(
							'help' => __('Model Name'),
							'required' => true,
						),
						'pluginName' => array(
							'help' => __('Plugin Name'),
							'required' => true,
						),
						'id' => array(
							'help' => __('Model Id'),
						),
						'length' => array(
							'help' => __('Value length of tokens'),
						)
					)
				)
			))
			->addSubCommand('list_plugins', array(
				'help' => __('List all plugins'),
				)
			);
	}

/**
 * Generate a token according to the intended models
 * <id> : generate a token according to id (optional)
 * <length> : length tokens. Default length is 5 (optional)
 *
 * Usage: ./Console/cake givrate.token generate modelName pluginName <id> <length>
 */
	public function generate() {
		$args = $this->args;
		$this->args = array_map('strtolower', $this->args);
		$modelAlias = ucfirst($args[0]);
		$pluginName = ucfirst($this->args[1]);
		$modelId = isset($args[2]) ? $args[2] : null;
		$len = isset($args[3]) ? $args[3] : null;
		$extensions = $this->_CroogoPlugin->getPlugins();
		$active = CakePlugin::loaded($pluginName);

		if (!empty($pluginName) && !in_array($pluginName, $extensions) && !$active) {
			$this->err(__('plugin "%s" not found.', $pluginName));
			return false;
		}
		if (empty($len)) {
			$len = 5;
		}

		$options = array();
		if (!empty($modelId)) {
			$options = Set::merge($options, array(
				'conditions' => array(
					$modelAlias.'.id' => $modelId
				)
			));
		}

		$Model = ClassRegistry::init($pluginName.'.'.$modelAlias);
		$Model->recursive = -1;
		$results = $Model->find('all', $options);
		foreach ($results as $result) {
			$Model->id = $result[$modelAlias]['id'];
			if (!empty($modelId)) {
				$foreignKey = $modelId;
			} else {
				$foreignKey = $Model->id;
			}
			$token = $this->Token->find('first', array(
				'conditions' => array(
					'Token.model' => $modelAlias,
					'Token.foreign_key' => $foreignKey,
				)
			));
			if (empty($token)) {
				$Model->Behaviors->attach('Givrate.Tokenable');
				$token = $Model->Behaviors->Tokenable->__GenerateUniqid($len);
				if ($Model->Behaviors->Tokenable->__isValidToken($token)) {
					if ($Model->Behaviors->Tokenable->__saveToken($Model, $token)) {
						$this->out(sprintf(__('Successful generate token for model <success>%s</success> id <bold>%d</bold>', $Model->alias, $Model->id)));
					} else {
						$this->out(sprintf(__('Failed generate token for model <failed>%s</failed> id <bold>%d</bold>', $Model->alias, $Model->id)));
					}
				}
			} else {
				$this->out(sprintf(__('Model <failed>%s</failed> id <bold>%d</bold> already have token', $Model->alias, $Model->id)));
			}
		}
	}

/**
 * Get list all activate plugins
 */
	public function list_plugins($plugin = null) {
		App::uses('CroogoPlugin','Extensions.Lib');
		$all = $this->params['all'];
		$plugins = $plugin == null ? App::objects('plugins') : array($plugin);
		$loaded = CakePlugin::loaded();
		$CroogoPlugin = new CroogoPlugin();
		$this->out(__('Plugins:'), 2);
		$this->out(__('%-20s%-50s%s', __('Plugin'), __('Author'), __('Status')));
		$this->out(str_repeat('-', 80));
		foreach ($plugins as $plugin) {
			$status = '<info>inactive</info>';
			if ($active = in_array($plugin, $loaded)) {
				$status = '<success>active</success>';
			}
			if (!$active && !$all) {
				continue;
			}
			$data = $CroogoPlugin->getPluginData($plugin);
			$author = isset($data['author']) ? $data['author'] : '';
			$this->out(__('%-20s%-50s%s', $plugin, $author, $status));
		}
	}

}
