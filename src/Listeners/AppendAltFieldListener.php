<?php

namespace WADM\MultisiteForAssets\Listeners;

use Statamic\Events\AssetContainerBlueprintFound;
use Statamic\Facades\Site;
use Statamic\Support\Str;

class AppendAltFieldListener
{
    /**
     * @param \Statamic\Events\AssetContainerBlueprintFound $event
     *
     * @return void
     */
    public function handle(AssetContainerBlueprintFound $event)
    {
        // We don't want the SEO fields to get added to the blueprint editor
        if (!Str::contains(request()->url(), '/blueprint')) {
            return;
        }

        // Get already registered fields
        $contents = $event->blueprint->contents();
        $type = array_key_exists('tabs', $contents) ? 'tabs' : 'sections';
        $fields = $contents[$type]['main']['fields'] ?? [];

        // Create the fields
        foreach (Site::all() as $site) {
            $fields = $this->createAltField($site, $fields);
            $fields = $this->createTitleField($site, $fields);
        }

        // Update the blueprint with new added fields
        $contents[$type]['main']['fields'] = $fields;
        $event->blueprint->setContents($contents)
            ->save();
    }

    protected function createAltField($site, $fields)
    {
        // Check if field is already added within the fields array
        $key = array_search('alt_' . $site->handle(), array_column($fields, 'handle'));
        if (!is_int($key) && $key == false) {
            array_push($fields, $this->createField('alt', 'Alt', $site->handle()));
        }

        return $fields;
    }

    protected function createTitleField($site, $fields)
    {
        // Check if field is already added within the fields array
        $key = array_search('title_' . $site->handle(), array_column($fields, 'handle'));
        if (!is_int($key) && $key == false) {
            array_push($fields, $this->createField('title', 'Title', $site->handle()));
        }

        return $fields;
    }

    /**
     * Create field.
     *
     * @param $handle
     * @param $site
     * @return array
     */
    protected function createField($handle, $label, $site)
    {
        return [
            'handle' => sprintf('%s_%s', $handle, $site),
            'field' => [
                "input_type" => "text",
                "antlers" => false,
                "display" => $label,
                "type" => "text",
                "icon" => "text",
                "localizable" => false,
                "listable" => "hidden",
                "instructions_position" => "above",
                "visibility" => "visible",
                "if" => "isOnSite:" . $site
            ],
        ];
    }
}
