<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Velt\Ui\Page;
use Velt\Ui\Components\Card;
use Velt\Ui\Components\Text;
use Velt\Ui\Components\Button;

// Test de l'API cible exacte de l'Issue 01
$page = Page::make('Connexion')
    ->layout('auth')
    ->meta(['title' => 'Connexion - Velt App'])
    ->add(
        Card::make()->class('p-8')->add(
            Text::make('Connexion')->as('h1')
        )->add(
            Button::make('Se connecter')->type('submit')->variant('primary')
        )
    );

echo 'API cible fonctionne !' . PHP_EOL;
$data = $page->toArray();
echo 'Type: ' . $data['type'] . PHP_EOL;
echo 'Title: ' . $data['title'] . PHP_EOL;
echo 'Layout: ' . $data['layout'] . PHP_EOL;
echo 'Children count: ' . count($data['children']) . PHP_EOL;

$card = $data['children'][0];
echo 'First child type: ' . $card['type'] . PHP_EOL;
echo 'Card class: ' . $card['props']['class'] . PHP_EOL;
echo 'Card children count: ' . count($card['children']) . PHP_EOL;

$text = $card['children'][0];
echo 'Text content: ' . $text['content'] . PHP_EOL;
echo 'Text as: ' . $text['props']['as'] . PHP_EOL;

$button = $card['children'][1];
echo 'Button content: ' . $button['content'] . PHP_EOL;
echo 'Button type: ' . $button['props']['type'] . PHP_EOL;
echo 'Button variant: ' . $button['props']['variant'] . PHP_EOL;

echo PHP_EOL . 'Test JSON serialization:' . PHP_EOL;
$json = $page->toJson();
echo 'JSON valid: ' . (json_decode($json) !== null ? 'YES' : 'NO') . PHP_EOL;
