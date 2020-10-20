#Simple Google Serp (search) API
Scraper Google simplement avec cette petite API facile à utiliser. 
Avec l'aide de scraperapi.com, l'api renvoie sous forme de json les 100 premiers résultats sur un mot clé.
Il y a aussi la data de knowledge_graph et local.

##Prérequis
- Un compte [scraperapi](https://www.scraperapi.com/dashboard) gratuit ou payant.
- Clé api à mettre dans le fichier ***api.php***.

##Utilisation

Pour utiliser, il faut lancer le fichier :

``` api.php?keyword=restaurant```
### Params
L'unique paramètre à utiliser :

`keyword`

Mettre un `+` pour faire une expression de mot clés :

``` api.php?keyword=table+ronde ```

### Éléments

Champ | Description
------|------------
**infos** | Les informations concernant la requête.
query_filter | Nom du mot clé
nb_results | Le nombre de résultat sur la page lié au mot clé.
**organic** | Les résultats organiques de 1 à 100.
position  | la position du site sur la serp.
url | l'url de la page positionnée
title | titre de la page du site positionnée
description | la description affiché dans la serp en dessous du titre.
link | Le chemin que l'on voit en gris jsute au dessus du titre.
cachedLink | Le lien vers le cache Google de la page.
**knowledge_graph** | les informations du knowledge_graph
associatedResearch | Recherches associées (liens, noms, images)
**questionList** | Autres questions posées
**HeaderBlock** | Le bloc en en haut de page sur la news.
