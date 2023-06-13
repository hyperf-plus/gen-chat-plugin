<?php
declare(strict_types=1);

namespace HPlus\ChatPlugins\Annotation;

use Attribute;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ChatPluginAnnotation extends AbstractAnnotation
{
    public function __construct(
        protected string $plugin_id = '',
        protected string $schema_version = 'v1',
        protected string $name_for_human = '',
        protected string $name_for_model = '',
        protected string $description_for_human = '',
        protected string $description_for_model = '',
        protected array  $auth = [],
        protected array  $api = [],
        protected string $logo_url = '',
        protected string $contact_email = '',
        protected string $legal_info_url = '',
    )
    {
    }

    /**
     * @return string
     */
    public function getPluginId(): string
    {
        return $this->plugin_id;
    }

    /**
     * @param string $plugin_id
     */
    public function setPluginId(string $plugin_id): void
    {
        $this->plugin_id = $plugin_id;
    }

    /**
     * @return string
     */
    public function getSchemaVersion(): string
    {
        return $this->schema_version;
    }

    /**
     * @param string $schema_version
     */
    public function setSchemaVersion(string $schema_version): void
    {
        $this->schema_version = $schema_version;
    }

    /**
     * @return string
     */
    public function getNameForHuman(): string
    {
        return $this->name_for_human;
    }

    /**
     * @param string $name_for_human
     */
    public function setNameForHuman(string $name_for_human): void
    {
        $this->name_for_human = $name_for_human;
    }

    /**
     * @return string
     */
    public function getNameForModel(): string
    {
        return $this->name_for_model;
    }

    /**
     * @param string $name_for_model
     */
    public function setNameForModel(string $name_for_model): void
    {
        $this->name_for_model = $name_for_model;
    }

    /**
     * @return string
     */
    public function getDescriptionForHuman(): string
    {
        return $this->description_for_human;
    }

    /**
     * @param string $description_for_human
     */
    public function setDescriptionForHuman(string $description_for_human): void
    {
        $this->description_for_human = $description_for_human;
    }

    /**
     * @return string
     */
    public function getDescriptionForModel(): string
    {
        return $this->description_for_model;
    }

    /**
     * @param string $description_for_model
     */
    public function setDescriptionForModel(string $description_for_model): void
    {
        $this->description_for_model = $description_for_model;
    }

    /**
     * @return array
     */
    public function getAuth(): array
    {
        return $this->auth;
    }

    /**
     * @param array $auth
     */
    public function setAuth(array $auth): void
    {
        $this->auth = $auth;
    }

    /**
     * @return array
     */
    public function getApi(): array
    {
        return $this->api;
    }

    /**
     * @param array $api
     */
    public function setApi(array $api): void
    {
        $this->api = $api;
    }

    /**
     * @return string
     */
    public function getLogoUrl(): string
    {
        return $this->logo_url;
    }

    /**
     * @param string $logo_url
     */
    public function setLogoUrl(string $logo_url): void
    {
        $this->logo_url = $logo_url;
    }

    /**
     * @return string
     */
    public function getContactEmail(): string
    {
        return $this->contact_email;
    }

    /**
     * @param string $contact_email
     */
    public function setContactEmail(string $contact_email): void
    {
        $this->contact_email = $contact_email;
    }

    /**
     * @return string
     */
    public function getLegalInfoUrl(): string
    {
        return $this->legal_info_url;
    }

    /**
     * @param string $legal_info_url
     */
    public function setLegalInfoUrl(string $legal_info_url): void
    {
        $this->legal_info_url = $legal_info_url;
    }

    public function toArray(): array
    {
        $config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $items = $config->get('plugins.plugin') ?: [];
        foreach ($items as $k => $v) {
            if (property_exists($this, $k) && empty($this->{$k})) {
                $this->{$k} = $v;
            }
        }
        $items = [];
        foreach ($this as $k => $v) {
            if ($k == 'plugin_id') {
                continue;
            }
            $items[$k] = $v;
        }
        return $items;
    }

}
