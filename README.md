# README

[![Release](https://img.shields.io/github/v/release/JcDenis/disclaimer)](https://github.com/JcDenis/disclaimer/releases)
[![Date](https://img.shields.io/github/release-date/JcDenis/disclaimer)](https://github.com/JcDenis/disclaimer/releases)
[![Issues](https://img.shields.io/github/issues/JcDenis/disclaimer)](https://github.com/JcDenis/disclaimer/issues)
[![Dotclear](https://img.shields.io/badge/dotclear-v2.26-blue.svg)](https://fr.dotclear.org/download)
[![Dotaddict](https://img.shields.io/badge/dotaddict-official-green.svg)](https://plugins.dotaddict.org/dc2/details/disclaimer)
[![License](https://img.shields.io/github/license/JcDenis/disclaimer)](https://github.com/JcDenis/disclaimer/blob/master/LICENSE)

## WHAT IS  DISCLAIMER ?

_disclaimer_  is a plugin for the open-source 
web publishing software called Dotclear.

It add a disclaimer to your blog entrance.

This plugin is inspired from plugin "Private mode"  by Osku.

## REQUIREMENTS

 _disclaimer_ requires: 

  * admin permissions on blog
  * Dotclear 2.26

## USAGE

First install _disclaimer_, manualy from a zip package or from 
Dotaddict repository. (See Dotclear's documentation to know how do this)

You can activate and setup _disclaimer_ from blog preferences page.

## LINKS

 * License : [GNU GPL v2](https://www.gnu.org/licenses/old-licenses/lgpl-2.0.html)
 * Source & contribution : [GitHub Page](https://github.com/JcDenis/disclaimer)
 * Packages & details : [Dotaddict Page](https://plugins.dotaddict.org/dc2/details/disclaimer)
 * Help & discuss : [Dotclear forum](http://forum.dotclear.org/viewtopic.php?id=40000)

## CONTRIBUTORS

 * Jean-Christian Denis (author)
 * Pierre Van Glabeke

 You are welcome to contribute to this code.

## HELP (in french)

### Paramètres

La configuration du plugin est situé dans
la rubrique "Disclaimer" de la page des paramètres du blog.

#### Activer le plugin

Permet d'activer ou non la page d'avertissement.

#### Se souvenir de l'utilisateur

Permet d'envoyer un cookie au visiteur pour qu'il n'ait pas
à revalider l'avertissement lors d'une visite ultérieure.

#### Titre

C'est le titre principale de la page d'avertissement.

#### Lien de sortie

Lien vers lequel sera renvoyé le visiteur s'il refuse les termes.

#### Avertissement

Texte principal de la page d'avertissement, cette page accepte le code html.
(sauf si l'attribut encode_html est actif dans les templates)

#### Liste des robots autorisés à indexer les pages du site

Liste des robots d'indexation séparés par un point-virgule.
Cela permet au robot utilisant ce user-agent de ne pas être bloqué par
le disclaimer.

#### Désactiver l'autorisation d'indexation par les moteurs de recherches

Permet de désactiver la fonction de recherche de user-agent et de rediriger
tous les user-agent vers le disclaimer.

### Templates

Le fichier de template par default pour la page d'avertissement 
se situe dans le repertoire "/default-template/mustek/disclaimer.html" ou
"/default-template/dotty/disclaimer.html" du plugin.
Il sera utilisé par défaut, sinon il faut le copier 
dans le repertoire /tpl de votre thème pour le modifier.

### Balises

Le plugin ajoute les balises de template suivantes :

#### DisclaimerTitle

Titre de l'avertissement.

#### DisclaimerText

Texte de l'avertissement.

#### DisclaimerFormURL

A mettre dans l'attribut "action" de la balise "form".

Ces balises supportent les attributs communs.
