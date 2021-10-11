<?php

namespace Best4u\Core\Config;

use Best4u\Core\Common\Abstracts\Base;

/**
 * Updater class that updates the plugin using Github releases.
 */
class Updater extends Base
{
    /**
     * Holds the Github repos base URI
     *
     * @since 0.0.1
     */
    const BASE_URI = 'https://api.github.com/repos/';

    /**
     * Holds the Github repo owner username
     *
     * @since 0.0.1
     */
    const USERNAME = 'Best4u-Media';

    /**
     * Holds the Github repo name
     *
     * @since 0.0.1
     */
    const REPO = 'Best4u-Core-Plugin';

    /**
     * Holds the access token used for accessing private repos
     *
     * @since 0.0.1
     */
    const ACCESS_TOKEN = '';

    /**
     * Current plugin data
     *
     * @var array
     * @since 0.0.1
     */
    protected $pluginData;

    /**
     * Github api result
     *
     * @var array|object
     * @since 0.0.1
     */
    protected $githubApiResult;

    /**
     * Registers WordPress hooks
     *
     * @return void
     * @since 0.0.1
     */
    public function init()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'setTransient']);
        add_filter('plugins_api', [$this, 'setPluginInfo'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'postInstall'], 10, 3);
    }

    /**
     * Gets the WordPress plugin data
     *
     * @return void
     * @since 0.0.1
     */
    protected function initPluginData()
    {
        $this->pluginData = get_plugin_data($this->context->file());
    }

    /**
     * Generates the releases request URL
     *
     * @return string
     */
    protected function buildUrl() : string
    {
        $url = self::BASE_URI . self::USERNAME . '/' . self::REPO . '/releases';

        if (!empty(self::ACCESS_TOKEN)) {
            $url = add_query_arg(['access_token' => self::ACCESS_TOKEN], $url);
        }

        return $url;
    }

    /**
     * Generates the package download url. Returns false when none can be generated
     *
     * @return string|bool
     */
    protected function getDownloadUrl() : string|bool
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

    /**
     * Gets the latest release info from Github
     *
     * @return void
     */
    protected function getRepoReleaseInfo()
    {
        if (!empty($this->githubApiResult)) {
            return;
        }

        $url = $this->buildUrl();

        $result = wp_remote_retrieve_body(wp_remote_get($url));
        if (empty($result)) {
            $this->githubApiResult = false;
            return;
        }

        $this->githubApiResult = @json_decode($result);

        if (!is_array($this->githubApiResult) || !count($this->githubApiResult)) {
            return;
        }

        $this->githubApiResult = $this->githubApiResult[0];
    }

    /**
     * Sets the plugin update transient
     *
     * @param mixed $transient
     * @return mixed
     */
    public function setTransient($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $this->initPluginData();
        $this->getRepoReleaseInfo();

        if (!isset($this->githubApiResult->tag_name)) {
            return $transient;
        }

        $doUpdate = version_compare($this->githubApiResult->tag_name, $transient->checked[$this->context->basename()]);

        if (!$doUpdate) {
            return;
        }

        $package = $this->getDownloadUrl();

        if (!empty(self::ACCESS_TOKEN)) {
            $package = add_query_arg(['access_token' => self::ACCESS_TOKEN], $package);
        }

        $update_object = new \stdClass();
        $update_object->slug = $this->context->basename();
        $update_object->new_version = $this->githubApiResult->tag_name;
        $update_object->url = $this->pluginData['PluginURI'];
        $update_object->package = $package;
        $transient->response[$this->context->basename()] = $update_object;

        return $transient;
    }

    /**
     * Sets the plugin info thats visible in the plugin detail popup
     *
     * @param bool $false deprecated
     * @param mixed $action
     * @param object $response
     * @return object|bool
     */
    public function setPluginInfo($false, $action, $response)
    {
        $this->getRepoReleaseInfo();

        if (empty($response->slug) || $response->slug !== $this->context->basename()) {
            return false;
        }

        if (!isset($this->githubApiResult->tag_name)) {
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

    /**
     * Renames the directory to the current plugin directory name
     *
     * @param bool $true deprecated
     * @param mixed $hook_extra
     * @param array $result
     * @return array
     */
    public function postInstall($true, $hook_extra, $result) : array
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
