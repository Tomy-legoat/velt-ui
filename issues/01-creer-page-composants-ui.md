# Issue 01 - Creer Page et composants UI

## Labels

`module:1-foundations`, `area:ui`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Implementer la syntaxe officielle de Velt sous forme d'objets PHP declaratifs.

## Composants MVP

- `Page`
- `Card`
- `Text`
- `Alert`
- `Form`
- `Input`
- `Button`
- `Link`

## API cible

```php
Page::make('Connexion')
    ->layout('auth')
    ->meta(['title' => 'Connexion - Velt App'])
    ->add(
        Card::make()->class('p-8')->children([
            Text::make('Connexion')->as('h1'),
            Button::make('Se connecter')->type('submit')->variant('primary'),
        ])
    );
```

## Travail attendu

- Creer une classe abstraite ou base `Component`.
- Stocker `type`, `props`, `children` et contenu textuel.
- Supporter les methodes chainables.
- Ajouter `toArray()` pour chaque composant.
- Ajouter validation minimale des enfants.

## Contraintes

- Les composants ne doivent pas generer directement du HTML.
- Les composants doivent rester serialisables.
- La syntaxe doit etre lisible par un developpeur PHP junior.

## Criteres d'acceptation

- La syntaxe officielle fonctionne.
- Une page produit un tableau stable.
- Les props comme `class`, `type`, `variant`, `label`, `href`, `method`, `action` sont conservees.
- Les tests couvrent chaque composant MVP.

## Definition of Done

- Composants crees.
- Tests unitaires verts.
- README avec exemple officiel.

