<?php

namespace liquidbcn\craftcmsreact;

use Craft;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use Limenius\ReactRenderer\Renderer\PhpExecJsReactRenderer;
use Limenius\ReactRenderer\Twig\ReactRenderExtension;
use liquidbcn\craftcmsreact\models\Settings;
use liquidbcn\craftcmsreact\context\CraftContextProvider;
use liquidbcn\craftcmsreact\twig\SerializerExtension;

/**
 * craftcms-react plugin
 *
 * @method static Plugin getInstance()
 * @method Settings getSettings()
 * @author liquidbcn <mbernet@liquid.cat>
 * @copyright liquidbcn
 * @license MIT
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            // ...
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('craftcms-react/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        $env = $this->getSettings()->env;
        $serverBundle = CRAFT_BASE_PATH.DIRECTORY_SEPARATOR.$this->getSettings()->serverBundle;

        $contextProvider = new CraftContextProvider(Craft::$app->request);
        $renderer = new PhpExecJsReactRenderer($serverBundle, $env != 'client_side', $contextProvider);
        $ext = new ReactRenderExtension($renderer, $contextProvider, $env);
        $ext2 = new SerializerExtension();
        Craft::$app->view->registerTwigExtension($ext2);
        Craft::$app->view->registerTwigExtension($ext);
    }
}
