<?php

namespace Best4u\Core\Config;

use Best4u\Core\Common\Abstracts\Base;

class Updater extends Base
{
    const BASE_URI = 'https://api.github.com/repos/';

    const USERNAME = 'Best4u-Media';
    const REPO = 'Best4u-Core-Plugin';
    const ACCESS_TOKEN = '';

    protected $pluginData;
    protected $githubApiResult;

    public function __construct()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'setTransient']);
        add_filter('plugins_api', [$this, 'setPluginInfo'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'postInstall'], 10, 3);
    }

    protected function initPluginData()
    {
        $this->pluginData = get_plugin_data($this->context->file());
    }

    protected function buildUrl()
    {
        $url = self::BASE_URI . self::USERNAME . '/' . self::REPO . '/releases';

        if (!empty(self::ACCESS_TOKEN)) {
            $url = add_query_arg(['access_token' => self::ACCESS_TOKEN], $url);
        }

        return $url;
    }

    protected function getDownloadUrl()
    {
        if (empty($this->githubApiResult)) {
            return false;
        }

        if (!is_array($this->githubApiResult->assets) || !count($this->githubApiResult->assets)) {
            $url = $this->githubApiResult->zipball_url;
        } else {
            $url = $this->githubApiResult->assets[0]->browser_download_url;
        }

        if (!empty(self::ACCESS_TOKEN)) {
            $url = add_query_arg(['access_token' => self::ACCESS_TOKEN]);
        }

        return $url;
    }

    protected function getRepoReleaseInfo()
    {
        if (!empty($this->githubApiResult)) {
            return;
        }

        $url = $this->buildUrl();

        $result = wp_remote_retrieve_body(wp_remote_get($url));
        if (empty($result)) {
            return;
        }

        $this->githubApiResult = @json_decode($this->githubApiResult);

        if (!is_array($this->githubApiResult)) {
            return;
        }

        $this->githubApiResult = $this->githubApiResult[0];
    }

    public function setTransient($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $this->initPluginData();
        $this->getRepoReleaseInfo();

        $doUpdate = version_compare($this->githubApiResult->tag_name, $transient->checked[$this->context->basename()]);

        if (!$doUpdate) {
            return;
        }

        $package = $this->getDownloadUrl();

        if (!empty(self::ACCESS_TOKEN)) {
            $package = add_query_arg(['access_token' => self::ACCESS_TOKEN], $package);
        }

        $update_object = new stdClass();
        $update_object->slug = $this->context->basename();
        $update_object->new_version = $this->githubApiResult->tag_name;
        $update_object->url = $this->pluginData['PluginURI'];
        $update_object->package = $package;
        $transient->response[$this->context->basename()] = $object;

        return $transient;
    }

    public function setPluginInfo($false, $action, $response)
    {
        $this->getRepoReleaseInfo();

        if (empty($response->slug) || $response->slug !== $this->context->basename()) {
            return false;
        }

        $response->last_updated = $this->githubApiResult->published_at;
        $response->slug = $this->context->basename();
        $response->plugin_name = $this->pluginData['Name'];
        $response->version = $this->githubApiResult->tag_name;
        $response->author = $this->pluginData['AuthorName'];
        $response->homepage = $this->pluginData['PluginURI'];
        $response->download_link = $this->getDownloadUrl();
        $response->sections = [
            'description' => $this->pluginData['Description'],
            'changelog' => class_exists('Parsedown')
                ? Parsedown::instance()->parse($this->githubApiResult->body)
                : $this->githubApiResult->body
        ];

        return $response;
    }

    public function postInstall($true, $hook_extra, $result)
    {
        $wasActivated = is_plugin_active($this->context->basename());

        global $wp_filesystem;
        $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname($this->context->basename());
        $wp_filesystem->move($result['destination'], $pluginFolder);
        $result['destination'] = $pluginFolder;

        if ($wasActivated) {
            $active = activate_plugin($this->context->basename());
        }

        return $result;
    }
}
