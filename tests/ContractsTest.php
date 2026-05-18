<?php

declare(strict_types=1);

namespace Velt\Ui\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Ui\Components\Button;
use Velt\Ui\Components\Component;
use Velt\Ui\Contracts\ComponentInterface;
use Velt\Ui\Contracts\RendererInterface;
use Velt\Ui\Contracts\ViewInterface;
use Velt\Ui\Page;
use Velt\Ui\Renderers\JsonRenderer;
use Velt\Ui\Renderers\WebRenderer;

class ContractsTest extends TestCase
{
    public function test_core_classes_respect_public_contracts(): void
    {
        $this->assertInstanceOf(ComponentInterface::class, Button::make('OK'));
        $this->assertInstanceOf(ViewInterface::class, Page::make('Accueil'));
        $this->assertInstanceOf(RendererInterface::class, new JsonRenderer());
        $this->assertInstanceOf(RendererInterface::class, new WebRenderer());
    }

    public function test_preview_can_serialize_a_fake_component_contract(): void
    {
        $page = Page::make('Fake')->add(
            FakePreviewComponent::make()
                ->class('custom')
                ->showIf('feature.enabled')
        );

        $data = (new JsonRenderer())->toPreviewArray($page);

        $this->assertSame('FakePreview', $data['components'][0]['type']);
        $this->assertSame('custom', $data['components'][0]['props']['class']);
        $this->assertSame('feature.enabled', $data['components'][0]['props']['showIf']);
    }
}

final class FakePreviewComponent extends Component
{
    protected string $type = 'fakePreview';

    public static function make(): self
    {
        return new self();
    }
}
