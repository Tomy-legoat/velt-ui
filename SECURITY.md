# Security Policy

La securite de Velt UI repose sur un principe simple : ce package doit rester une bibliotheque declarative pure. Il ne doit pas executer de logique metier, gerer la session ou fabriquer des donnees de securite a la place du kernel.

## Versions supportees

Le projet est en phase fondation. Tant qu'il n'y a pas de version stable publiee, les correctifs de securite ciblent la branche principale active.

| Version | Support |
| --- | --- |
| `dev-main` | oui |
| versions non publiees | non garanti |

## Signaler une vulnerabilite

Si vous trouvez une faille de securite, ne creez pas d'issue publique avec un exploit complet.

Envoyez plutot un rapport prive au mainteneur du projet avec :

- une description claire du probleme ;
- les fichiers ou composants concernes ;
- un exemple minimal de reproduction ;
- l'impact possible ;
- une proposition de correction si vous en avez une.

Adresse de contact actuelle :

```text
julesmukadi.dev@gmail.com
```

## Perimetre de securite de Velt UI

Velt UI est responsable de :

- echapper les textes rendus en HTML ;
- echapper les attributs HTML ;
- refuser les chemins dangereux dans `ViewFactory` ;
- ne pas inclure de HTML dans le JSON Preview ;
- ne pas evaluer `showIf` dans le Module 1 ;
- ne pas generer de faux token CSRF ;
- garder les contrats publics previsibles.

Velt UI n'est pas responsable de :

- authentification ;
- autorisation ;
- session ;
- stockage des tokens CSRF ;
- validation des formulaires ;
- protection rate limit ;
- execution des controleurs ;
- politiques CORS ;
- headers HTTP de securite ;
- chiffrement ;
- gestion des secrets.

Ces responsabilites appartiennent au kernel, au module HTTP ou aux modules applicatifs.

## HTML et escaping

Toute sortie HTML doit passer par l'echappement.

Regles :

- le contenu texte doit etre echappe ;
- les valeurs d'attributs doivent etre echappees ;
- les attributs `null` et `false` ne doivent pas etre rendus ;
- les attributs booleens doivent etre rendus sans valeur ;
- les tags dynamiques doivent etre limites a une allowlist.

Exemple : `Text::as()` ne doit pas permettre de rendre `script`.

## CSRF

`Form::csrf()` exprime seulement une intention.

Velt UI ne doit jamais :

- creer un token aleatoire sans session ;
- inventer un champ `_token` silencieux ;
- stocker un token ;
- verifier un token.

Le champ CSRF reel doit etre fourni par la couche HTTP/session :

```php
$renderer = new WebRenderer(
    fn (array $form): string => $csrf->field()
);
```

## Chargement des vues

`ViewFactory` accepte des noms logiques comme :

```text
auth.login
```

Et les resout vers :

```text
auth/login.velt.php
```

Les chemins dangereux doivent etre refuses :

- `../secret`
- `auth/../secret`
- `auth\login`
- chemins absolus
- noms vides

Une vue `.velt.php` doit retourner une instance de `Velt\Ui\Page`.

## JSON Preview

Le JSON Preview doit rester declaratif.

Regles :

- inclure `schemaVersion` ;
- ne pas inclure de HTML ;
- ne pas evaluer `showIf` ;
- conserver les intentions logiques ;
- garder une structure stable pour les clients mobiles.

Le client Preview ou le kernel peut decider comment interpreter certaines props, mais Velt UI ne doit pas executer de logique applicative.

## Contributions de securite

Toute contribution qui touche la securite doit inclure des tests.

Cas minimum a tester :

- escaping de texte dangereux ;
- escaping d'attribut dangereux ;
- tag texte non autorise ;
- chemin de vue dangereux ;
- comportement CSRF sans resolver ;
- JSON Preview sans HTML injecte.

## Secrets

Ne jamais committer :

- cles API ;
- tokens ;
- certificats prives ;
- fichiers `.env` reels ;
- dumps de base de donnees ;
- donnees utilisateur.

## Dependances

Le package doit garder peu de dependances.

Avant d'ajouter une dependance :

- verifier si PHP standard suffit ;
- evaluer la surface d'attaque ajoutee ;
- verifier la maintenance de la dependance ;
- documenter pourquoi elle est necessaire.

## Reponse aux incidents

Pour une vulnerabilite confirmee :

1. reproduire le probleme ;
2. ajouter un test qui echoue ;
3. corriger le code ;
4. verifier que la correction ne casse pas le contrat public ;
5. documenter l'impact ;
6. publier la correction.

Si le schema JSON Preview doit changer pour corriger une faille, augmenter `schemaVersion` ou documenter clairement la compatibilite.
