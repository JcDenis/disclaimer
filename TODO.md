# TODO

## Le disclaimer bloque les appels d'API (ApiServer, ClaudetteMcp)

### Constat

Sur un blog où _disclaimer_ est actif, toute requête publique sans session reçoit la page de consentement, y compris les appels à l'URL `api` du plugin ApiServer.

Vu le 11 novembre 2026 en ajoutant un connecteur ClaudetteMcp sur un blog de dev : la requête `POST index.php?api/mcp/<clé>` a reçu la page du disclaimer (HTML, statut 200) au lieu de la réponse JSON, et le client a refusé le connecteur.

Les autres clients d'ApiServer (DotclearWatch, daContribute) sont a priori bloqués de la même façon.

### Où ça se passe

`src/UrlHandler.php`, `publicBeforeDocumentV2()` : tous les types d'URL enregistrés sont remplacés par `overwriteCallbacks()`. La seule exemption existante est le test du user-agent pour les robots (`disclaimer_bots_agents`).

### Pistes

* Exempter des types d'URL : au minimum le type `ApiServer`, ou mieux une liste de types exemptés, complétée par un behavior (ex : `disclaimerExcludedUrlTypes`) pour que les plugins déclarent les leurs.
* Garder les flux et le reste sous disclaimer comme aujourd'hui.

Contournement en attendant : ajouter le user-agent du client (ex : `python-httpx`) à la liste des robots autorisés, mais c'est fragile.

Fichier à supprimer une fois la correction faite sur cette branche.
