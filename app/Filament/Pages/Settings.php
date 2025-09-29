<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Filament\Notifications\Notification;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;

class Settings extends Page implements Forms\Contracts\HasForms
{
    use WithFileUploads, Forms\Concerns\InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;
    protected string $view                                  = 'filament.pages.settings';
    protected static ?int $navigationSort                   = 101;

    public $settings;
    public $items = [];

    public function mount(): void
    {
        $this->settings = Setting::with('settingItems')->orderBy('order')->get();

        foreach ($this->settings as $setting) {
            foreach ($setting->settingItems as $item) {
                $this->items[$item->id] = $item->value;
            }
        }

        $this->form->fill([
            'items' => $this->items,
        ]);
    }

    protected function getFormSchema(): array
    {
        $schema = [];

        foreach ($this->settings as $setting) {
            $settingFields = [];

            foreach ($setting->settingItems as $item) {
                $id    = $item->id;
                $label = $item->name;

                switch ($item->type) {
                    case 'text':
                        $field = TextInput::make("items.$id")
                            ->label($label);
                        break;
                    case 'textarea':
                        $field = Textarea::make("items.$id")
                            ->label($label)
                            ->rows(5);
                        break;
                    case 'textarea_editor':
                        $field = RichEditor::make("items.$id")
                            ->label($label)
                            ->toolbarButtons([]);
                        break;
                    case 'url':
                        $field = TextInput::make("items.$id")
                            ->label($label)
                            ->url();
                        break;
                    case 'number':
                        $field = TextInput::make("items.$id")
                            ->label($label)
                            ->numeric();
                        break;
                    case 'email':
                        $field = TextInput::make("items.$id")
                            ->label($label)->email();
                        break;
                    case 'color':
                        $field = ColorPicker::make("items.$id")
                            ->label($label);
                        break;
                    case 'file':
                        $field = FileUpload::make("items.$id")
                            ->label($label)
                            ->disk('public')
                            ->directory('setting-items')
                            ->openable()
                            ->maxSize(2048);
                        break;
                    default:
                        continue 2;
                }

                $settingFields[] = $field;

                if (!empty($item->helper_text)) {
                    $field->helperText($item->helper_text);
                }
            }

            if (!empty($settingFields)) {
                $schema[] = Section::make($setting->name)
                    ->schema($settingFields);
            }
        }

        return $schema;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($this->settings as $setting) {
            foreach ($setting->settingItems as $item) {
                $itemId = $item->id;

                if (in_array($item->type, ['text', 'textarea', 'textarea_editor', 'url', 'number', 'email', 'color', 'file'])) {
                    $item->value = $data['items'][$itemId] ?? null;
                }

                $item->save();
            }
        }

        Notification::make()
            ->title('Succeed')
            ->body('Settings updated successfully.')
            ->success()
            ->send();
    }
}
